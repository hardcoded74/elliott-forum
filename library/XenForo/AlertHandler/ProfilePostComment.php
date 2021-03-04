<?php

class XenForo_AlertHandler_ProfilePostComment extends XenForo_AlertHandler_Abstract
{
	/**
	 * @var XenForo_Model_ProfilePost
	 */
	protected $_profilePostModel = null;

	/**
	 * Gets the profile post comment content.
	 * @see XenForo_AlertHandler_Abstract::getContentByIds()
	 */
	public function getContentByIds(array $contentIds, $model, $userId, array $viewingUser)
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
	 * Determines if the profile post comment is viewable.
	 * @see XenForo_AlertHandler_Abstract::canViewAlert()
	 */
	public function canViewAlert(array $alert, $content, array $viewingUser)
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