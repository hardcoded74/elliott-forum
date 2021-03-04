<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_ControllerAdmin_Tables extends XenForo_ControllerAdmin_Abstract
{
	public function actionIndex()
	{
		$viewParams['tables'] = $this->getModelFromCache('Siropu_UsernameChange_Model_Tables')->getAllTables();
		return $this->responseView(null, 'siropu_username_change_table_list', $viewParams);
	}
	public function actionAdd()
	{
		return $this->_getTabAddEditResponse();
	}
	public function actionEdit()
	{
		return $this->_getTabAddEditResponse();
	}
	public function actionSave()
	{
		$this->_assertPostOnly();

		$data = $this->_input->filter(array(
			'table_name'  => XenForo_Input::STRING,
			'column_name' => XenForo_Input::STRING,
			'description' => XenForo_Input::STRING
		));

		$error = array();

		if (!$this->_getModel()->checkIfTableExists($data))
		{
			$error[] = new XenForo_Phrase('siropu_username_change_table_not_found',
				array('name' => $data['table_name']));
		}
		else if (!$this->_getModel()->checkIfColumnExists($data))
		{
			$error[] = new XenForo_Phrase('siropu_username_change_column_not_found',
				array('name' => $data['column_name']));
		}
		if ($error)
		{
			return $this->responseError($error);
		}

		$dw = XenForo_DataWriter::create('Siropu_UsernameChange_DataWriter_Tables');
		if ($id = $this->_getID())
		{
			$dw->setExistingData($id);
		}
		$dw->bulkSet($data);
		$dw->save();

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('custom-tables') . $this->getLastHash($dw->get('table_id'))
		);
	}
	public function actionDelete()
	{
		if ($this->isConfirmedPost())
		{
			return $this->_deleteData(
				'Siropu_UsernameChange_DataWriter_Tables', 'table_id',
				XenForo_Link::buildAdminLink('custom-tables')
			);
		}
		else
		{
			$viewParams['table'] = $this->_getTableOrError();
			return $this->responseView(null, 'siropu_username_change_table_delete_confirm', $viewParams);
		}
	}
	protected function _getTabAddEditResponse()
	{
		$viewParams = array();

		if ($id = $this->_getID())
		{
			$viewParams['table'] = $this->_getTableOrError($id);
		}

		return $this->responseView(null, 'siropu_username_change_table_edit', $viewParams);
	}
	protected function _getTableOrError($id = null)
	{
		if ($id === null)
		{
			$id = $this->_getID();
		}

		if ($info = $this->_getModel()->getTableById($id))
		{
			return $info;
		}

		throw $this->responseException($this->responseError(new XenForo_Phrase('siropu_username_change_table_not_found'), 404));
	}
	protected function _getModel()
	{
		return $this->getModelFromCache('Siropu_UsernameChange_Model_Tables');
	}
	protected function _getHelper()
	{
		return $this->getHelper('Siropu_UsernameChange_Helper');
	}
	protected function _getID()
	{
		return $this->_input->filterSingle('table_id', XenForo_Input::UINT);
	}
}