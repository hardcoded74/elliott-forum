<?php

class XenForo_ViewPublic_Account_TwoStepEnable extends XenForo_ViewPublic_Base
{
	public function renderHtml()
	{
		/** @var XenForo_Tfa_AbstractProvider $provider */
		$provider = $this->_params['provider'];

		$this->_params['providerHtml'] = $provider->renderVerification(
			$this, 'setup', $this->_params['user'], $this->_params['providerData'], $this->_params['triggerData']
		);
	}
}