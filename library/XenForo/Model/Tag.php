<?php

class XenForo_Model_Tag extends XenForo_Model
{
	const CONTENT_TYPE = 0;
	const CONTENT_ID = 1;

	public function splitTags($list)
	{
		return preg_split('/\s*,\s*/', $list, -1, PREG_SPLIT_NO_EMPTY);
	}

	public function normalizeTag($tag)
	{
		$tag = utf8_strtolower($tag);

		try
		{
			// if this matches, then \v isn't known (appears to be PCRE < 7.2) so don't strip
			if (!preg_match('/\v/', 'v'))
			{
				$new = preg_replace('/\v+/u', ' ', $tag);
				if (is_string($new))
				{
					$tag = $new;
				}
			}
		}
		catch (Exception $e) {}
		$tag = preg_replace('/\s+/u', ' ', $tag);

		$tag = preg_replace('/^[^\d\pL]+(.*)[^\d\pL]+$/siUu', '$1', $tag);
		$tag = trim($tag);

		return $tag;
	}

	public function isValidTag($tag)
	{
		$length = utf8_strlen($tag);
		$lengthLimits = XenForo_Application::getOptions()->tagLength;

		$minLength = max($lengthLimits['min'], 1);
		$maxLength = $lengthLimits['max'] <= 0 ? 100 : min($lengthLimits['max'], 100);

		if ($length < $minLength)
		{
			return false;
		}
		if ($length > $maxLength)
		{
			return false;
		}

		$validation = XenForo_Application::getOptions()->tagValidation;

		$disallowed = preg_split('/\r?\n/', $validation['disallowedWords']);
		if ($disallowed)
		{
			foreach ($disallowed AS $disallowedCheck)
			{
				$disallowedCheck = trim($disallowedCheck);
				if ($disallowedCheck === '')
				{
					continue;
				}
				if (stripos($tag, $disallowedCheck) !== false)
				{
					return false;
				}
			}
		}

		if ($validation['matchRegex'] && !preg_match('/\W[\s\w]*e[\s\w]*$/', $validation['matchRegex']))
		{
			try
			{
				if (!preg_match($validation['matchRegex'], $tag))
				{
					return false;
				}
			}
			catch (Exception $e)
			{
				XenForo_Error::logException($e, false);
			}
		}

		$censored = XenForo_Helper_String::censorString($tag);
		if ($censored != $tag)
		{
			return false;
		}

		return true;
	}

	public function getFoundTagsInList(array $search, array $dbList, &$notFound = array())
	{
		$found = array();
		$notFound = array();

		foreach ($search AS $tag)
		{
			$tag = $this->normalizeTag($tag);
			$tagCompare = utf8_deaccent($tag);
			$foundKey = null;

			foreach ($dbList AS $id => $dbTag)
			{
				if (utf8_deaccent($dbTag['tag']) == $tagCompare)
				{
					$foundKey = $id;
					break;
				}
			}

			if ($foundKey === null)
			{
				$notFound[] = $tag;
			}
			else
			{
				$found[$foundKey] = $dbList[$foundKey];
			}
		}

		return $found;
	}

	public function getTagByUrl($tagUrl)
	{
		return $this->_getDb()->fetchRow("
			SELECT *
			FROM xf_tag
			WHERE tag_url = ?
		", $tagUrl);
	}

	public function getTagById($tagId)
	{
		return $this->_getDb()->fetchRow("
			SELECT *
			FROM xf_tag
			WHERE tag_id = ?
		", $tagId);
	}

	public function autoCompleteTag($tag, $limit = 10)
	{
		return $this->fetchAllKeyed($this->limitQueryResults(
			"
				SELECT *
				FROM xf_tag
				WHERE tag LIKE " . XenForo_Db::quoteLike($tag, 'r') . "
					AND (use_count > 0 OR permanent = 1)
				ORDER BY tag
			", $limit
		), 'tag_id');
	}

	public function getTagList($containing = null, array $fetchOptions = array())
	{
		$limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

		if ($containing && strlen($containing))
		{
			$containingSql = "AND tag LIKE " . XenForo_Db::quoteLike($containing, 'lr');
		}
		else
		{
			$containingSql = '';
		}

		if (isset($fetchOptions['order']))
		{
			switch ($fetchOptions['order'])
			{
				case 'use_count': $orderBy = 'use_count DESC'; break;
				case 'last_use_date': $orderBy = 'last_use_date DESC'; break;
				case 'tag':
				default: $orderBy = 'tag';
			}
		}
		else
		{
			$orderBy = 'tag';
		}

		return $this->fetchAllKeyed($this->limitQueryResults(
			"
				SELECT *
				FROM xf_tag
				WHERE 1=1 {$containingSql}
				ORDER BY {$orderBy}
			", $limitOptions['limit'], $limitOptions['offset']
		), 'tag_id');
	}

	public function countTagList($containing = null)
	{
		if ($containing && strlen($containing))
		{
			$containingSql = "AND tag LIKE " . XenForo_Db::quoteLike($containing, 'lr');
		}
		else
		{
			$containingSql = '';
		}

		return $this->_getDb()->fetchOne("
			SELECT COUNT(*)
			FROM xf_tag
			WHERE 1=1 {$containingSql}
		");
	}

	public function getTag($tag)
	{
		return $this->_getDb()->fetchRow("
			SELECT *
			FROM xf_tag
			WHERE tag = ?
		", $tag);
	}

	public function getTags(array $tags, &$notFound = array())
	{
		$notFound = array();

		$normalized = array();

		foreach ($tags AS $k => $tag)
		{
			$tag = $this->normalizeTag($tag);
			if (strlen($tag))
			{
				$normalized[$k] = $tag;
			}
		}

		if (!$normalized)
		{
			return array();
		}

		$dbTags = $this->fetchAllKeyed("
			SELECT *
			FROM xf_tag
			WHERE tag IN (" . $this->_getDb()->quote($normalized) . ")
		", 'tag_id');

		return $this->getFoundTagsInList($normalized, $dbTags, $notFound);
	}

	public function getTagsInRange($start, $limit)
	{
		return $this->fetchAllKeyed($this->limitQueryResults("
			SELECT *
			FROM xf_tag
			WHERE tag_id > ?
			ORDER BY tag_id
		", $limit), 'tag_id', $start);
	}

	public function getTagsForContent($contentType, $contentId)
	{
		return $this->fetchAllKeyed("
			SELECT tag_content.*, tag.*
			FROM xf_tag_content AS tag_content
			INNER JOIN xf_tag AS tag ON (tag.tag_id = tag_content.tag_id)
			WHERE tag_content.content_type = ?
				AND tag_content.content_id = ?
			ORDER BY tag.tag
		", 'tag_id', array($contentType, $contentId));
	}

	public function getContentTagCache($contentType, $contentId)
	{
		$tags = $this->getTagsForContent($contentType, $contentId);
		$cache = array();
		foreach ($tags AS $tag)
		{
			$cache[$tag['tag_id']] = array(
				'tag' => $tag['tag'],
				'tag_url' => $tag['tag_url']
			);
		}

		return $cache;
	}

	public function getTagListForEdit($contentType, $contentId, $editOthers, $userId = null)
	{
		if ($userId === null)
		{
			$userId = XenForo_Visitor::getUserId();
		}

		$editable = array();
		$uneditable = array();

		foreach ($this->getTagsForContent($contentType, $contentId) AS $tag)
		{
			if (!$editOthers && $userId != $tag['add_user_id'])
			{
				$uneditable[] = $tag['tag'];
			}
			else
			{
				$editable[] = $tag['tag'];
			}
		}

		return array(
			'editable' => $editable,
			'uneditable' => $uneditable
		);
	}

	public function getContentIdsByTagId($tagId, $limit, $visibleOnly = true)
	{
		$results = $this->_getDb()->query($this->limitQueryResults(
			"
				SELECT tag_content_id, content_type, content_id
				FROM xf_tag_content
				WHERE tag_id = ?
					" . ($visibleOnly ? "AND visible = 1" : '') . "
				ORDER BY content_date DESC
			", max(1, $limit)
		), $tagId);
		$output = array();
		while ($result = $results->fetch())
		{
			$output[$result['tag_content_id']] = array($result['content_type'], $result['content_id']);
		}

		return $output;
	}

	public function getTagsForCloud($limit, $minUses = 1)
	{
		$tagIds = $this->_getDb()->fetchCol($this->limitQueryResults(
			"
				SELECT tag_id
				FROM xf_tag
				WHERE use_count >= ?
				ORDER BY use_count DESC
			", $limit
		), $minUses);
		if (!$tagIds)
		{
			return array();
		}

		return $this->fetchAllKeyed("
			SELECT *
			FROM xf_tag
			WHERE tag_id IN (" . $this->_getDb()->quote($tagIds) . ")
			ORDER BY tag
		", 'tag_id');
	}

	public function getTagCloudLevels(array $tags, $levels = 7)
	{
		if (!$tags)
		{
			return array();
		}

		$uses = XenForo_Application::arrayColumn($tags, 'use_count');
		$min = min($uses);
		$max = max($uses);
		$levelSize = ($max - $min) / $levels;

		$output = array();

		if ($min == $max)
		{
			$middle = ceil($levels / 2);
			foreach ($tags AS $id => $tag)
			{
				$output[$id] = $middle;
			}
		}
		else
		{
			foreach ($tags AS $id => $tag)
			{
				$diffFromMin = $tag['use_count'] - $min;
				if (!$diffFromMin)
				{
					$level = 1;
				}
				else
				{
					$level = min($levels, ceil($diffFromMin / $levelSize));
				}
				$output[$id] = $level;
			}
		}

		return $output;
	}

	public function createTag($tag)
	{
		$tag = $this->normalizeTag($tag);
		if (!strlen($tag))
		{
			return null;
		}

		$dw = XenForo_DataWriter::create('XenForo_DataWriter_Tag');
		$dw->set('tag', $tag);
		$dw->preSave();
		if ($dw->getErrors())
		{
			$existingId = $this->_getDb()->fetchOne("
				SELECT tag_id
				FROM xf_tag
				WHERE tag = ?
			", $tag);
			if ($existingId)
			{
				return $existingId;
			}
			else
			{
				return null;
			}
		}

		$dw->save();

		return $dw->get('tag_id');
	}

	protected function _getUrlVersionOfTag($tag)
	{
		$db = $this->_getDb();

		$urlVersion = preg_replace('/[^a-zA-Z0-9_ -]/', '', utf8_romanize(utf8_deaccent($tag)));
		$urlVersion = preg_replace('/[ -]+/', '-', $urlVersion);

		if (!strlen($urlVersion))
		{
			$urlVersion = 1 + intval($db->fetchOne("
				SELECT MAX(tag_id)
				FROM xf_tag
			"));
		}
		else
		{
			$existing = $db->fetchRow("
				SELECT *
				FROM xf_tag
				WHERE tag_url = ?
					OR (tag_url LIKE ? AND tag_url REGEXP ?)
				ORDER BY tag_id DESC
				LIMIT 1
			", array ($urlVersion, "$urlVersion-%", "^{$urlVersion}-[0-9]+\$"));
			if ($existing)
			{
				$counter = 1;
				if ($existing['tag_url'] != $urlVersion && preg_match('/-(\d+)$/', $existing['tag_url'], $match))
				{
					$counter = $match[1];
				}

				$testExists = true;
				while ($testExists)
				{
					$counter++;
					$testExists = $db->fetchOne("
						SELECT tag_id
						FROM xf_tag
						WHERE tag_url = ?
					", "$urlVersion-$counter");
				}

				$urlVersion .= "-$counter";
			}
		}

		return $urlVersion;
	}

	public function adjustContentTags($contentType, $contentId, array $addIds, array $removeIds, $userId = null)
	{
		$handler = $this->getTagHandler($contentType);
		if (!$handler)
		{
			return null;
		}

		$content = $handler->getBasicContent($contentId);
		if (!$content)
		{
			return null;
		}

		if ($userId === null)
		{
			$userId = XenForo_Visitor::getUserId();
		}

		$db = $this->_getDb();
		XenForo_Db::beginTransaction($db);

		if ($removeIds)
		{
			$db->query("
				DELETE FROM xf_tag_content
				WHERE tag_id IN (" . $db->quote($removeIds) . ")
					AND content_type = ?
					AND content_id = ?
			", array($contentType, $contentId));
			$this->recalculateTagUsage($removeIds);
		}

		if ($addIds)
		{
			$contentDate = $handler->getContentDate($content);
			$visibleSql = $handler->getContentVisibility($content) ? 1 : 0;

			foreach ($addIds AS $addId)
			{
				$res = $this->_getDb()->query("
					INSERT IGNORE INTO xf_tag_content
						(content_type, content_id, tag_id, add_user_id, add_date, content_date, visible)
					VALUES
						(?, ?, ?, ?, ?, ?, ?)
				", array($contentType, $contentId, $addId, $userId, XenForo_Application::$time, $contentDate, $visibleSql));
				if ($res->rowCount() && $visibleSql)
				{
					$this->_getDb()->query("
						UPDATE xf_tag
						SET use_count = use_count + 1,
							last_use_date = ?
						WHERE tag_id = ?
					", array(XenForo_Application::$time, $addId));
				}
			}
		}

		$cache = $this->getContentTagCache($contentType, $contentId);
		$handler->updateContentTagCache($content, $cache);

		XenForo_Db::commit($db);

		return $cache;
	}

	public function rebuildTagCache($contentType, $contentId)
	{
		$handler = $this->getTagHandler($contentType);
		if (!$handler)
		{
			return false;
		}

		$content = $handler->getBasicContent($contentId);
		if (!$content)
		{
			return false;
		}

		$cache = $this->getContentTagCache($contentType, $contentId);
		$handler->updateContentTagCache($content, $cache);

		return true;
	}

	public function updateContentVisibility($contentType, $contentId, $visibility)
	{
		$db = $this->_getDb();
		$tagIds = $db->fetchAll("
			SELECT tag_id, tag_content_id, visible
			FROM xf_tag_content
			WHERE content_type = ?
				AND content_id = ?
		", array($contentType, $contentId));
		if (!$tagIds)
		{
			return;
		}

		$newVisibleSql = $visibility ? 1 : 0;
		$update = array();
		$recalc = array();
		foreach ($tagIds AS $tag)
		{
			if ($newVisibleSql != $tag['visible'])
			{
				$update[] = $tag['tag_content_id'];
				$recalc[] = $tag['tag_id'];
			}
		}
		if (!$update)
		{
			return;
		}

		XenForo_Db::beginTransaction($db);

		$db->update('xf_tag_content',
			array('visible' => $newVisibleSql),
			'tag_content_id IN (' . $db->quote($update) . ')'
		);
		$this->recalculateTagUsage($recalc);

		XenForo_Db::commit($db);
	}

	public function deleteContentTags($contentType, $contentId)
	{
		$db = $this->_getDb();
		$tagIds = $db->fetchPairs("
			SELECT tag_id, visible
			FROM xf_tag_content
			WHERE content_type = ?
				AND content_id = ?
		", array($contentType, $contentId));
		if (!$tagIds)
		{
			return;
		}

		$recalc = array();
		foreach ($tagIds AS $id => $visible)
		{
			if ($visible)
			{
				$recalc[] = $id;
			}
		}

		XenForo_Db::beginTransaction($db);

		$db->query("
			DELETE FROM xf_tag_content
			WHERE content_type = ?
				AND content_id = ?
		", array($contentType, $contentId));

		$this->recalculateTagUsage($recalc);

		XenForo_Db::commit($db);
	}

	public function recalculateTagUsageByContentTagged($contentType, $contentId)
	{
		$tagIds = $this->_getDb()->fetchCol("
			SELECT tag_id
			FROM xf_tag_content
			WHERE content_type = ?
				AND content_id = ?
		", array($contentType, $contentId));
		$this->recalculateTagUsage($tagIds);
	}

	public function recalculateTagUsage($tagIds)
	{
		if (!$tagIds)
		{
			return;
		}

		if (!is_array($tagIds))
		{
			$tagIds = array($tagIds);
		}

		$db = $this->_getDb();

		$tags = $db->fetchPairs("
			SELECT tag_id, permanent
			FROM xf_tag
			WHERE tag_id IN (" . $db->quote($tagIds) . ")
		");
		$results = $this->fetchAllKeyed("
			SELECT tag_id,
				COUNT(IF(visible, 1, NULL)) AS use_count,
				COUNT(*) AS raw_use_count,
				MAX(IF(visible, add_date, 0)) AS last_use_date
			FROM xf_tag_content
			WHERE tag_id IN (" . $db->quote($tagIds) . ")
			GROUP BY tag_id
		", 'tag_id');

		XenForo_Db::beginTransaction($db);

		foreach ($tags AS $tagId => $permanent)
		{
			$delete = false;

			if (isset($results[$tagId]))
			{
				$result = $results[$tagId];
				if (!$result['use_count'] && !$result['raw_use_count'])
				{
					// this shouldn't actually happen since there shouldn't be a row
					$delete = true;
				}
				else
				{
					$db->update('xf_tag', array(
						'use_count' => $result['use_count'],
						'last_use_date' => $result['last_use_date']
					), 'tag_id = ' . $db->quote($tagId));
				}
			}
			else
			{
				$delete = true;
			}

			if ($delete)
			{
				if ($permanent)
				{
					$db->update('xf_tag', array(
						'use_count' => 0,
						'last_use_date' => 0
					), 'tag_id = ' . $db->quote($tagId));
				}
				else
				{
					$db->delete('xf_tag', 'tag_id = ' . $db->quote($tagId));
				}
			}
		}

		XenForo_Db::commit($db);
	}

	public function mergeTags($sourceTagId, $targetTagId)
	{
		$db = $this->_getDb();

		XenForo_Db::beginTransaction($db);

		$db->query("
			UPDATE IGNORE xf_tag_content
			SET tag_id = ?
			WHERE tag_id = ?
		", array($targetTagId, $sourceTagId));
		$db->query("DELETE FROM xf_tag_result_cache WHERE tag_id = ?", $targetTagId);

		// this handles cases where the content already had the target tag
		$db->query("DELETE FROM xf_tag WHERE tag_id = ?", $sourceTagId);
		$db->query("DELETE FROM xf_tag_content WHERE tag_id = ?", $sourceTagId);

		$this->recalculateTagUsage($targetTagId);

		XenForo_Db::commit($db);

		XenForo_Application::defer('TagRecache', array(
			'tagId' => $targetTagId
		), 'tagUpdate' . $targetTagId, true);
	}

	public function getTagResultsCache($tagId, $userId = null)
	{
		if ($userId === null)
		{
			$userId = XenForo_Visitor::getUserId();
		}

		return $this->_getDb()->fetchRow("
			SELECT *
			FROM xf_tag_result_cache
			WHERE tag_id = ?
				AND user_id = ?
				AND expiry_date > ?
		", array($tagId, $userId, XenForo_Application::$time));
	}

	public function insertTagResultsCache($tagId, array $results, $userId = null)
	{
		if ($userId === null)
		{
			$userId = XenForo_Visitor::getUserId();
		}

		$expiry = XenForo_Application::$time + 60*60;

		$this->_getDb()->query("
			INSERT INTO xf_tag_result_cache
				(tag_id, user_id, cache_date, expiry_date, results)
			VALUES
				(?, ?, ?, ?, ?)
			ON DUPLICATE KEY UPDATE
				cache_date = VALUES(cache_date),
				expiry_date = VALUES(expiry_date),
				results = VALUES(results)
		", array($tagId, $userId, XenForo_Application::$time, $expiry, json_encode($results)));
	}

	public function pruneTagResultsCache($cutOff = null)
	{
		if ($cutOff === null)
		{
			$cutOff = XenForo_Application::$time;
		}

		$this->_getDb()->delete('xf_tag_result_cache', 'expiry_date <= ' . intval($cutOff));
	}

	/**
	 * Groups tag results by the content type they belong to.
	 *
	 * @param array $results Format: [] => array(content type, content id)
	 *
	 * @return array Format: [content type][content id] => content id
	 */
	public function groupTagResultsByType(array $results)
	{
		$resultsGrouped = array();
		foreach ($results AS $result)
		{
			$resultsGrouped[$result[self::CONTENT_TYPE]][$result[self::CONTENT_ID]] = $result[self::CONTENT_ID];
		}

		return $resultsGrouped;
	}

	/**
	 * Gets the data for the tag results that are actually viewable. If no
	 * data is returned, the result is not viewable and should be hidden.
	 *
	 * @param array $resultsGrouped Tag results, grouped by type (see {@link groupTagResultsByType()})
	 * @param array $handlers Tag handler objects for all necessary content types
	 * @param boolean $prepareData True if the data should be prepared as well
	 * @param array|null $viewingUser Information about the viewing user (keys: user_id, permission_combination_id, permissions) or null for visitor
	 *
	 * @return array Result data grouped, format: [content type][content id] => data
	 */
	public function getViewableTagResultData(array $resultsGrouped, array $handlers, $prepareData = true, array $viewingUser = null)
	{
		$this->standardizeViewingUserReference($viewingUser);

		$dataGrouped = array();
		foreach ($handlers AS $contentType => $handler)
		{
			if (!isset($resultsGrouped[$contentType]))
			{
				continue;
			}

			$dataResults = $handler->getDataForResults($resultsGrouped[$contentType], $viewingUser, $resultsGrouped);
			foreach ($dataResults AS $dataId => $data)
			{
				if (!$handler->canViewResult($data, $viewingUser))
				{
					unset($dataResults[$dataId]);
					continue;
				}

				if ($prepareData)
				{
					$dataResults[$dataId] = $handler->prepareResult($data, $viewingUser);
				}
			}

			$dataGrouped[$contentType] = $dataResults;
		}

		return $dataGrouped;
	}

	/**
	 * Filters a list of tag results to those that are viewable.
	 *
	 * @param array $results Tag results ([] => array(content type, content id)
	 * @param array|null $viewingUser Information about the viewing user (keys: user_id, permission_combination_id, permissions) or null for visitor
	 * @param array $preparableData Returns data which can be prepared and then used for display (keys: results, handlers)
	 *
	 * @return array Same as input results, but unviewable entries removed
	 */
	public function getViewableTagResults(array $results, array $viewingUser = null, &$preparableData = null)
	{
		$resultsGrouped = $this->groupTagResultsByType($results);
		$handlers = $this->getTagHandlers(array_keys($resultsGrouped));

		$dataGrouped = $this->getViewableTagResultData($resultsGrouped, $handlers, false, $viewingUser);

		foreach ($results AS $resultId => $result)
		{
			if (!isset($dataGrouped[$result[self::CONTENT_TYPE]][$result[self::CONTENT_ID]]))
			{
				unset($results[$resultId]);
			}
		}

		$preparableData = array(
			'results' => $dataGrouped,
			'handlers' => $handlers
		);

		return $results;
	}

	/**
	 * Gets the tag results ready for display (using the handlers).
	 * The results (in the returned "results" key) have extra, type-specific data
	 * included with them.
	 *
	 * @param array $results Tag results ([] => array(content type, content id)
	 * @param array|null $viewingUser Information about the viewing user (keys: user_id, permission_combination_id, permissions) or null for visitor
	 *
	 * @return array Keys: results, handlers
	 */
	public function getTagResultsForDisplay(array $results, array $viewingUser = null)
	{
		$resultsGrouped = $this->groupTagResultsByType($results);
		$handlers = $this->getTagHandlers(array_keys($resultsGrouped));

		$dataGrouped = $this->getViewableTagResultData($resultsGrouped, $handlers, true, $viewingUser);

		foreach ($results AS $resultId => $result)
		{
			if (isset($dataGrouped[$result[self::CONTENT_TYPE]][$result[self::CONTENT_ID]]))
			{
				$results[$resultId]['content'] = $dataGrouped[$result[self::CONTENT_TYPE]][$result[self::CONTENT_ID]];
			}
			else
			{
				unset($results[$resultId]);
			}
		}

		if (!$results)
		{
			return false;
		}

		return array(
			'results' => $results,
			'handlers' => $handlers
		);
	}

	public function finalizeUnpreparedResults(array $unpreparedResults, array $pageResultIds, array $viewingUser = null)
	{
		$finalResults = array();
		$results = $unpreparedResults['results'];
		$handlers = $unpreparedResults['handlers'];

		$this->standardizeViewingUserReference($viewingUser);

		foreach ($pageResultIds AS $key => $pageResult)
		{
			$type = $pageResult[self::CONTENT_TYPE];
			$id = $pageResult[self::CONTENT_ID];

			if (!isset($results[$type][$id]) || !isset($handlers[$type]))
			{
				continue;
			}

			$result = $results[$type][$id];
			$handler = $handlers[$type];

			$finalResults[$key] = array(
				self::CONTENT_TYPE => $type,
				self::CONTENT_ID => $id,
				'content' => $handler->prepareResult($result, $viewingUser)
			);
		}

		return array(
			'results' => $finalResults,
			'handlers' => $handlers
		);
	}

	/**
	 * Returns the slice of tag results for the requested page.
	 *
	 * @param array $tagCache Tag cache, containing results
	 * @param integer $page
	 * @param integer $perPage
	 *
	 * @return array Results for the specified page
	 */
	public function sliceTagResultsToPage(array $tagCache, $page, $perPage)
	{
		if ($page < 1)
		{
			$page = 1;
		}

		if (!isset($tagCache['resultsCache']))
		{
			$tagCache['resultsCache'] = json_decode($tagCache['results'], true);
		}

		return array_slice($tagCache['resultsCache'], ($page - 1) * $perPage, $perPage);
	}

	/**
	 * @param string $contentType
	 * @return XenForo_TagHandler_Tagger
	 *
	 * @throws Exception
	 */
	public function getTagger($contentType)
	{
		$handler = $this->getTagHandler($contentType);
		if (!$handler)
		{
			throw new InvalidArgumentException("Unknown content type '$contentType'");
		}

		$class = XenForo_Application::resolveDynamicClass('XenForo_TagHandler_Tagger');
		return new $class($handler, $this);
	}

	/**
	 * @param string $contentType
	 *
	 * @return XenForo_TagHandler_Abstract|null
	 */
	public function getTagHandler($contentType)
	{
		$handlerClass = $this->getContentTypeField($contentType, 'tag_handler_class');
		if (!$handlerClass || !class_exists($handlerClass))
		{
			return null;
		}

		$handlerClass = XenForo_Application::resolveDynamicClass($handlerClass);
		return new $handlerClass($contentType);
	}

	/**
	 * @param array $contentTypes
	 *
	 * @return XenForo_TagHandler_Abstract[]
	 */
	public function getTagHandlers(array $contentTypes)
	{
		$handlers = array();
		foreach ($contentTypes AS $contentType)
		{
			$handler = $this->getTagHandler($contentType);
			if ($handler)
			{
				$handlers[$contentType] = $handler;
			}
		}

		return $handlers;
	}
}