<?php

class XenForo_Tfa_Backup extends XenForo_Tfa_AbstractProvider
{
	public function getTitle()
	{
		return new XenForo_Phrase('tfa_backup_codes');
	}

	public function getDescription()
	{
		return new XenForo_Phrase('tfa_backup_codes_desc');
	}

	public function generateInitialData(array $user, array $setupData)
	{
		$codes = array();
		$total = 10;
		$length = 9;
		$random = XenForo_Application::generateRandomString(4 * $total, true);

		for ($i = 0; $i < $total; $i++)
		{
			$offset = $i * 4; // 4 bytes for each set

			$code = (
					((ord($random[$offset + 0]) & 0x7f) << 24 ) |
					((ord($random[$offset + 1]) & 0xff) << 16 ) |
					((ord($random[$offset + 2]) & 0xff) << 8 ) |
					(ord($random[$offset + 3]) & 0xff)
				) % pow(10, $length);
			$code = str_pad($code, $length, '0', STR_PAD_LEFT);

			$codes[] = $code;
		}

		return array(
			'codes' => $codes,
			'used' => array()
		);
	}

	public function triggerVerification($context, array $user, $ip, array &$providerData)
	{
		return array();
	}

	public function renderVerification(XenForo_View $view, $context, array $user, array $providerData, array $triggerData)
	{
		$params = array(
			'data' => $providerData,
			'context' => $context,
		);
		return $view->createTemplateObject('two_step_backup', $params)->render();
	}

	public function verifyFromInput($context, XenForo_Input $input, array $user, array &$providerData)
	{
		$code = $input->filterSingle('code', XenForo_Input::STRING);
		$code = preg_replace('/[^0-9]/', '', $code);
		if (!$code)
		{
			return false;
		}

		$matched = null;

		foreach ($providerData['codes'] AS $i => $expectedCode)
		{
			if (XenForo_Application::hashEquals($expectedCode, $code))
			{
				$matched = $i;
				break;
			}
		}

		if ($matched === null)
		{
			return false;
		}

		$providerData['used'][] = $providerData['codes'][$matched];
		unset($providerData['codes'][$matched]);

		if (!$providerData['codes'])
		{
			// regenerate automatically
			$regenerated = true;
			$providerData = $this->generateInitialData($user, array());
		}
		else
		{
			$regenerated = false;
		}

		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

		$mail = XenForo_Mail::create('two_step_login_backup', array(
			'user' => $user,
			'ip' => $ip,
			'regenerated' => $regenerated
		), $user['language_id']);
		$mail->send($user['email'], $user['username']);

		return true;
	}

	public function canDisable()
	{
		return false;
	}

	public function canEnable()
	{
		return false;
	}

	public function canManage()
	{
		return true;
	}

	public function handleManage(XenForo_Controller $controller, array $user, array $providerData)
	{
		$input = $controller->getInput();

		if ($controller->isConfirmedPost())
		{
			if ($input->filterSingle('regen', XenForo_Input::BOOLEAN))
			{
				$newProviderData = $this->generateInitialData($user, array());

				/** @var XenForo_Model_Tfa $tfaModel */
				$tfaModel = XenForo_Model::create('XenForo_Model_Tfa');
				$tfaModel->enableUserTfaProvider($user['user_id'], $this->_providerId, $newProviderData);

				return $controller->responseRedirect(
					XenForo_ControllerResponse_Redirect::SUCCESS,
					XenForo_Link::buildPublicLink('account/two-step/manage', null,
						array('provider' => $this->_providerId)
					)
				);
			}
			else
			{
				return null;
			}
		}

		$viewParams = array(
			'provider' => $this,
			'providerId' => $this->_providerId,
			'user' => $user,
			'providerData' => $providerData,
			'usedCodes' => $this->_formatCodesForDisplay($providerData['used']),
			'availableCodes' => $this->_formatCodesForDisplay($providerData['codes'])
		);
		return $controller->responseView(
			'XenForo_ViewPublic_Account_Tfa_BackupManage', 'account_two_step_backup_manage', $viewParams
		);
	}

	protected function _formatCodesForDisplay(array $codes)
	{
		foreach ($codes AS &$code)
		{
			$code = implode(' ', str_split($code, 3));
		}

		return $codes;
	}
}