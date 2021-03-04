<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_ControllerPublic_Account extends XFCP_Siropu_UsernameChange_ControllerPublic_Account
{
	public function actionChangeUsername()
    {
		$data = $this->_input->filter(array(
			'username_new' => XenForo_Input::STRING,
			'incognito'    => XenForo_Input::UINT
		));

		$action       = $this->_input->filterSingle('action', XenForo_Input::STRING);
		$visitor      = XenForo_Visitor::getInstance();
		$userId       = $visitor->user_id;
		$username     = $data['username_new'];
		$incognito    = $this->_getHelper()->userHasPermission('incognito');

		$changeLimit  = $this->_getHelper()->userHasPermission('changeLimit');
		$changeLimit  = ($changeLimit > 0) ? $changeLimit : false;
		$changeCount  = $changeLimit ? $this->_getHistoryModel()->getUserChangeCount($userId) : false;
		$timeFrame    = $this->_getHelper()->userHasPermission('timeFrame');
		$timeFrame    = ($timeFrame > 0) ? $timeFrame : false;
		$timeToWait   = $timeFrame ? $this->_getHistoryModel()->getUserLastUsernameChange($userId, $timeFrame) : false;
		$limitReached = $changeLimit && $changeCount >= $changeLimit;

		if ($this->isConfirmedPost() || $action == 'confirm')
		{
			$errors = array();

			if (!$this->_getHelper()->userHasPermission('change'))
			{
				$errors[] = new XenForo_Phrase('siropu_username_change_no_permissions');
			}
			else if ($limitReached)
			{
				$errors[] = new XenForo_Phrase('siropu_username_change_limit_reached', array('limit' => $changeLimit));
			}
			else if ($timeFrame && $this->_getHistoryModel()->getUserChangeCount($userId, $timeFrame))
			{
				$errors[] = new XenForo_Phrase('siropu_username_change_time_to_wait', array('days' => $timeToWait));
			}
			else if ($this->_getHistoryModel()->usernameExistsInHistory($username, $userId))
			{
				$errors[] = new XenForo_Phrase('siropu_username_change_exists_in_history', array('username' => $username));
			}
			else if ($username == $visitor->username)
			{
				$errors[] = new XenForo_Phrase('siropu_username_change_cannot_be_identical');
			}
			else if (strtolower($username) != strtolower($visitor->username))
			{
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_User');
				$dw->set('username', $username);

				if ($dw->getErrors())
				{
					$errors = $dw->getErrors();
				}
			}

			if ($errors)
			{
				return $this->responseError($errors);
			}
		}

		if ($action == 'confirm')
		{
			$viewParams = array_merge($data, array(
				'timeFrame' => $timeFrame
			));

			return $this->_getWrapper(
				'account', 'change-username',
				$this->responseView(
					null,
					'siropu_username_change_confirm',
					$viewParams
				)
			);
		}

		if ($this->isConfirmedPost())
		{
			$data['username_old'] = $visitor->username;

			if ($data['incognito'] && !$incognito)
			{
				$data['incognito'] = 0;
			}

			$this->_getUserModel()->update($this->_getUserModel()->getUserById($userId), 'username', $username);
			$this->_getHelper()->postAnnouncement($data, $visitor);

			$data['user_id'] = $userId;

			$dw = XenForo_DataWriter::create('Siropu_UsernameChange_DataWriter_History');
			$dw->bulkSet($data);
			$dw->save();

			if (XenForo_Application::get('options')->updateContentOnUsernameChange)
			{
				$this->_getUserModel()->changeContentUser($userId, $username, $visitor->username);
				$this->getModelFromCache('Siropu_UsernameChange_Model_Tables')->changeUsernameInTables($data);
			}

			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildPublicLink('account/change-username')
			);
		}

		$remainingChanges = $changeLimit ? $changeLimit - $changeCount : 0;

		$viewParams = array(
			'incognito'    => $incognito,
			'timeFrame'    => $timeFrame,
			'timeToWait'   => $timeToWait,
			'limitReached' => $limitReached,
			'changeLimit'  => $changeLimit,
			'changesLeft'  => $remainingChanges >= 0 ? $remainingChanges : 0
		);

		return $this->_getWrapper(
			'account', 'change-username',
			$this->responseView(
				null,
				'siropu_username_change',
				$viewParams
			)
		);
	}
	protected function _getHistoryModel()
	{
		return $this->getModelFromCache('Siropu_UsernameChange_Model_History');
	}
	protected function _getUserModel()
	{
		return $this->getModelFromCache('XenForo_Model_User');
	}
	protected function _getHelper()
	{
		return $this->getHelper('Siropu_UsernameChange_Helper');
	}
}