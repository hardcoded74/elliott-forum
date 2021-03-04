<?php

class XenForo_SitemapHandler_Tag extends XenForo_SitemapHandler_Abstract
{
	protected $_tagModel;

	public function getRecords($previousLast, $limit, array $viewingUser)
	{
		return $this->_getTagModel()->getTagsInRange($previousLast, $limit);
	}

	public function isIncluded(array $entry, array $viewingUser)
	{
		return ($entry['use_count'] > 0);
	}

	public function getData(array $entry)
	{
		return array(
			'loc' => XenForo_Link::buildPublicLink('canonical:tags', $entry),
			'lastmod' => $entry['last_use_date']
		);
	}

	public function isInterruptable()
	{
		return true;
	}

	/**
	 * @return XenForo_Model_Tag
	 */
	protected function _getTagModel()
	{
		if (!$this->_tagModel)
		{
			$this->_tagModel = XenForo_Model::create('XenForo_Model_Tag');
		}

		return $this->_tagModel;
	}
}