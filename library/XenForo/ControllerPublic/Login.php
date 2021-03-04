<?php

class XenForo_ControllerPublic_Login extends XenForo_ControllerPublic_Abstract
{
	public function actionIndex()
	{
		$redirect = $this->getDynamicRedirectIfNot(XenForo_Link::buildPublicLink('login'));
		$redirectAlt = $this->getDynamicRedirectIfNot(XenForo_Link::buildPublicLink('register'));
		if ($redirect != $redirectAlt)
		{
			// matched one of the two, just go to the index
			$redirect = XenForo_Link::buildPublicLink('index');
		}

		if (XenForo_Visitor::getUserId())
		{
			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				$redirect
			);
		}

		$viewParams = array(
			'redirect' => $redirect
		);

		return $this->responseView(
			'XenForo_ViewPublic_Login_Login',
			'login',
			$viewParams,
			$this->_getRegistrationContainerParams()
		);
	}

	public function actionLogin()
	{
		$data = $this->_input->filter(array(
			'login' => XenForo_Input::STRING,
			'password' => XenForo_Input::STRING,
			'remember' => XenForo_Input::UINT,
			'register' => XenForo_Input::UINT,
			'cookie_check' => XenForo_Input::UINT,
			'postData' => XenForo_Input::JSON_ARRAY
		));

		if ($data['register'] || $data['password'] === '')
		{
			return $this->responseReroute('XenForo_ControllerPublic_Register', 'index');
		}

		$redirect = $this->getDynamicRedirectIfNot(XenForo_Link::buildPublicLink('login'));

		if (XenForo_Visitor::getUserId())
		{
			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				$redirect
			);
		}

		$this->_assertPostOnly();

		$loginModel = $this->_getLoginModel();

		if ($data['cookie_check'] && count($_COOKIE) == 0)
		{
			// login came from a page, so we should at least have a session cookie.
			// if we don't, assume that cookies are disabled
			return $this->_loginErrorResponse(
				new XenForo_Phrase('cookies_required_to_log_in_to_site'),
				$data['login'],
				true,
				$redirect
			);
		}

		$needCaptcha = $loginModel->requireLoginCaptcha($data['login']);
		if ($needCaptcha)
		{
			switch (XenForo_Application::getOptions()->loginLimit)
			{
				case 'captcha':
					if (!XenForo_Captcha_Abstract::validateDefault($this->_input, true))
					{
						return $this->_loginErrorResponse(
							new XenForo_Phrase('did_not_complete_the_captcha_verification_properly'),
							$data['login'],
							true,
							$redirect,
							$data['postData']
						);
					}
					break;

				case 'block':
					return $this->_loginErrorResponse(
						new XenForo_Phrase('your_account_has_temporarily_been_locked_due_to_failed_login_attempts'),
						$data['login'],
						true,
						$redirect,
						$data['postData']
					);
					break;
			}
		}

		$userModel = $this->_getUserModel();

		$userId = $userModel->validateAuthentication($data['login'], $data['password'], $error);
		if (!$userId)
		{
			$loginModel->logLoginAttempt($data['login']);

			return $this->_loginErrorResponse(
				$error,
				$data['login'],
				($needCaptcha || $loginModel->requireLoginCaptcha($data['login'])),
				$redirect,
				$data['postData']
			);
		}

		$loginModel->clearLoginAttempts($data['login']);

		$user = $this->_getUserModel()->getFullUserById($userId);

		/** @var XenForo_ControllerHelper_Login $loginHelper */
		$loginHelper = $this->getHelper('Login');

		if ($loginHelper->userTfaConfirmationRequired($user))
		{
			$loginHelper->setTfaSessionCheck($user['user_id']);

			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildPublicLink('login/two-step', null, array(
					'redirect' => $redirect,
					'remember' => $data['remember'] ? 1 : 0
				))
			);
		}
		else
		{
			return $this->completeLogin($userId, $data['remember'], $redirect, $data['postData']);
		}
	}

	public function actionTwoStep()
	{
		$data = $this->_input->filter(array(
			'remember' => XenForo_Input::UINT
		));

		$redirect = $this->getDynamicRedirectIfNot(XenForo_Link::buildPublicLink('login'));

		/** @var XenForo_ControllerHelper_Login $loginHelper */
		$loginHelper = $this->getHelper('Login');

		$user = $loginHelper->getUserForTfaCheck();
		if (!$user)
		{
			$loginHelper->clearTfaSessionCheck();
			return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS, $redirect);
		}

		/** @var XenForo_Model_Tfa $tfaModel */
		$tfaModel = $this->getModelFromCache('XenForo_Model_Tfa');
		$providers = $tfaModel->getTfaConfigurationForUser($user['user_id'], $userData);

		if (!$providers)
		{
			$loginHelper->clearTfaSessionCheck();
			return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS, $redirect);
		}

		$providerId = $this->_input->filterSingle('provider', XenForo_Input::STRING);

		if ($this->isConfirmedPost())
		{
			$loginHelper->assertNotTfaAttemptLimited($user['user_id']);

			$validationResult = $loginHelper->runTfaValidation($user, $providerId, $providers, $userData);
			if ($validationResult === true)
			{
				if ($this->_input->filterSingle('trust', XenForo_Input::BOOLEAN))
				{
					$loginHelper->setDeviceTrusted($user['user_id']);
				}

				return $this->completeLogin($user['user_id'], $data['remember'], $redirect);
			}
			else if ($validationResult === false)
			{
				return $this->responseError(new XenForo_Phrase('two_step_verification_value_could_not_be_confirmed'));
			}
		}

		$viewParams = $loginHelper->triggerTfaCheck($user, $providerId, $providers, $userData);
		$viewParams['remember'] = $data['remember'];
		$viewParams['trustChecked'] = ($data['remember'] || XenForo_Helper_Cookie::getCookie('user'));
		$viewParams['redirect'] = $redirect;

		return $this->responseView('XenForo_ViewPublic_Login_TwoStep', 'login_two_step', $viewParams);
	}

	public function completeLogin($userId, $remember, $redirect, array $postData = array())
	{
		$userModel = $this->_getUserModel();

		if ($remember)
		{
			$userModel->setUserRememberCookie($userId);
		}

		XenForo_Model_Ip::log($userId, 'user', $userId, 'login');

		$userModel->deleteSessionActivity(0, $this->_request->getClientIp(false));

		$visitor = XenForo_Visitor::setup($userId);

		XenForo_Application::getSession()->userLogin($userId, $visitor['password_date']);

		if ($postData)
		{
			return $this->responseView('XenForo_ViewPublic_Login_PostRedirect', 'login_post_redirect', array(
				'postData' => $postData,
				'redirect' => $redirect
			));
		}
		else
		{
			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				$redirect,
				''
			);
		}
	}

	protected function _loginErrorResponse($error, $defaultLogin, $needCaptcha, $redirect = false, array $postData = null)
	{
		if ($needCaptcha && XenForo_Application::getOptions()->loginLimit == 'captcha')
		{
			$captcha = XenForo_Captcha_Abstract::createDefault(true);
		}
		else
		{
			$captcha = false;
		}

		return $this->responseView('XenForo_ViewPublic_Login', 'error_with_login', array(
			'text' => $error,
			'defaultLogin' => $defaultLogin,
			'captcha' => $captcha,
			'redirect' => $redirect,
			'postData' => $postData
		));
	}

	public function actionPasswordConfirm()
	{
		$redirect = $this->getDynamicRedirectIfNot(XenForo_Link::buildPublicLink('login/password-confirm'));

		$visitor = XenForo_Visitor::getInstance();
		$userId = $visitor->user_id;

		if (!$userId)
		{
			return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS, $redirect, '');
		}

		$auth = $this->_getUserModel()->getUserAuthenticationObjectByUserId($userId);
		if (!$auth || !$auth->hasPassword())
		{
			return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS, $redirect, '');
		}

		$this->_assertPostOnly();

		$password = $this->_input->filterSingle('password', XenForo_Input::STRING);

		$loginModel = $this->_getLoginModel();

		$needCaptcha = $loginModel->requireLoginCaptcha($visitor->username);
		if ($needCaptcha)
		{
			return $this->responseError(
				new XenForo_Phrase('your_account_has_temporarily_been_locked_due_to_failed_login_attempts')
			);
		}

		if (!$auth->authenticate($userId, $password))
		{
			$loginModel->logLoginAttempt($visitor->username);

			return $this->responseError(new XenForo_Phrase('incorrect_password'));
		}

		$loginModel->clearLoginAttempts($visitor->username);

		XenForo_Application::getSession()->set('passwordConfirm', XenForo_Application::$time);

		return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS, $redirect, '');
	}

	/**
	 * Gets an updated CSRF token for pages that are left open for a long time.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionCsrfTokenRefresh()
	{
		$this->_assertPostOnly();

		$visitor = XenForo_Visitor::getInstance();
		$viewParams = array(
			'csrfToken' => $visitor['csrf_token_page'],
			'sessionId' => XenForo_Application::get('session')->getSessionId()
		);

		return $this->responseView('XenForo_ViewPublic_Login_CsrfTokenRefresh', '', $viewParams);
	}

	protected function _checkCsrf($action)
	{
		if (strtolower($action) == 'login')
		{
			return;
		}

		return parent::_checkCsrf($action);
	}

	protected function _assertViewingPermissions($action) {}
	protected function _assertCorrectVersion($action) {}
	protected function _assertBoardActive($action) {}
	protected function _assertTfaRequirement($action) {}
	public function updateSessionActivity($controllerResponse, $controllerName, $action) {}

	/**
	 * @return XenForo_Model_User
	 */
	protected function _getUserModel()
	{
		return $this->getModelFromCache('XenForo_Model_User');
	}

	/**
	 * @return XenForo_Model_Login
	 */
	protected function _getLoginModel()
	{
		return $this->getModelFromCache('XenForo_Model_Login');
	}
}
