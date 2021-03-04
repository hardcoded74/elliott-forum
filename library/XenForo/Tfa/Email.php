<?php

class XenForo_Tfa_Email extends XenForo_Tfa_AbstractProvider
{
	public function getTitle()
	{
		return new XenForo_Phrase('tfa_email_confirmation');
	}

	public function getDescription()
	{
		return new XenForo_Phrase('tfa_email_confirmation_desc');
	}

	public function generateInitialData(array $user, array $setupData)
	{
		return array();
	}

	public function triggerVerification($context, array $user, $ip, array &$providerData)
	{
		$length = 6;

		$random = XenForo_Application::generateRandomString(4, true);
		$code = (
				((ord($random[0]) & 0x7f) << 24 ) |
				((ord($random[1]) & 0xff) << 16 ) |
				((ord($random[2]) & 0xff) << 8 ) |
				(ord($random[3]) & 0xff)
            ) % pow(10, $length);
		$code = str_pad($code, $length, '0', STR_PAD_LEFT);

		$providerData['code'] = $code;
		$providerData['codeGenerated'] = time();

		$mail = XenForo_Mail::create('two_step_login_email', array(
			'code' => $code,
			'user' => $user,
			'ip' => $ip
		), $user['language_id']);
		$mail->send($user['email'], $user['username']);

		return array();
	}

	public function renderVerification(XenForo_View $view, $context, array $user, array $providerData, array $triggerData)
	{
		$params = array(
			'email' => $user['email'],
			'data' => $providerData,
			'context' => $context,
		);
		return $view->createTemplateObject('two_step_email', $params)->render();
	}

	public function verifyFromInput($context, XenForo_Input $input, array $user, array &$providerData)
	{
		if (empty($providerData['code']) || empty($providerData['codeGenerated']))
		{
			return false;
		}

		if (time() - $providerData['codeGenerated'] > 900)
		{
			return false;
		}

		$code = $input->filterSingle('code', XenForo_Input::STRING);
		$code = preg_replace('/[^0-9]/', '', $code);

		if (!XenForo_Application::hashEquals($providerData['code'], $code))
		{
			return false;
		}

		unset($providerData['code']);
		unset($providerData['codeGenerated']);

		return true;
	}

	public function meetsRequirements(array $user, &$error)
	{
		if (empty($user['email']) && $user['user_state'] != 'valid')
		{
			$error = new XenForo_Phrase('you_must_have_valid_email_account_confirmed');
			return false;
		}

		return true;
	}
}