<?php

class XenForo_Importer_vBulletin4x extends XenForo_Importer_vBulletin
{
	public static function getName()
	{
		return 'vBulletin 4.x';
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_vBulletin::getSteps()
	 */
	public function getSteps()
	{
		$originalSteps = parent::getSteps();

		$newStep = array(
			'title' => new XenForo_Phrase('import_content_tags'),
			'depends' => array('threads')
		);
		$steps = $this->_injectNewStep($originalSteps, $newStep, 'contentTags', 'threads');

		return $steps;
	}

	public function stepAttachments($start, array $options)
	{
		$options = array_merge(array(
			'path' => isset($this->_config['attachmentPath']) ? $this->_config['attachmentPath'] : '',
			'limit' => 50,
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		/* @var $model XenForo_Model_Import */
		$model = $this->_importModel;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(attachmentid)
				FROM ' . $prefix . 'attachment
			');
		}

		$contentId = $sDb->fetchOne('SELECT contenttypeid FROM ' . $prefix .'contenttype WHERE class = \'Post\'');

		$attachments = $sDb->fetchAll($sDb->limit(
			'
				SELECT attachment.attachmentid, attachment.userid, attachment.dateline,
					attachment.filename, attachment.counter, attachment.contentid, attachment.filedataid,
					filedata.userid AS filedata_userid
				FROM ' . $prefix . 'attachment AS attachment
				INNER JOIN ' . $prefix . 'filedata AS filedata ON (filedata.filedataid = attachment.filedataid)
				WHERE attachment.attachmentid > ' . $sDb->quote($start) . '
					AND attachment.contenttypeid = ' . $sDb->quote($contentId) . '
					AND attachment.state = \'visible\'
				ORDER BY attachment.attachmentid
			', $options['limit']
		));
		if (!$attachments)
		{
			return true;
		}

		$next = 0;
		$total = 0;

		$userIdMap = $model->getUserIdsMapFromArray($attachments, 'userid');

		$postIdMap = $model->getPostIdsMapFromArray($attachments, 'contentid');
		$posts = $model->getModelFromCache('XenForo_Model_Post')->getPostsByIds($postIdMap);

		foreach ($attachments AS $attachment)
		{
			$next = $attachment['attachmentid'];

			$newPostId = $this->_mapLookUp($postIdMap, $attachment['contentid']);
			if (!$newPostId)
			{
				continue;
			}

			if (!$options['path'])
			{
				$fData = $sDb->fetchOne('
					SELECT filedata
					FROM ' . $prefix . 'filedata
					WHERE filedataid = ' . $sDb->quote($attachment['filedataid'])
				);
				if ($fData === '')
				{
					continue;
				}

				$attachFile = tempnam(XenForo_Helper_File::getTempDir(), 'xf');
				if (!$attachFile || !@file_put_contents($attachFile, $fData))
				{
					continue;
				}

				$isTemp = true;
			}
			else
			{
				$attachFileOrig = "$options[path]/" . implode('/', str_split($attachment['filedata_userid'])) . "/$attachment[filedataid].attach";
				if (!file_exists($attachFileOrig))
				{
					continue;
				}

				$attachFile = tempnam(XenForo_Helper_File::getTempDir(), 'xf');
				copy($attachFileOrig, $attachFile);

				$isTemp = true;
			}

			$success = $model->importPostAttachment(
				$attachment['attachmentid'],
				$this->_convertToUtf8($attachment['filename']),
				$attachFile,
				$this->_mapLookUp($userIdMap, $attachment['userid'], 0),
				$newPostId,
				$attachment['dateline'],
				array('view_count' => $attachment['counter']),
				array($this, 'processAttachmentTags'),
				$posts[$newPostId]['message']
			);
			if ($success)
			{
				$total++;
			}

			if ($isTemp)
			{
				@unlink($attachFile);
			}
		}

		$this->_session->incrementStepImportTotal($total);

		return array($next, $options, $this->_getProgressOutput($next, $options['max']));
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
				SELECT MAX(threadid)
				FROM ' . $prefix . 'thread
			');
		}

		$threads = $sDb->fetchAll($sDb->limit(
			'
				SELECT threadid, taglist
				FROM ' . $prefix . 'thread
				WHERE threadid > ?
				ORDER BY threadid ASC
			', $options['limit']
		), $start);

		if (!$threads)
		{
			return true;
		}

		$next = 0;
		$total = 0;

		XenForo_Db::beginTransaction();

		$tags = array();

		foreach ($threads AS $thread)
		{
			$next = $thread['threadid'];

			$tagList = explode(',', $thread['taglist']);
			if (!$tagList)
			{
				continue;
			}

			foreach ($tagList AS $tagText)
			{
				$tagText = trim($tagText);

				if (!$tagText)
				{
					continue;
				}

				$tagId = $sDb->fetchOne('
					SELECT tagid
					FROM ' . $prefix . 'tag
					WHERE tagtext = ?
				', $tagText);

				if (!$tagId)
				{
					continue;
				}

				$tag = $sDb->fetchRow('
					SELECT *
					FROM ' . $prefix . 'tagcontent
					WHERE tagid = ?
					AND contentid = ?
					AND contenttypeid = 2
				', array($tagId, $thread['threadid']));

				if (!$tag)
				{
					continue;
				}

				$tags[] = array(
					'tagid' => $tagId,
					'tagtext' => $tagText,
					'contentid' => $thread['threadid'],
					'userid' => $tag['userid'],
					'dateline' => $tag['dateline']
				);
			}

			$total++;
		}

		$threadIdMap = $model->getThreadIdsMapFromArray($tags, 'contentid');
		$userIdMap = $model->getThreadIdsMapFromArray($tags, 'userid');

		foreach ($tags AS $tag)
		{
			$newThreadId = $this->_mapLookUp($threadIdMap, $tag['contentid']);
			if (!$newThreadId)
			{
				continue;
			}

			$newThread = $this->_db->fetchRow('
				SELECT *
				FROM xf_thread
				WHERE thread_id = ?
			', $newThreadId);
			if (!$newThread)
			{
				continue;
			}

			$userId = $this->_mapLookUp($userIdMap, $tag['userid'], 0);

			$model->importTag($this->_convertToUtf8($tag['tagtext'], true), 'thread', $newThread['thread_id'], array(
				'add_user_id' => $userId,
				'add_date' => $tag['dateline'],
				'visible' => ($newThread['discussion_state'] == 'visible'),
				'content_date' => $newThread['post_date']
			));
		}

		XenForo_Db::commit();

		$this->_session->incrementStepImportTotal($total);

		return array($next, $options, $this->_getProgressOutput($next, $options['max']));
	}
}