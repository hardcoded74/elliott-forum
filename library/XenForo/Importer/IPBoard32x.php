<?php

class XenForo_Importer_IPBoard32x extends XenForo_Importer_IPBoard
{
	/**
	 * Name of meta app key for content tags import.
	 *
	 * @var string
	 */
	protected $_metaApp = 'forums';

	/**
	 * Name of meta area key for content tags import
	 *
	 * @var string
	 */
	protected $_metaArea = 'topics';

	public static function getName()
	{
		return 'IP.Board 3.2/3.3';
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::getSteps()
	 */
	public function getSteps()
	{
		$originalSteps = parent::getSteps();

		$newStep = array(
			'title' => new XenForo_Phrase('import_content_tags'),
			'depends' => array('threads')
		);
		$steps = $this->_injectNewStep($originalSteps, $newStep, 'contentTags', 'threads');

		unset ($steps['profileComments']);

		return $steps;
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::_getUserGroupAvatarPerms($userGroup)
	 */
	protected function _getUserGroupAvatarPerms(array $userGroup)
	{
		// did IPB remove avatar permissions in 3.2?
		return array(
			'allowed' => true,
			'maxFileSize' => 51200
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::_getStatusUpdates($start, $limit)
	 */
	protected function _getStatusUpdates($start, $limit)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT msus.*,
					members.name AS status_author_name
				FROM ' . $prefix . 'member_status_updates AS msus
				INNER JOIN ' . $prefix . 'members AS members ON
					(msus.status_author_id = members.member_id)
				WHERE msus.status_id > ' . $sDb->quote($start) . '
				ORDER BY msus.status_id
			', $limit
		));
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::_getStatusUpdateUserIdMap($model, $statusUpdates)
	 */
	protected function _getStatusUpdateUserIdMap(XenForo_Model_Import $model, array $statusUpdates)
	{
		return $model->getUserIdsMapFromArray($statusUpdates, array('status_member_id', 'status_author_id'));
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::_getStatusUpdateUserInfo($statusUpdate, $userIdMap)
	 */
	protected function _getStatusUpdateUserInfo(array $statusUpdate, array $userIdMap)
	{
		$profileUserId = $this->_mapLookUp($userIdMap, $statusUpdate['status_member_id']);
		$userId = $this->_mapLookUp($userIdMap, $statusUpdate['status_author_id']);
		$username = $statusUpdate['status_author_name'];
		$ip = $statusUpdate['status_author_ip'];

		return array($profileUserId, $userId, $username, $ip);
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::_importStatusUpdateExtra($statusUpdate, $profilePostId, $profilePost)
	 */
	protected function _importStatusUpdateExtra(array $statusUpdate, $profilePostId, array $profilePost)
	{
		$this->_importStatusUpdateLikes($statusUpdate, $profilePostId, $profilePost);

		return parent::_importStatusUpdateExtra($statusUpdate, $profilePostId, $profilePost);
	}

	protected function _importStatusUpdateLikes(array $statusUpdate, $profilePostId, array $profilePost)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;
		$model = $this->_importModel;

		$likes = $sDb->fetchAll('
			SELECT like_rel_id, like_member_id, like_added
			FROM ' . $prefix . 'core_like
			WHERE like_rel_id = ' . $sDb->quote($statusUpdate['status_id']) . '
				AND like_app = \'members\'
				AND like_area = \'status\'
				AND like_is_anon = 0
				AND like_visible = 1
		');

		if ($likes)
		{
			$userIdMap = $model->getUserIdsMapFromArray($likes, 'like_member_id');

			foreach ($likes AS $like)
			{
				$model->importLike(
					'profile_post', $profilePostId,
					$profilePost['user_id'],
					$this->_mapLookUp($userIdMap, $like['like_member_id']),
					$like['like_added']
				);
			}
		}
	}

	/**
	 * Imports thread watch records for the given thread
	 *
	 * @param integer $threadId Imported XenForo thread ID
	 * @param array $sourceThread IPB source thread data
	 */
	protected function _importThreadWatch($threadId, array $sourceThread)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;
		$model = $this->_importModel;

		$subs = $sDb->fetchPairs('
			SELECT like_member_id, like_notify_freq
			FROM ' . $prefix . 'core_like
			WHERE like_rel_id = ' . $sDb->quote($sourceThread['tid']) . '
				AND like_app = \'forums\'
				AND like_area = \'topics\'
				AND like_notify_do = 1
		');
		if ($subs)
		{
			$userIdMap = $model->getImportContentMap('user', array_keys($subs));
			foreach ($subs AS $userId => $emailUpdate)
			{
				$newUserId = $this->_mapLookUp($userIdMap, $userId);
				if (!$newUserId)
				{
					continue;
				}

				$model->importThreadWatch($newUserId, $threadId, ($emailUpdate == 'none' || empty($emailUpdate) ? 0 : 1));
			}
		}
	}

	public function stepContentTags($start, array $options)
	{
		$options = array_merge(array(
			'limit' => 100,
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		/* @var $model XenForo_Model_Import */
		$model = $this->_importModel;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(tag_id)
				FROM ' . $prefix . 'core_tags
				WHERE tag_meta_app = ?
					AND tag_meta_area = ?
			', array($this->_metaApp, $this->_metaArea));
		}

		$tags = $sDb->fetchAll($sDb->limit(
			'
				SELECT *
					FROM ' . $prefix . 'core_tags
				WHERE tag_meta_app = ?
					AND tag_meta_area = ?
					AND tag_id > ?
				ORDER BY tag_id ASC
			', $options['limit']
		), array($this->_metaApp, $this->_metaArea, $start));

		if (!$tags)
		{
			return true;
		}

		$next = 0;
		$total = 0;

		$threadIdMap = $model->getThreadIdsMapFromArray($tags, 'tag_meta_id');
		$userIdMap = $model->getUserIdsMapFromArray($tags, 'tag_member_id');

		XenForo_Db::beginTransaction();

		foreach ($tags AS $tag)
		{
			$next = $tag['tag_id'];

			$newThreadId = $this->_mapLookUp($threadIdMap, $tag['tag_meta_id']);
			if (!$newThreadId)
			{
				continue;
			}

			$thread = $this->_db->fetchRow('
				SELECT *
				FROM xf_thread
				WHERE thread_id = ?
			', $newThreadId);
			if (!$thread)
			{
				continue;
			}

			$memberId = $this->_mapLookUp($userIdMap, $tag['tag_member_id'], 0);

			$model->importTag($this->_convertToUtf8($tag['tag_text'], true), 'thread', $thread['thread_id'], array(
				'add_user_id' => $memberId,
				'add_date' => $tag['tag_added'],
				'visible' => ($thread['discussion_state'] == 'visible'),
				'content_date' => $thread['post_date']
			));

			$total++;
		}

		XenForo_Db::commit();

		$this->_session->incrementStepImportTotal($total);

		return array($next, $options, $this->_getProgressOutput($next, $options['max']));
	}
}