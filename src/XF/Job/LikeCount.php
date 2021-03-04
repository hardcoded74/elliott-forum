<?php

namespace XF\Job;

class LikeCount extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT user_id
				FROM xf_user
				WHERE user_id > ?
				ORDER BY user_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \XF\Repository\LikedContent $likeRepo */
		$likeRepo = $this->app->repository('XF:LikedContent');
		$count = $likeRepo->getUserLikeCount($id);

		$this->app->db()->update('xf_user', ['like_count' => $count], 'user_id = ?', $id);
	}

	protected function getStatusType()
	{
		return \XF::phrase('like_counts');
	}
}