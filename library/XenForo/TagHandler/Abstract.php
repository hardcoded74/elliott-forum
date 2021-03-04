<?php

abstract class XenForo_TagHandler_Abstract
{
	protected $_contentType;

	abstract public function getPermissionsFromContext(array $context, array $parentContext = null);
	abstract public function getBasicContent($id);
	abstract public function getContentDate(array $content);
	abstract public function getContentVisibility(array $content);
	abstract public function updateContentTagCache(array $content, array $cache);
	abstract public function getDataForResults(array $ids, array $viewingUser, array $resultsGrouped);
	abstract public function canViewResult(array $result, array $viewingUser);
	abstract public function prepareResult(array $result, array $viewingUser);
	abstract public function renderResult(XenForo_View $view, array $result);

	public function __construct($contentType)
	{
		$this->_contentType = $contentType;
	}

	public function getContentType()
	{
		return $this->_contentType;
	}
}