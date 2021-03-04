<?php

class bdApi_ControllerAdmin_Client extends XenForo_ControllerAdmin_Abstract
{
	public function actionIndex()
	{
		$clientModel = $this->_getClientModel();
		$clients = $clientModel->getClients();
		
		$viewParams = array(
			'clients' => $clients
		);
		
		return $this->responseView('bdApi_ViewAdmin_Client_List', 'bdapi_client_list', $viewParams);
	}
	
	public function actionAdd()
	{
		$viewParams = array(
			'client' => array(),
		);
		
		return $this->responseView('bdApi_ViewAdmin_Client_Edit', 'bdapi_client_edit', $viewParams);
	}
	
	public function actionEdit()
	{
		$id = $this->_input->filterSingle('client_id', XenForo_Input::STRING);
		$client = $this->_getClientOrError($id);
		
		$viewParams = array(
			'client' => $client,
		);
		
		return $this->responseView('bdApi_ViewAdmin_Client_Edit', 'bdapi_client_edit', $viewParams);
	}
	
	public function actionSave()
	{
		$this->_assertPostOnly();
		
		$id = $this->_input->filterSingle('existing_client_id', XenForo_Input::STRING);

		$dwInput = $this->_input->filter(array(
			'name' => XenForo_Input::STRING,
			'description' => XenForo_Input::STRING,	
			'client_id' => XenForo_Input::STRING,
			'client_secret' => XenForo_Input::STRING,
			'redirect_uri' => XenForo_Input::STRING,
			'options' => XenForo_Input::ARRAY_SIMPLE
		));
		
		$dw = $this->_getClientDataWriter();
		if (!empty($id))
		{
			$dw->setExistingData($id);
		}
		$dw->bulkSet($dwInput);
		if (!$dw->get('user_id'))
		{
			$dw->set('user_id', XenForo_Visitor::getUserId());
		}
		
		$dw->save();

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('api-clients')
		);
	}
	
	public function actionDelete()
	{
		$id = $this->_input->filterSingle('client_id', XenForo_Input::STRING);
		$client = $this->_getClientOrError($id);
		
		if ($this->isConfirmedPost())
		{
			$dw = $this->_getClientDataWriter();
			$dw->setExistingData($client, true);
			$dw->delete();

			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildAdminLink('api-clients')
			);
		}
		else
		{
			$viewParams = array(
				'client' => $client
			);

			return $this->responseView('bdApi_ViewAdmin_Client_Delete', 'bdapi_client_delete', $viewParams);
		}
	}
	
	protected function _getClientOrError($id, array $fetchOptions = array())
	{
		$info = $this->_getClientModel()->getClientById($id, $fetchOptions);
		
		if (empty($info))
		{
			throw $this->responseException($this->responseError(new XenForo_Phrase('bdapi_client_not_found'), 404));
		}
		
		return $info;
	}
	
	/**
	 * @return bdApi_Model_Client
	 */
	protected function _getClientModel()
	{
		return $this->getModelFromCache('bdApi_Model_Client');
	}
	
	/**
	 * @return bdApi_DataWriter_Client
	 */
	protected function _getClientDataWriter()
	{
		return XenForo_DataWriter::create('bdApi_DataWriter_Client');
	}
}