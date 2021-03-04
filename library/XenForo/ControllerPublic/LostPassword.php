<?php

/**
 * Lost password handler.
 *
 * @package XenForo_UserConfirmation
 */
class XenForo_ControllerPublic_LostPassword extends XenForo_ControllerPublic_Abstract
{
	/**
	 * Displays a form to retrieve a lost password.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionIndex()
	{
		if (XenForo_Visitor::getUserId())
		{
			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL,
				XenForo_Link::buildPublicLink('index')
			);
		}

		$viewParams = array();

		if (XenForo_Application::get('options')->lostPasswordCaptcha)
		{
			$viewParams['captcha'] = XenForo_Captcha_Abstract::createDefault();
		}

		return $this->responseView('XenForo_ViewPublic_LostPassword_Form', 'lost_password', $viewParams);
	}

	/**
	 * Submits a lost password reset request.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionLost()
	{
		if (XenForo_Visitor::getUserId())
		{
			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL,
				XenForo_Link::buildPublicLink('index')
			);
		}

		$this->_assertPostOnly();

		$options = XenForo_Application::get('options');

		if ($options->lostPasswordCaptcha)
		{
			if (!XenForo_Captcha_Abstract::validateDefault($this->_input))
			{
				return $this->responseError(new XenForo_Phrase('did_not_complete_the_captcha_verification_properly'));
			}
		}

		$usernameOrEmail = $this->_input->filterSingle('username_email', XenForo_Input::STRING);
		$user = $this->_getUserModel()->getUserByNameOrEmail($usernameOrEmail);
		if (!$user)
		{
			return $this->responseError(new XenForo_Phrase('requested_member_not_found'));
		}

		$confirmationModel = $this->_getUserConfirmationModel();

		if ($options->lostPasswordTimeLimit)
		{
			if ($confirmation = $confirmationModel->getUserConfirmationRecord($user['user_id'], 'password'))
			{
				$timeDiff = XenForo_Application::$time - $confirmation['confirmation_date'];

				if ($options->lostPasswordTimeLimit > $timeDiff)
				{
					return $this->responseFlooding($options->lostPasswordTimeLimit - $timeDiff);
				}
			}
		}

		$confirmationModel->sendPasswordResetRequest($user);

		return $this->responseMessage(new XenForo_Phrase('password_reset_request_has_been_emailed_to_you'));
	}

	/**
	 * Confirms a lost password reset request and resets the password.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionConfirm()
	{
		$userId = $this->_input->filterSingle('user_id', XenForo_Input::UINT);
		if (!$userId)
		{
			return $this->responseError(new XenForo_Phrase('no_account_specified'));
		}

		$user = $this->_getUserModel()->getFullUserById($userId);
		if (!$user)
		{
			return $this->responseError(new XenForo_Phrase('your_password_could_not_be_reset'));
		}

		$confirmationModel = $this->_getUserConfirmationModel();

		$confirmation = $confirmationModel->getUserConfirmationRecord($userId, 'password');
		if (!$confirmation)
		{
			if (XenForo_Visitor::getUserId())
			{
				// probably already been reset
				return $this->responseRedirect(
					XenForo_ControllerResponse_Redirect::RESOURCE_CANONICAL,
					XenForo_Link::buildPublicLink('index')
				);
			}
			else
			{
				return $this->responseError(new XenForo_Phrase('your_password_could_not_be_reset'));
			}
		}

		$confirmationKey = $this->_input->filterSingle('c', XenForo_Input::STRING);
		if (!$confirmationModel->validateUserConfirmationRecord($confirmationKey, $confirmation))
		{
			return $this->responseError(new XenForo_Phrase('your_password_could_not_be_reset'));
		}

		if ($this->_request->isPost())
		{
			$input = $this->_input->filter(array(
				'password' => XenForo_Input::STRING,
				'password_confirm' => XenForo_Input::STRING
			));

			$writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
			$writer->setExistingData($userId);
			$writer->setPassword($input['password'], $input['password_confirm'], null, true);

			switch ($writer->get('user_state'))
			{
				case 'email_confirm':
					$writer->advanceRegistrationUserState();
					break;

				case 'email_confirm_edit':
				case 'email_bounce':
					$writer->set('user_state', 'valid');
					break;
			}

			$writer->save();

			$visitor = XenForo_Visitor::getInstance();
			$mail = XenForo_Mail::create('password_changed', array(
				'user' => $visitor,
				'ip' => $this->_request->getClientIp(false)
			), $visitor['language_id']);
			$mail->send($visitor['email'], $visitor['username']);

			$confirmationModel->deleteUserConfirmationRecord($userId, 'password');

			$session = XenForo_Application::getSession();

			$visitorUserId = XenForo_Visitor::getUserId();
			if ($visitorUserId == $user['user_id'])
			{
				// don't need to do anything -- this can come up when setting a password for a user from external auth
				$session->set('password_date', $writer->get('password_date'));
			}
			else
			{
				/** @var XenForo_ControllerHelper_Login $loginHelper */
				$loginHelper = $this->getHelper('Login');
				if ($loginHelper->userTfaConfirmationRequired($user))
				{
					// need to do TFA confirmation before we can log them in
					$loginHelper->setTfaSessionCheck($user['user_id']);

					return $this->responseRedirect(
						XenForo_ControllerResponse_Redirect::SUCCESS,
						XenForo_Link::buildPublicLink('login/two-step', null, array(
							'redirect' => XenForo_Link::buildPublicLink('index'),
							'remember' => 0
						))
					);
				}
				else
				{
					$session->userLogin($user['user_id'], $writer->get('password_date'));
				}
			}

			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildPublicLink('index')
			);
		}
		else
		{
			$viewParams = array(
				'user' => $user,
				'confirmation' => $confirmation,
				'confirmationKey' => $confirmationKey
			);
			return $this->responseView('XenForo_ViewPublic_LostPassword_Confirm', 'lost_password_confirm', $viewParams);
		}
	}

	protected function _assertViewingPermissions($action) {}
	protected function _assertBoardActive($action) {}
	protected function _assertCorrectVersion($action) {}
	protected function _assertTfaRequirement($action) {}
	public function updateSessionActivity($controllerResponse, $controllerName, $action) {}

	/**
	 * @return XenForo_Model_UserConfirmation
	 */
	protected function _getUserConfirmationModel()
	{
		return $this->getModelFromCache('XenForo_Model_UserConfirmation');
	}

	/**
	 * @return XenForo_Model_Login
	 */
	protected function _getLoginModel()
	{
		return $this->getModelFromCache('XenForo_Model_Login');
	}

	/**
	 * @return XenForo_Model_User
	 */
	protected function _getUserModel()
	{
		return $this->getModelFromCache('XenForo_Model_User');
	}
}