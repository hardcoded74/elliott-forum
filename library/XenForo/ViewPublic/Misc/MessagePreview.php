<?php

class XenForo_ViewPublic_Misc_MessagePreview extends XenForo_ViewPublic_Base
{
	public function renderHtml()
	{
		$bbCodeParser = XenForo_BbCode_Parser::create(XenForo_BbCode_Formatter_Base::create('Base', array('view' => $this)));
		$this->_params['conversation']['messageHtml'] = new XenForo_BbCode_TextWrapper($this->_params['conversation']['message_body'], $bbCodeParser);
	}
}