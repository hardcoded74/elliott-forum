<?php

class bdApi_Route_PrefixApi_Index extends bdApi_Route_PrefixApi_Abstract
{
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		return $router->getRouteMatch('bdApi_ControllerApi_Index', $routePath);
	}
}