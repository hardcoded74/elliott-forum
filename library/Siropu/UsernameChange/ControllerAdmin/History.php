<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_ControllerAdmin_History extends XenForo_ControllerAdmin_Abstract
{
	public function actionIndex()
	{
		$data = $this->_input->filter(array(
			'search' => XenForo_Input::STRING,
			'page'   => XenForo_Input::UINT,
		));

		$viewParams = array(
			'historyList' => $this->_getHistoryModel()->getAllHistory($data['search'], array('page' => $data['page'], 'perPage' => 25)),
			'total'       => $this->_getHistoryModel()->getAllHistoryCount($data['search']),
			'page'        => $data['page'],
			'perPage'     => 25,
			'linkParams'  => array(),
			'search'      => $data['search']
		);

		return $this->responseView(null, 'siropu_username_change_history_list', $viewParams);
	}
	public function actionEdit()
	{
		$viewParams['history'] = $this->_getHistoryOrError();
		return $this->responseView(null, 'siropu_username_change_history_edit', $viewParams);
	}
	public function actionSave()
	{
		$this->_assertPostOnly();

		$data = $this->_input->filter(array(
			'user_id'      => XenForo_Input::UINT,
			'username_old' => XenForo_Input::STRING,
			'username_new' => XenForo_Input::STRING,
			'incognito'    => XenForo_Input::UINT
		));

		$dw = XenForo_DataWriter::create('Siropu_UsernameChange_DataWriter_History');
		$dw->setExistingData($this->_getID());
		$dw->bulkSet($data);
		$dw->save();

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('username-history')
		);
	}
	public function actionDelete()
	{
		if ($this->isConfirmedPost())
		{
			return $this->_deleteData(
				'Siropu_UsernameChange_DataWriter_History', 'history_id',
				XenForo_Link::buildAdminLink('username-history')
			);
		}
		else
		{
			$viewParams['history'] = $this->_getHistoryOrError();
			return $this->responseView(null, 'siropu_username_change_history_delete_confirm', $viewParams);
		}
	}
	protected function _getHistoryOrError($id = null)
	{
		if ($id === null)
		{
			$id = $this->_getID();
		}

		if ($info = $this->_getHistoryModel()->getHistoryById($id))
		{
			return $info;
		}

		throw $this->responseException($this->responseError(new XenForo_Phrase('siropu_username_change_history_not_dound'), 404));
	}
	private function _getHistoryModel()
	{
		return $this->getModelFromCache('Siropu_UsernameChange_Model_History');
	}
	protected function _getID()
	{
		return $this->_input->filterSingle('history_id', XenForo_Input::UINT);
	}
}