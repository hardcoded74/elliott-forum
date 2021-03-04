<?php

class XenForo_ViewAdmin_Login_TwoStep extends XenForo_ViewAdmin_Base
{
	public function renderHtml()
	{
		/** @var XenForo_Tfa_AbstractProvider $provider */
		$provider = $this->_params['provider'];

		$this->_params['providerHtml'] = $provider->renderVerification(
			$this, 'login', $this->_params['user'], $this->_params['providerData'], $this->_params['triggerData']
		);
	}
}