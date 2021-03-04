<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_Helper
{
	public static function userHasPermission($permission)
	{
		$visitor     = XenForo_Visitor::getInstance();
		$options     = XenForo_Application::get('options');
		$minPosts    = $options->siropu_username_change_user_min_posts;
		$minDays     = $options->siropu_username_change_user_min_days;
		$permissions = $visitor->getPermissions();

		if (($minPosts && $visitor->message_count < $minPosts)
			|| ($minDays && $visitor->register_date > strtotime("-{$minDays} days")))
		{
			return false;
		}

		return XenForo_Permission::hasPermission($permissions, 'siropu_username_change', $permission);
	}
	public static function getUserWaitTime($data, $timeFrame)
	{
		$wait = ($timeFrame - round((time() - $data['date']) / 86400));

		if ($wait > 0)
		{
			return $wait;
		}

		return 0;
	}
	public static function sortHistoryById($resultArray)
	{
		$data = array();
		foreach ($resultArray as $row)
		{
			$data[$row['history_id']] = $row;
		}
		return $data;
	}
	public static function postAnnouncement($data, $member)
	{
		$options = XenForo_Application::get('options');

		if (!$data['incognito']
			&& ($forumId = $options->siropu_username_change_forum_id)
			&& ($userId = $options->siropu_username_change_user_id)
			&& ($user = XenForo_Model::create('XenForo_Model_User')->getUserById($userId)))
		{
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_Discussion_Thread');
			$dw->set('user_id', $userId);
			$dw->set('username', $user['username']);
			$dw->set('node_id', $forumId);
			$dw->set('title', new XenForo_Phrase('siropu_username_change_thread_title',
				array('old' => $data['username_old'], 'new' => $data['username_new'])));

			$postWriter = $dw->getFirstMessageDw();
			$postWriter->set('message', new XenForo_Phrase('siropu_username_change_thread_content',
				array(
					'old'  => $data['username_old'],
					'link' => XenForo_Link::buildPublicLink('canonical:members', $member),
					'new'  => $data['username_new']
				)
			));

			$dw->save();
		}
	}
}