<?php

class XenForo_Importer_PhpBb31x extends XenForo_Importer_PhpBb3
{
	public static function getName()
	{
		return 'phpBB 3.1.x (Beta)';
	}

	protected function _resolveTimeZone($timeZone, $useDst)
	{
		try
		{
			$time = new DateTime('now', new DateTimeZone($timeZone));
			$offset = $time->getTimezone()->getOffset($time) / 60 / 60;
			return $this->_importModel->resolveTimeZoneOffset($offset, $useDst);
		}
		catch (Exception $e)
		{
			return $this->_importModel->resolveTimeZoneOffset($timeZone, $useDst);
		}
	}

	protected function _getSelectUserSql($where)
	{
		return '
			SELECT users.*, pfd.*, ban.*, users.user_id, 1 AS user_dst,
				pfd.pf_phpbb_website AS user_website,
				pfd.pf_phpbb_interests AS user_interests,
				pfd.pf_phpbb_location AS user_from,
				pfd.pf_phpbb_occupation AS user_occ,
				pfd.pf_phpbb_icq AS user_icq,
				pfd.pf_phpbb_aol AS user_aim,
				pfd.pf_phpbb_yahoo AS user_yim
			FROM ' . $this->_prefix . 'users AS users
			LEFT JOIN ' . $this->_prefix . 'profile_fields_data AS pfd ON
				(pfd.user_id = users.user_id)
			LEFT JOIN ' . $this->_prefix . 'banlist AS ban ON
				(ban.ban_userid = users.user_id AND (ban.ban_end = 0 OR ban.ban_end > ' . XenForo_Application::$time . '))
			WHERE ' . $where . '
				AND users.user_type <> 2
			ORDER BY users.user_id
		';
	}

	public function stepAvatars($start, array $options)
	{
		$options = array_merge(array(
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(user_id)
				FROM ' . $prefix . 'users
				WHERE user_avatar_type = \'avatar.driver.upload\'
			');
		}

		return parent::stepAvatars($start, $options);
	}

	protected function _getAvatars($start, array $options)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT *
				FROM ' . $prefix . 'users
				WHERE user_id > ' . $sDb->quote($start) . '
					AND user_avatar_type = \'avatar.driver.upload\'
				ORDER BY user_id
			', $options['limit']
		));
	}

	protected function _getForums()
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll('
			SELECT *,
				forum_posts_approved AS forum_posts,
				forum_topics_approved AS forum_topics
			FROM ' . $prefix . 'forums
		');
	}

	protected function _getThreads($start, array $options)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT topics.*, topic_posts_approved AS topic_replies, topic_visibility AS topic_approved,
					IF(users.username IS NOT NULL, users.username, topics.topic_first_poster_name) AS username
				FROM ' . $prefix . 'topics AS topics FORCE INDEX (PRIMARY)
				LEFT JOIN ' . $prefix . 'users AS users ON (topics.topic_poster = users.user_id)
				INNER JOIN ' . $prefix . 'forums AS forums ON
					(topics.forum_id = forums.forum_id)
				WHERE topics.topic_id >= ' . $sDb->quote($start) . '
					AND topics.topic_status <> 2 # redirect
				ORDER BY topics.topic_id
			', $options['limit']
		));
	}

	protected function _getPosts(array $thread, $postDateStart, $maxPosts)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
					SELECT posts.*, post_visibility AS post_approved,
						IF(users.username IS NOT NULL, users.username, posts.post_username) AS username
					FROM ' . $prefix . 'posts AS posts
					LEFT JOIN ' . $prefix . 'users AS users ON (posts.poster_id = users.user_id)
					WHERE posts.topic_id = ' . $sDb->quote($thread['topic_id']) . '
						AND posts.post_time > ' . $sDb->quote($postDateStart) . '
					ORDER BY posts.post_time
			', $maxPosts
		));
	}
}