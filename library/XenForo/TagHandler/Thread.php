<?php

class XenForo_TagHandler_Thread extends XenForo_TagHandler_Abstract
{
	/**
	 * @var XenForo_Model_Thread
	 */
	protected $_threadModel = null;

	public function getPermissionsFromContext(array $context, array $parentContext = null)
	{
		if (isset($context['thread_id']))
		{
			$thread = $context;
			$forum = $parentContext;
		}
		else
		{
			$thread = null;
			$forum = $context;
		}

		if (!$forum || !isset($forum['node_id']))
		{
			throw new Exception("Context must be a thread and a forum or just a forum");
		}

		$visitor = XenForo_Visitor::getInstance();

		// creating a new thread (all tags would be by us)
		$nodePermissions = $visitor->getNodePermissions($forum['node_id']);

		if ($thread)
		{
			if ($thread['user_id'] == $visitor['user_id']
				&& XenForo_Permission::hasContentPermission($nodePermissions, 'manageOthersTagsOwnThread')
			)
			{
				$removeOthers = true;
			}
			else
			{
				$removeOthers = XenForo_Permission::hasContentPermission($nodePermissions, 'manageAnyTag');
			}
		}
		else
		{
			$removeOthers = false;
		}

		return array(
			'edit' => $this->_getThreadModel()->canEditTags($thread, $forum),
			'removeOthers' => $removeOthers,
			'minTotal' => $forum['min_tags']
		);
	}

	public function getBasicContent($id)
	{
		return $this->_getThreadModel()->getThreadById($id);
	}

	public function getContentDate(array $content)
	{
		return $content['post_date'];
	}

	public function getContentVisibility(array $content)
	{
		return $content['discussion_state'] == 'visible';
	}

	public function updateContentTagCache(array $content, array $cache)
	{
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_Discussion_Thread');
		$dw->setExistingData($content['thread_id']);
		$dw->set('tags', $cache);
		$dw->save();
	}

	public function getDataForResults(array $ids, array $viewingUser, array $resultsGrouped)
	{
		$threadModel = $this->_getThreadModel();

		$threads = $threadModel->getThreadsByIds($ids, array(
			'join' =>
				XenForo_Model_Thread::FETCH_FORUM |
				XenForo_Model_Thread::FETCH_USER |
				XenForo_Model_Thread::FETCH_FIRSTPOST,
			'permissionCombinationId' => $viewingUser['permission_combination_id'],
			'readUserId' => $viewingUser['user_id'],
			'includeForumReadDate' => true,
			'postCountUserId' => $viewingUser['user_id']
		));

		return $threadModel->unserializePermissionsInList($threads, 'node_permission_cache');
	}

	public function canViewResult(array $result, array $viewingUser)
	{
		return $this->_getThreadModel()->canViewThreadAndContainer(
			$result, $result, $null, $result['permissions'], $viewingUser
		);
	}

	public function prepareResult(array $result, array $viewingUser)
	{
		return $this->_getThreadModel()->prepareThread($result, $result, $result['permissions'], $viewingUser);
	}

	public function renderResult(XenForo_View $view, array $result)
	{
		return $view->createTemplateObject('search_result_thread', array(
			'thread' => $result,
			'forum' => array(
				'node_id' => $result['node_id'],
				'title' => $result['node_title'],
				'node_name' => $result['node_name']
			),
			'post' => $result
		));
	}

	/**
	 * @return XenForo_Model_Thread
	 */
	protected function _getThreadModel()
	{
		if (!$this->_threadModel)
		{
			$this->_threadModel = XenForo_Model::create('XenForo_Model_Thread');
		}

		return $this->_threadModel;
	}
}