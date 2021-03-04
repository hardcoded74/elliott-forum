<?php

/**
* Data writer for thread prefixes.
*/
class XenForo_DataWriter_Tag extends XenForo_DataWriter
{
	/**
	 * Title of the phrase that will be created when a call to set the
	 * existing data fails (when the data doesn't exist).
	 *
	 * @var string
	 */
	protected $_existingDataErrorPhrase = 'requested_tag_not_found';

	/**
	* Gets the fields that are defined for the table. See parent for explanation.
	*
	* @return array
	*/
	protected function _getFields()
	{
		return array(
			'xf_tag' => array(
				'tag_id'        => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
				'tag'           => array('type' => self::TYPE_STRING, 'required' => true, 'maxLength' => 100,
					'verification' => array('$this', '_verifyTag')
				),
				'tag_url'       => array('type' => self::TYPE_STRING, 'required' => true, 'maxLength' => 100,
					'verification' => array('$this', '_verifyTagUrl')
				),
				'use_count'     => array('type' => self::TYPE_UINT_FORCED, 'default' => 0),
				'last_use_date' => array('type' => self::TYPE_UINT, 'default' => 0),
				'permanent'     => array('type' => self::TYPE_BOOLEAN, 'default' => 0)
			)
		);
	}

	/**
	* Gets the actual existing data out of data that was passed in. See parent for explanation.
	*
	* @param mixed
	*
	* @return array|false
	*/
	protected function _getExistingData($data)
	{
		if (!$id = $this->_getExistingPrimaryKey($data, 'tag_id'))
		{
			return false;
		}

		return array('xf_tag' => $this->_getTagModel()->getTagById($id));
	}

	/**
	* Gets SQL condition to update the existing record.
	*
	* @return string
	*/
	protected function _getUpdateCondition($tableName)
	{
		return 'tag_id = ' . $this->_db->quote($this->getExisting('tag_id'));
	}

	protected function _verifyTag(&$tag)
	{
		$tag = $this->_getTagModel()->normalizeTag($tag);
		if (!strlen($tag))
		{
			return true; // will error later
		}

		$existing = $this->_getTagModel()->getTag($tag);
		if ($existing && $existing['tag_id'] != $this->get('tag_id'))
		{
			$this->error(new XenForo_Phrase('tags_must_be_unique'), 'tag');
			return false;
		}

		return true;
	}

	protected function _verifyTagUrl(&$tagUrl)
	{
		if (!strlen($tagUrl))
		{
			return true; // will be generated later
		}

		if (preg_match('/[^a-zA-Z0-9_-]/', $tagUrl))
		{
			$this->error(new XenForo_Phrase('please_enter_an_id_using_only_alphanumeric'), 'tag_url');
			return false;
		}

		$existing = $this->_getTagModel()->getTagByUrl($tagUrl);
		if ($existing && $existing['tag_id'] != $this->get('tag_id'))
		{
			$this->error(new XenForo_Phrase('tag_url_versions_must_be_unique'), 'tag_url');
			return false;
		}

		return true;
	}

	protected function _preSave()
	{
		$url = $this->get('tag_url');
		if (!is_string($url) || !strlen($url))
		{
			$this->set('tag_url', $this->_getUrlVersion($this->get('tag')),
				'', array('runVerificationCallback' => false)
			);
		}
	}

	protected function _getUrlVersion($tag)
	{
		$urlVersion = preg_replace('/[^a-zA-Z0-9_ -]/', '', utf8_romanize(utf8_deaccent($tag)));
		$urlVersion = preg_replace('/[ -]+/', '-', $urlVersion);

		$db = $this->_db;

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

	protected function _postSave()
	{
		if ($this->isUpdate() && ($this->isChanged('tag') || $this->isChanged('tag_url')))
		{
			// this will update the content and trigger search index changes as necessary
			XenForo_Application::defer('TagRecache', array(
				'tagId' => $this->get('tag_id')
			), 'tagUpdate' . $this->get('tag_id'), true);
		}
	}

	protected function _postDelete()
	{
		// this will handle cleaning up the data in tag content -- we need it to know which bits to rebuild
		XenForo_Application::defer('TagRecache', array(
			'tagId' => $this->get('tag_id'),
			'deleteFirst' => true
		), 'tagDelete' . $this->get('tag_id'), true);
	}

	/**
	 * @return XenForo_Model_Tag
	 */
	protected function _getTagModel()
	{
		return $this->getModelFromCache('XenForo_Model_Tag');
	}
}