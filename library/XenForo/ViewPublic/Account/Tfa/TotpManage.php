<?php

class XenForo_ViewPublic_Account_Tfa_TotpManage extends XenForo_ViewPublic_Base
{
	public function renderHtml()
	{
		/** @var XenForo_Tfa_AbstractProvider $provider */
		$provider = $this->_params['provider'];

		if ($this->_params['showSetup'])
		{
			$this->_params['newProviderHtml'] = $provider->renderVerification(
				$this, 'setup', $this->_params['user'], $this->_params['newProviderData'], $this->_params['newTriggerData']
			);
		}
		else
		{
			$this->_params['newProviderHtml'] = '';
		}
	}
}