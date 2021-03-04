<?php

/**
 * Moderation queue for profile post comments.
 *
 * @package XenForo_Moderation
 */
class XenForo_ModerationQueueHandler_ProfilePostComment extends XenForo_ModerationQueueHandler_Abstract
{
	/**
	 * Gets visible moderation queue entries for specified user.
	 *
	 * @see XenForo_ModerationQueueHandler_Abstract::getVisibleModerationQueueEntriesForUser()
	 */
	public function getVisibleModerationQueueEntriesForUser(array $contentIds, array $viewingUser)
	{
		/** @var XenForo_Model_ProfilePost $profilePostModel */
		$profilePostModel = XenForo_Model::create('XenForo_Model_ProfilePost');

		$comments = $profilePostModel->getProfilePostCommentsByIds($contentIds);

		$profilePostIds = XenForo_Application::arrayColumn($comments, 'profile_post_id');
		$profilePosts = $profilePostModel->getProfilePostsByIds($profilePostIds, array(
			'join' => XenForo_Model_ProfilePost::FETCH_USER_RECEIVER
				| XenForo_Model_ProfilePost::FETCH_USER_RECEIVER_PRIVACY
				| XenForo_Model_ProfilePost::FETCH_USER_POSTER,
			'visitingUser' => $viewingUser
		));

		$output = array();

		foreach ($comments AS $key => &$comment)
		{
			if (isset($profilePosts[$comment['profile_post_id']]))
			{
				$comment['profilePost'] = $profilePosts[$comment['profile_post_id']];
				$comment['profileUser'] = $profilePostModel->getProfileUserFromProfilePost($comment['profilePost'], $viewingUser);

				if (!$comment['profilePost'] || !$comment['profileUser'])
				{
					continue;
				}

				$canManage = true;
				if (!$profilePostModel->canViewProfilePostAndContainer($comment['profilePost'], $comment['profileUser'], $null, $viewingUser))
				{
					$canManage = false;
				}
				else if (!$profilePostModel->canViewProfilePostComment($comment, $comment['profilePost'], $comment['profileUser'], $null, $viewingUser))
				{
					$canManage = false;
				}
				else if (!XenForo_Permission::hasPermission($viewingUser['permissions'], 'profilePost', 'editAny')
					|| !XenForo_Permission::hasPermission($viewingUser['permissions'], 'profilePost', 'deleteAny')
				)
				{
					$canManage = false;
				}

				if ($canManage)
				{
					$output[$comment['profile_post_comment_id']] = array(
						'message' => $comment['message'],
						'user' => array(
							'user_id' => $comment['user_id'],
							'username' => $comment['username']
						),
						'title' => new XenForo_Phrase('profile_post_comment_by_x', array('username' => $comment['username'])),
						'link' => XenForo_Link::buildPublicLink('profile-posts/comments', $comment),
						'contentTypeTitle' => new XenForo_Phrase('profile_post_comment'),
						'titleEdit' => false
					);
				}
			}
		}

		return $output;
	}

	/**
	 * Approves the specified moderation queue entry.
	 *
	 * @see XenForo_ModerationQueueHandler_Abstract::approveModerationQueueEntry()
	 */
	public function approveModerationQueueEntry($contentId, $message, $title)
	{
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_ProfilePostComment', XenForo_DataWriter::ERROR_SILENT);
		$dw->setExistingData($contentId);
		$dw->set('message_state', 'visible');
		$dw->set('message', $message);

		if ($dw->save())
		{
			XenForo_Model_Log::logModeratorAction('profile_post_comment', $dw->getMergedData(), 'approve');

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Deletes the specified moderation queue entry.
	 *
	 * @see XenForo_ModerationQueueHandler_Abstract::deleteModerationQueueEntry()
	 */
	public function deleteModerationQueueEntry($contentId)
	{
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_ProfilePostComment', XenForo_DataWriter::ERROR_SILENT);
		$dw->setExistingData($contentId);
		$dw->set('message_state', 'deleted');

		if ($dw->save())
		{
			XenForo_Model_Log::logModeratorAction('profile_post_comment', $dw->getMergedData(), 'delete_soft', array('reason' => ''));
			return true;
		}
		else
		{
			return false;
		}
	}
}