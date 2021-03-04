<?php

class XenForo_StatsHandler_ProfilePostComment extends XenForo_StatsHandler_Abstract
{
	public function getStatsTypes()
	{
		return array(
			'profile_post_comment' => new XenForo_Phrase('profile_post_comments'),
			'profile_post_comment_like' => new XenForo_Phrase('profile_post_comment_likes')
		);
	}

	public function getData($startDate, $endDate)
	{
		$db = $this->_getDb();

		$profilePostComments = $db->fetchPairs(
			$this->_getBasicDataQuery('xf_profile_post_comment', 'comment_date', 'message_state = ?'),
			array($startDate, $endDate, 'visible')
		);

		$profilePostCommentLikes = $db->fetchPairs(
			$this->_getBasicDataQuery('xf_liked_content', 'like_date', 'content_type = ?'),
			array($startDate, $endDate, 'profile_post_comment')
		);

		return array(
			'profile_post_comment' => $profilePostComments,
			'profile_post_comment_like' => $profilePostCommentLikes
		);
	}
}