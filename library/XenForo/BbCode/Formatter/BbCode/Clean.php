<?php

class XenForo_BbCode_Formatter_BbCode_Clean extends XenForo_BbCode_Formatter_BbCode_Abstract
{
	/**
	 * Callback for all tags.
	 *
	 * @var callback
	 */
	protected $_generalTagCallback = array('$this', 'autoLinkTag');

	/**
	 * Callback that all tags with go through. Changes the rendering state to disable
	 * URL parsing if necessary.
	 *
	 * @param array $tag
	 * @param array $rendererStates

	 * @return string
	 */
	public function autoLinkTag(array $tag, array $rendererStates)
	{
		$text = $this->renderSubTree($tag['children'], $rendererStates);

		if (!empty($tag['original']) && is_array($tag['original']))
		{
			list($prepend, $append) = $tag['original'];
		}
		else
		{
			$prepend = '';
			$append = '';
		}

		// note: necessary to return prepend/append unfiltered to keep them unchanged
		return $prepend . $text . $append;
	}
}