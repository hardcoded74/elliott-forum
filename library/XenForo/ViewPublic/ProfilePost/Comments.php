<?php

class XenForo_ViewPublic_ProfilePost_Comments extends XenForo_ViewPublic_Base
{
	public function renderJson()
	{
		$comments = array();

		if ($this->_params['profilePost']['first_comment_date'] < $this->_params['firstCommentShown']['comment_date'])
		{
			$comments[] = $this->createTemplateObject(
				'profile_post_comments_before', $this->_params
			);
		}

		foreach ($this->_params['comments'] AS $comment)
		{
			$template = empty($comment['isDeleted']) ? 'profile_post_comment' : 'profile_post_comment_deleted';

			$comments[] = $this->createTemplateObject(
				$template, array('comment' => $comment) + $this->_params
			);
		}

		foreach ($comments AS &$comment)
		{
			$comment = strval($comment);
		}

		$template = $this->createTemplateObject('profile_post_comment');

		return array(
			'comments' => $comments,
			'css' => $template->getRequiredExternals('css'),
			'js' => $template->getRequiredExternals('js')
		);
	}
}