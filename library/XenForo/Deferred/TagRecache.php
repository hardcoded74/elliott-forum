<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 ?><?php

class XenForo_Deferred_TagRecache extends XenForo_Deferred_Abstract
{
	public function execute(array $deferred, array $data, $targetRunTime, &$status)
	{
		$data = array_merge(array(
			'tagId' => null,
			'position' => 0,
			'deleteFirst' => false
		), $data);

		if (!$data['tagId'])
		{
			return false;
		}

		$db = XenForo_Application::getDb();

		$matches = $db->fetchAll("
			SELECT tag_content_id, content_type, content_id
			FROM xf_tag_content
			WHERE tag_id = ?
				AND tag_content_id > ?
			ORDER BY tag_content_id
			LIMIT 1000
		", array($data['tagId'], $data['position']));
		if (!$matches)
		{
			return false;
		}

		/** @var XenForo_Model_Tag $tagModel */
		$tagModel = XenForo_Model::create('XenForo_Model_Tag');

		XenForo_Db::beginTransaction($db);

		$limitTime = ($targetRunTime > 0);
		$s = microtime(true);

		foreach ($matches AS $match)
		{
			$data['position'] = $match['tag_content_id'];

			if ($data['deleteFirst'])
			{
				$db->delete('xf_tag_content', 'tag_content_id = ' . $match['tag_content_id']);
			}

			$tagModel->rebuildTagCache($match['content_type'], $match['content_id']);

			if ($limitTime && microtime(true) - $s >= $targetRunTime)
			{
				break;
			}
		}

		XenForo_Db::commit($db);

		$actionPhrase = new XenForo_Phrase('rebuilding');
		$typePhrase = new XenForo_Phrase('tags');
		$status = sprintf('%s... %s (%s)', $actionPhrase, $typePhrase, XenForo_Locale::numberFormat($data['position']));

		return $data;
	}
}