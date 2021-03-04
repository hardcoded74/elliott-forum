<?php
class bdApi_XenForo_ControllerPublic_Register_Base extends XFCP_bdApi_Xenforo_ControllerPublic_Register
{
	private $_authorizePending = false;

	protected function _preDispatch($action)
	{
		$session = XenForo_Application::getSession();
		$this->_authorizePending = $session->get('bdApi_authorizePending');

		return parent::_preDispatch($action);
	}

	protected function _bdApi_responseView($viewName, $templateName, array $params, array $containerParams)
	{
		if ($viewName == 'XenForo_ViewPublic_Register_Process'
		AND $templateName == 'register_process')
		{
			// this means registration succeeded
			// built-in or Facebook (or even our own [bd] API Consumer)
			if (!empty($this->_authorizePending))
			{
				// but we have an authorize request pending...
				// redirect to that instead
				// TODO: drop the usage of Xenforo_Controller::responseRedirect? A bit dangerous
				return $this->responseRedirect(
					XenForo_ControllerResponse_Redirect::RESOURCE_CREATED,
					$this->_authorizePending
				);
			}
		}

		return parent::responseView($viewName, $templateName, $params, $containerParams);
	}
}

if (XenForo_Application::$versionId > 1020000)
{
	class bdApi_XenForo_ControllerPublic_Register extends bdApi_XenForo_ControllerPublic_Register_Base
	{
		public function responseView($viewName = '', $templateName = '', array $params = array(), array $containerParams = array())
		{
			return $this->_bdApi_responseView($viewName, $templateName, $params, $containerParams);
		}
	}
}
else
{
	class bdApi_XenForo_ControllerPublic_Register extends bdApi_XenForo_ControllerPublic_Register_Base
	{
		public function responseView($viewName, $templateName = 'DEFAULT', array $params = array(), array $containerParams = array())
		{
			return $this->_bdApi_responseView($viewName, $templateName, $params, $containerParams);
		}
	}
}
