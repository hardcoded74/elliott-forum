<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_ControllerPublic_Register extends XFCP_Siropu_UsernameChange_ControllerPublic_Register
{
	public function actionIndex()
    {
		if (($login = $this->_input->filterSingle('login', XenForo_Input::STRING))
			&& !XenForo_Helper_Email::isEmailValid($login)
			&& ($response = $this->_historyCheck($login, array('username' => $login))))
		{
			return $response;
		}

		return parent::actionIndex();
	}
	public function actionRegister()
    {
		$inputData = $this->_getRegistrationInputDataSafe();

		if ($response = $this->_historyCheck($inputData['data']['username'], $inputData))
		{
			return $response;
		}

		return parent::actionRegister();
	}
	protected function _getHistoryModel()
	{
		return $this->getModelFromCache('Siropu_UsernameChange_Model_History');
	}
	protected function _historyCheck($username, $fields)
	{
		if ($this->_getHistoryModel()->usernameExistsInHistory($username))
		{
			$errors[] = new XenForo_Phrase('siropu_username_change_exists_in_history', array('username' => $username));
			return $this->_getRegisterFormResponse($fields, $errors);
		}
	}
}