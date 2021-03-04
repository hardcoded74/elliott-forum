<?php

class XenForo_ViewPublic_Tag_View extends XenForo_ViewPublic_Base
{
	public function renderHtml()
	{
		/** @var XenForo_TagHandler_Abstract[] $handlers */
		$handlers = $this->_params['results']['handlers'];
		$results = $this->_params['results']['results'];

		$output = array();
		foreach ($results AS $result)
		{
			$output[] = $handlers[$result[XenForo_Model_Tag::CONTENT_TYPE]]->renderResult(
				$this, $result['content']
			);
		}

		$this->_params['results'] = $output;
	}
}