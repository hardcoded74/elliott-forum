<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_ControllerAdmin_Change extends XenForo_ControllerAdmin_Abstract
{
	public function actionIndex()
	{
		return $this->responseView(null, 'siropu_username_change');
	}
	public function actionSave()
	{
		$this->_assertPostOnly();

		$data = $this->_input->filter(array(
			'username_old' => XenForo_Input::STRING,
			'username_new' => XenForo_Input::STRING,
			'incognito'    => XenForo_Input::UINT
		));

		$userModel = $this->getModelFromCache('XenForo_Model_User');

		if ($user = $userModel->getUserByName($data['username_old']))
		{
			$usernameNew = $data['username_new'];

			if ($this->_getHistoryModel()->usernameExistsInHistory($usernameNew, $user['user_id']))
			{
				return $this->responseError(new XenForo_Phrase('siropu_username_change_exists_in_history',
					array('username' => $usernameNew)));
			}

			$userModel->update($user, 'username', $usernameNew);
			$this->getHelper('Siropu_UsernameChange_Helper')->postAnnouncement($data, $user);

			$data['user_id'] = $user['user_id'];

			$dw = XenForo_DataWriter::create('Siropu_UsernameChange_DataWriter_History');
			$dw->bulkSet($data);
			$dw->save();

			if (XenForo_Application::get('options')->updateContentOnUsernameChange)
			{
				$userModel->changeContentUser($user['user_id'], $usernameNew, $user['username']);
				$this->getModelFromCache('Siropu_UsernameChange_Model_Tables')->changeUsernameInTables($data);
			}

			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildAdminLink('change-username')
			);
		}

		return $this->responseError(new XenForo_Phrase('siropu_username_change_user_not_found',
			array('name' => $data['username_old'])));
	}
	protected function _getHistoryModel()
	{
		return $this->getModelFromCache('Siropu_UsernameChange_Model_History');
	}
}