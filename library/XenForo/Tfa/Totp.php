<?php

class XenForo_Tfa_Totp extends XenForo_Tfa_AbstractProvider
{
	protected $_auth;

	public function getTitle()
	{
		return new XenForo_Phrase('tfa_totp');
	}

	public function getDescription()
	{
		return new XenForo_Phrase('tfa_totp_desc');
	}

	public function generateInitialData(array $user, array $setupData)
	{
		return array(
			'secret' => $this->_getAuthHandler()->createSecret()
		);
	}

	public function triggerVerification($context, array $user, $ip, array &$providerData)
	{
		return array();
	}

	public function renderVerification(XenForo_View $view, $context, array $user, array $providerData, array $triggerData)
	{
		$issuer = XenForo_Helper_String::wholeWordTrim(
			str_replace(':', '', XenForo_Application::getOptions()->boardTitle),
			50
		);
		$user = str_replace(':', '', $user['username']);

		$params = array(
			'secret' => $providerData['secret'],
			'otpUrl' => $this->_getAuthHandler()->getOtpAuthUrl("$issuer: $user", $providerData['secret'], $issuer),
			'data' => $providerData,
			'context' => $context,
		);
		return $view->createTemplateObject('two_step_totp', $params)->render();
	}

	public function verifyFromInput($context, XenForo_Input $input, array $user, array &$providerData)
	{
		if (empty($providerData['secret']))
		{
			return false;
		}

		$code = $input->filterSingle('code', XenForo_Input::STRING);
		$code = preg_replace('/[^0-9]/', '', $code);
		if (!$code)
		{
			return false;
		}

		if (!empty($providerData['lastCode']) && $providerData['lastCode'] === $code)
		{
			// prevent a replay attack: once the code has been used, don't allow it to be used in the slice again
			if (!empty($providerData['lastCodeTime']) && time() - $providerData['lastCodeTime'] < 150)
			{
				return false;
			}
		}

		$auth = $this->_getAuthHandler();
		if (!$auth->verifyCode($providerData['secret'], $code, 2))
		{
			return false;
		}

		$providerData['lastCode'] = $code;
		$providerData['lastCodeTime'] = time();

		return true;
	}

	public function canManage()
	{
		return true;
	}

	public function handleManage(XenForo_Controller $controller, array $user, array $providerData)
	{
		$input = $controller->getInput();
		$request = $controller->getRequest();
		$session = XenForo_Application::getSession();

		$newProviderData = null;
		$newTriggerData = null;
		$showSetup = false;

		if ($controller->isConfirmedPost())
		{
			$sessionKey = 'tfaData_totp';

			if ($input->filterSingle('regen', XenForo_Input::BOOLEAN))
			{
				$newProviderData = $this->generateInitialData($user, array());
				$newTriggerData = $this->triggerVerification('setup', $user, $request->getClientIp(false), $newProviderData);

				$session->set($sessionKey, $newProviderData);
				$showSetup = true;
			}
			else if ($input->filterSingle('confirm', XenForo_Input::BOOLEAN))
			{
				$newProviderData = $session->get($sessionKey);
				if (!is_array($newProviderData))
				{
					return null;
				}

				if (!$this->verifyFromInput('setup', $input, $user, $newProviderData))
				{
					return $controller->responseError(new XenForo_Phrase('two_step_verification_value_could_not_be_confirmed'));
				}

				/** @var XenForo_Model_Tfa $tfaModel */
				$tfaModel = XenForo_Model::create('XenForo_Model_Tfa');
				$tfaModel->enableUserTfaProvider($user['user_id'], $this->_providerId, $newProviderData);

				$session->remove($sessionKey);

				return null;
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
			'newProviderData' => $newProviderData,
			'newTriggerData' => $newTriggerData,
			'showSetup' => $showSetup
		);
		return $controller->responseView(
			'XenForo_ViewPublic_Account_Tfa_TotpManage', 'account_two_step_totp_manage', $viewParams
		);
	}

	protected function _getAuthHandler()
	{
		if (!$this->_auth)
		{
			$this->_auth = new XenForo_Tfa_PHPGangsta_GoogleAuthenticator();
		}

		return $this->_auth;
	}
}