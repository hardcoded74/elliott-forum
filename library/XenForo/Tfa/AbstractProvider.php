<?php

abstract class XenForo_Tfa_AbstractProvider implements ArrayAccess
{
	protected $_providerId;

	abstract public function getTitle();
	abstract public function getDescription();
	abstract public function generateInitialData(array $user, array $setupData);
	abstract public function triggerVerification($context, array $user, $ip, array &$providerData);
	abstract public function renderVerification(XenForo_View $view, $context, array $user, array $providerData, array $triggerData);
	abstract public function verifyFromInput($context, XenForo_Input $input, array $user, array &$providerData);

	public function __construct($id)
	{
		$this->_providerId = $id;
	}

	public function requiresSetup()
	{
		return false;
	}

	public function renderSetup(XenForo_View $view, array $user)
	{
		return '';
	}

	public function verifySetupFromInput(XenForo_Input $input, array $user, &$error)
	{
		return array();
	}

	public function canDisable()
	{
		return true;
	}

	public function canEnable()
	{
		return true;
	}

	public function meetsRequirements(array $user, &$error)
	{
		return true;
	}

	public function canManage()
	{
		return false;
	}

	public function handleManage(XenForo_Controller $controller, array $user, array $providerData)
	{
		return null;
	}

	public function getProviderId()
	{
		return $this->_providerId;
	}

	public function offsetGet($key)
	{
		switch ($key)
		{
			case 'title': return $this->getTitle();
			case 'description': return $this->getDescription();
			case 'provider_id': return $this->_providerId;
			default: return null;
		}
	}

	public function offsetExists($key)
	{
		switch ($key)
		{
			case 'title':
			case 'description':
			case 'provider_id':
				return true;
			default:
				return false;
		}
	}

	public function offsetSet($key, $value)
	{
		throw new Exception("Cannot set");
	}

	public function offsetUnset($key)
	{
		throw new Exception("Cannot unset");
	}
}