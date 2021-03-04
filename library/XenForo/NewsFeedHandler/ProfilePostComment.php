<?php

class XenForo_NewsFeedHandler_ProfilePostComment extends XenForo_NewsFeedHandler_Abstract
{
	/**
	 * @var XenForo_Model_ProfilePost
	 */
	protected $_profilePostModel = null;

	/**
	 * Fetches related content (profile post comments) by IDs
	 *
	 * @param array $contentIds
	 * @param XenForo_Model_NewsFeed $model
	 * @param array $viewingUser Information about the viewing user (keys: user_id, permission_combination_id, permissions)
	 *
	 * @return array
	 */
	public function getContentByIds(array $contentIds, $model, array $viewingUser)
	{
		$profilePostModel = $this->_getProfilePostModel();

		$comments = $profilePostModel->getProfilePostCommentsByIds($contentIds);

		$profilePostIds = XenForo_Application::arrayColumn($comments, 'profile_post_id');
		$profilePosts = $profilePostModel->getProfilePostsByIds($profilePostIds, array(
			'join' => XenForo_Model_ProfilePost::FETCH_USER_RECEIVER
				| XenForo_Model_ProfilePost::FETCH_USER_RECEIVER_PRIVACY
				| XenForo_Model_ProfilePost::FETCH_USER_POSTER,
			'visitingUser' => $viewingUser
		));
		foreach ($comments AS $key => &$comment)
		{
			if (isset($profilePosts[$comment['profile_post_id']]))
			{
				$comment['profilePost'] = $profilePosts[$comment['profile_post_id']];
				$comment['profileUser'] = $profilePostModel->getProfileUserFromProfilePost($comment['profilePost'], $viewingUser);

				if (!$comment['profilePost'] || !$comment['profileUser'])
				{
					unset($comments[$key]);
					continue;
				}
				if (!$profilePostModel->canViewProfilePostAndContainer($comment['profilePost'], $comment['profileUser'], $null, $viewingUser))
				{
					unset($comments[$key]);
				}
			}
			else
			{
				unset($comments[$key]);
			}
		}
		return $comments;
	}

	/**
	 * Determines if the given news feed item is viewable.
	 *
	 * @param array $item
	 * @param mixed $content
	 * @param array $viewingUser
	 *
	 * @return boolean
	 */
	public function canViewNewsFeedItem(array $item, $content, array $viewingUser)
	{
		return $this->_getProfilePostModel()->canViewProfilePostComment($content, $content['profilePost'], $content['profileUser'], $null, $viewingUser);
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
}