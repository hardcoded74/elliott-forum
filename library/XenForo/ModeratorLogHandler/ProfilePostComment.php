<?php

class XenForo_ModeratorLogHandler_ProfilePostComment extends XenForo_ModeratorLogHandler_Abstract
{
	protected $_skipLogSelfActions = array(
		'edit'
	);

	protected function _log(array $logUser, array $content, $action, array $actionParams = array(), $parentContent = null)
	{
		$contentTitle = $content['username'];

		$dw = XenForo_DataWriter::create('XenForo_DataWriter_ModeratorLog');
		$dw->bulkSet(array(
			'user_id' => $logUser['user_id'],
			'content_type' => 'profile_post_comment',
			'content_id' => $content['profile_post_comment_id'],
			'content_user_id' => $content['user_id'],
			'content_username' => $content['username'],
			'content_title' => $contentTitle,
			'content_url' => XenForo_Link::buildPublicLink('profile-posts/comments', $content),
			'discussion_content_type' => 'profile_post',
			'discussion_content_id' => $content['profile_post_id'],
			'action' => $action,
			'action_params' => $actionParams
		));
		$dw->save();

		return $dw->get('moderator_log_id');
	}

	protected function _prepareEntry(array $entry)
	{
		// will be escaped in template
		$entry['content_title'] = new XenForo_Phrase('profile_post_comment_by_x', array('username' => $entry['content_title']));

		return $entry;
	}
}