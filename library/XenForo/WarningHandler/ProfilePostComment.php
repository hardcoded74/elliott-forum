<?php

class XenForo_WarningHandler_ProfilePostComment extends XenForo_WarningHandler_Abstract
{
	protected function _canView(array $content, array $viewingUser)
	{
		return $this->_getProfilePostModel()->canViewProfilePostComment($content, $content['profilePost'], $content['profileUser'], $null, $viewingUser);
	}

	protected function _canWarn($userId, array $content, array $viewingUser)
	{
		return $this->_getProfilePostModel()->canWarnProfilePostComment($content, $content['profilePost'], $content['profileUser'], $null, $viewingUser);
	}

	protected function _canDeleteContent(array $content, array $viewingUser)
	{
		return $this->_getProfilePostModel()->canDeleteProfilePostComment($content, $content['profilePost'], $content['profileUser'], 'soft', $null, $viewingUser);
	}

	protected function _getContent(array $contentIds, array $viewingUser)
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

	public function getContentTitle(array $content)
	{
		return $content['username'];
	}

	public function getContentDetails(array $content)
	{
		return $content['message'];
	}

	public function getContentTitleForDisplay($title)
	{
		// will be escaped in template
		return new XenForo_Phrase('profile_post_comment_by_x', array('username' => $title), false);
	}

	public function getContentUrl(array $content, $canonical = false)
	{
		return XenForo_Link::buildPublicLink(($canonical ? 'canonical:' : '') . 'profile-posts/comments', $content);
	}

	protected function _warn(array $warning, array $content, $publicMessage, array $viewingUser)
	{
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_ProfilePostComment', XenForo_DataWriter::ERROR_SILENT);
		if ($dw->setExistingData($content))
		{
			$dw->set('warning_id', $warning['warning_id']);
			$dw->set('warning_message', $publicMessage);
			$dw->save();
		}
	}

	protected function _reverseWarning(array $warning, array $content)
	{
		if ($content)
		{
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_ProfilePostComment', XenForo_DataWriter::ERROR_SILENT);
			if ($dw->setExistingData($content))
			{
				$dw->set('warning_id', 0);
				$dw->set('warning_message', '');
				$dw->save();
			}
		}
	}

	protected function _deleteContent(array $content, $reason, array $viewingUser)
	{
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_ProfilePostComment', XenForo_DataWriter::ERROR_SILENT);
		if ($dw->setExistingData($content))
		{
			$dw->setExtraData(XenForo_DataWriter_DiscussionMessage::DATA_DELETE_REASON, $reason);
			$dw->set('message_state', 'deleted');
			$dw->save();
		}

		XenForo_Model_Log::logModeratorAction(
			'profile_post_comment', $content, 'delete_soft', array('reason' => $reason),
			$this->_getProfilePostModel()->getProfilePostById($content['profile_post_id'])
		);
	}

	public function canPubliclyDisplayWarning()
	{
		return true;
	}

	/**
	 * @return XenForo_Model_ProfilePost
	 */
	protected function _getProfilePostModel()
	{
		return XenForo_Model::create('XenForo_Model_ProfilePost');
	}
}