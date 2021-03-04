<?php

/**
 * Handles searching of profile post comments.
 *
 * @package XenForo_Search
 */
class XenForo_Search_DataHandler_ProfilePostComment extends XenForo_Search_DataHandler_Abstract
{
	/**
	 * @var XenForo_Model_ProfilePost
	 */
	protected $_profilePostModel = null;

	/**
	 * @var XenForo_Model_User
	 */
	protected $_userModel = null;

	/**
	 * Inserts into (or replaces a record) in the index.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::_insertIntoIndex()
	 */
	protected function _insertIntoIndex(XenForo_Search_Indexer $indexer, array $data, array $parentData = null)
	{
		if ($data['message_state'] != 'visible')
		{
			return;
		}

		$profilePost = $this->_getProfilePostModel()->getProfilePostById($data['profile_post_id']);
		if ($profilePost['message_state'] != 'visible')
		{
			return;
		}

		$metadata = array();
		$metadata['profile_user'] = $profilePost['user_id'];

		$indexer->insertIntoIndex(
			'profile_post_comment', $data['profile_post_comment_id'],
			'', $data['message'], $data['comment_date'],
			$data['user_id'], $profilePost['profile_post_id'], $metadata
		);
	}

	/**
	 * Updates a record in the index.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::_updateIndex()
	 */
	protected function _updateIndex(XenForo_Search_Indexer $indexer, array $data, array $fieldUpdates)
	{
		$indexer->updateIndex('profile_post_comment', $data['profile_post_comment_id'], $fieldUpdates);
	}

	/**
	 * Deletes one or more records from the index.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::_deleteFromIndex()
	 */
	protected function _deleteFromIndex(XenForo_Search_Indexer $indexer, array $dataList)
	{
		$commentIds = array();
		foreach ($dataList AS $data)
		{
			$commentIds[] = is_array($data) ? $data['profile_post_comment_id'] : $data;
		}

		$indexer->deleteFromIndex('profile_post_comment', $commentIds);
	}

	/**
	 * Rebuilds the index for a batch.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::rebuildIndex()
	 */
	public function rebuildIndex(XenForo_Search_Indexer $indexer, $lastId, $batchSize)
	{
		$profilePostIds = $this->_getProfilePostModel()->getProfilePostCommentIdsInRange($lastId, $batchSize);
		if (!$profilePostIds)
		{
			return false;
		}

		$this->quickIndex($indexer, $profilePostIds);

		return max($profilePostIds);
	}

	/**
	 * Rebuilds the index for the specified content.

	 * @see XenForo_Search_DataHandler_Abstract::quickIndex()
	 */
	public function quickIndex(XenForo_Search_Indexer $indexer, array $contentIds)
	{
		$profilePostModel = $this->_getProfilePostModel();

		$profilePosts = $profilePostModel->getProfilePostCommentsByIds($contentIds);

		foreach ($profilePosts AS $profilePost)
		{
			$this->insertIntoIndex($indexer, $profilePost);
		}

		return true;
	}

	/**
	 * Gets the type-specific data for a collection of results of this content type.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::getDataForResults()
	 */
	public function getDataForResults(array $ids, array $viewingUser, array $resultsGrouped)
	{
		$profilePostModel = $this->_getProfilePostModel();

		$comments = $profilePostModel->getProfilePostCommentsByIds($ids, array(
			'join' => XenForo_Model_ProfilePost::FETCH_COMMENT_USER
		));

		$profilePostIds = array();
		foreach ($comments AS $comment)
		{
			$profilePostIds[] = $comment['profile_post_id'];
		}
		$profilePosts = $profilePostModel->getProfilePostsByIds($profilePostIds, array(
			'join' => XenForo_Model_ProfilePost::FETCH_USER_RECEIVER
		));

		$userIds = array();
		foreach ($profilePosts AS $profilePost)
		{
			$userIds[$profilePost['profile_user_id']] = true;
		}
		$users = $profilePostModel->getModelFromCache('XenForo_Model_User')->getUsersByIds(array_keys($userIds), array(
			'join' => XenForo_Model_User::FETCH_USER_PRIVACY,
			'followingUserId' => $viewingUser['user_id']
		));

		foreach ($comments AS $key => &$comment)
		{
			if (!isset($profilePosts[$comment['profile_post_id']]))
			{
				unset ($comments[$key]);
				continue;
			}

			$profilePost = $profilePosts[$comment['profile_post_id']];

			if (isset($users[$profilePost['profile_user_id']]))
			{
				$user = $users[$profilePost['profile_user_id']];
				if (!$profilePostModel->canViewProfilePostAndContainer(
					$profilePost, $user, $null, $viewingUser
				))
				{
					unset($comments[$key]);
					continue;
				}
				else
				{
					$comment['profileUser'] = $user;
				}
			}

			$comment['profilePost'] = $profilePost;
		}

		return $comments;
	}

	public function canViewResult(array $result, array $viewingUser)
	{
		return true; // viewability determined above
	}

	/**
	 * Prepares a result for display.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::prepareResult()
	 */
	public function prepareResult(array $result, array $viewingUser)
	{
		$profilePostModel = $this->_getProfilePostModel();
		return $profilePostModel->prepareProfilePostComment($result, $result['profilePost'], $result['profileUser'], $viewingUser);
	}

	/**
	 * Gets the date of the result (from the result's content).
	 *
	 * @see XenForo_Search_DataHandler_Abstract::getResultDate()
	 */
	public function getResultDate(array $result)
	{
		return $result['comment_date'];
	}

	/**
	 * Renders a result to HTML.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::renderResult()
	 */
	public function renderResult(XenForo_View $view, array $result, array $search)
	{
		return $view->createTemplateObject('search_result_profile_post_comment', array(
			'comment' => $result,
			'search' => $search,
			'enableInlineMod' => $this->_inlineModEnabled
		));
	}

	/**
	 * Returns an array of content types handled by this class
	 *
	 * @see XenForo_Search_DataHandler_Abstract::getSearchContentTypes()
	 */
	public function getSearchContentTypes()
	{
		return array('profile_post_comment');
	}

	/**
	 * Get type-specific constrints from input.
	 *
	 * @param XenForo_Input $input
	 *
	 * @return array
	 */
	public function getTypeConstraintsFromInput(XenForo_Input $input)
	{
		$constraints = array();

		if ($profileUsersInput = $input->filterSingle('profile_users', XenForo_Input::STRING))
		{
			$userModel = $this->_getUserModel();

			$profileUsers = $userModel->getUsersByNames(explode(',', $profileUsersInput));
			if ($profileUsers)
			{
				$constraints['profile_user'] = array_keys($profileUsers);
			}
		}

		return $constraints;
	}

	/**
	 * Process a type-specific constraint.
	 *
	 * @see XenForo_Search_DataHandler_Abstract::processConstraint()
	 */
	public function processConstraint(XenForo_Search_SourceHandler_Abstract $sourceHandler, $constraint, $constraintInfo, array $constraints)
	{
		if ($constraint == 'profile_user')
		{
			if ($constraintInfo)
			{
				return array(
					'metadata' => array('profile_user', $constraintInfo)
				);
			}
		}

		return false;
	}

	/**
	 * @return XenForo_Model_ProfilePost
	 */
	protected function _getProfilePostModel()
	{
		if (!$this->_profilePostModel)
		{
			$this->_profilePostModel = XenForo_Model::create('XenForo_Model_ProfilePost');
		}

		return $this->_profilePostModel;
	}

	/**
	 * @return XenForo_Model_User
	 */
	protected function _getUserModel()
	{
		if (!$this->_userModel)
		{
			$this->_userModel = XenForo_Model::create('XenForo_Model_User');
		}

		return $this->_userModel;
	}
}