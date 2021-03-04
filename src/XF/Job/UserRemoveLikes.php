<?php

namespace XF\Job;

class UserRemoveLikes extends AbstractJob
{
	protected $defaultData = [
		'userId' => null,
		'cutOff' => null,
		'count' => 0,
		'total' => 0
	];

	public function run($maxRunTime)
	{
		$startTime = microtime(true);

		if (!$this->data['userId'] || $this->data['cutOff'] === null)
		{
			return $this->complete();
		}

		/** @var \XF\Repository\LikedContent $likeRepo */
		$likeRepo = $this->app->repository('XF:LikedContent');
		$likeFinder = $likeRepo->findLikesByLikeUserId($this->data['userId'])
			->where('like_date', '>', $this->data['cutOff']);

		$count = $likeFinder->total();
		if (!$count)
		{
			return $this->complete();
		}

		if (!$this->data['total'])
		{
			$this->data['total'] = $count;
		}

		foreach ($likeFinder->fetch(500) AS $like)
		{
			try
			{
				$like->delete(false);
			}
			catch(\Exception $e) {}

			$this->data['count']++;

			if ($maxRunTime && microtime(true) - $startTime > $maxRunTime)
			{
				break;
			}
		}

		return $this->resume();
	}

	public function getStatusMessage()
	{
		return sprintf('%s... %s/%s', \XF::phrase('removing_likes'), $this->data['count'], $this->data['total']);
	}

	public function canCancel()
	{
		return true;
	}

	public function canTriggerByChoice()
	{
		return false;
	}
}