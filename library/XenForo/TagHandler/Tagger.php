<?php

class XenForo_TagHandler_Tagger
{
	/**
	 * @var XenForo_TagHandler_Abstract
	 */
	protected $_handler;

	/**
	 * @var XenForo_Model_Tag
	 */
	protected $_tagModel;

	protected $_contentId;

	protected $_existingTags = array();

	protected $_permissions = null;
	protected $_contextSet = false;

	protected $_addTags = array();
	protected $_newTags = array();
	protected $_removeTags = array();
	protected $_invalidNewTags = array();

	public function __construct(XenForo_TagHandler_Abstract $handler, XenForo_Model_Tag $tagModel)
	{
		$this->_handler = $handler;
		$this->_tagModel = $tagModel;
	}

	public function setPermissionsFromContext(array $context, array $parentContext = null)
	{
		$this->_permissions = array_merge(
			$this->getPermissions(),
			$this->_handler->getPermissionsFromContext($context, $parentContext)
		);
		$this->_contextSet = true;

		return $this;
	}

	public function getPermissions()
	{
		if (!$this->_permissions)
		{
			$this->_permissions = $this->_getDefaultPermissions();
		}

		return $this->_permissions;
	}

	public function getPermission($key)
	{
		$permissions = $this->getPermissions();
		return $permissions[$key];
	}

	protected function _getDefaultPermissions()
	{
		$visitor = XenForo_Visitor::getInstance();
		$options = XenForo_Application::getOptions();

		return array(
			'edit' => $options->enableTagging,
			'create' => $visitor->hasPermission('general', 'createTag'),
			'removeOthers' => false,
			'maxUser' => $visitor->hasPermission('general', 'bypassUserTagLimit') ? 0 : $options->maxContentTagsPerUser,
			'maxTotal' => $options->maxContentTags,
			'minTotal' => 0
		);
	}

	public function setContent($id, $newlyCreated = false)
	{
		if (!$id)
		{
			throw new Exception("Must provide content");
		}

		$this->_contentId = $id;
		if ($newlyCreated)
		{
			$this->_existingTags = array();
		}
		else
		{
			if ($this->_newTags || $this->_addTags || $this->_removeTags)
			{
				throw new Exception("Content must be set before you attempt to manipulate tags");
			}

			$this->_existingTags = $this->_tagModel->getTagsForContent(
				$this->_handler->getContentType(), $id
			);
		}

		return $this;
	}

	public function addTags(array $tags)
	{
		if (!$this->_contextSet)
		{
			throw new Exception("Permissions context has not been set");
		}

		$addTags = $this->_tagModel->getTags($tags, $createTags);
		foreach ($addTags AS $tag)
		{
			$id = $tag['tag_id'];
			if (isset($this->_existingTags[$id]) && !isset($this->_removeTags[$id]))
			{
				continue;
			}

			$this->_addTags[$id] = $tag['tag'];
		}

		foreach ($createTags AS $tag)
		{
			// this tag doesn't exist, so it can't be in existing or removed
			$this->_newTags[$tag] = $tag;

			if (!$this->_tagModel->isValidTag($tag))
			{
				// still adding this to the new tags list so we have a record
				$this->_invalidNewTags[$tag] = $tag;
			}
		}

		return $this;
	}

	public function removeTags(array $tags, $ignoreNonRemovable = true)
	{
		if (!$this->_contextSet)
		{
			throw new Exception("Permissions context has not been set");
		}

		$userId = XenForo_Visitor::getUserId();

		$found = $this->_tagModel->getFoundTagsInList($tags, $this->_existingTags);
		foreach ($found AS $tag)
		{
			if ($ignoreNonRemovable && !$this->_permissions['removeOthers'] && $tag['add_user_id'] != $userId)
			{
				// can't remove, just ignore
				continue;
			}
			
			$this->_removeTags[$tag['tag_id']] = $tag['tag'];
		}

		return $this;
	}

	public function setTags(array $tags, $ignoreNonRemovable = true)
	{
		if (!$this->_contextSet)
		{
			throw new Exception("Permissions context has not been set");
		}

		$removeExisting = $this->_existingTags;

		$addTags = $this->_tagModel->getTags($tags, $createTags);
		foreach ($addTags AS $tag)
		{
			$id = $tag['tag_id'];
			if (isset($this->_existingTags[$id]) && !isset($this->_removeTags[$id]))
			{
				unset($removeExisting[$id]);
				continue;
			}

			$this->_addTags[$id] = $tag['tag'];
		}

		foreach ($createTags AS $tag)
		{
			// this tag doesn't exist, so it can't be in existing or removed
			$this->_newTags[$tag] = $tag;

			if (!$this->_tagModel->isValidTag($tag))
			{
				// still adding this to the new tags list so we have a record
				$this->_invalidNewTags[$tag] = $tag;
			}
		}

		$userId = XenForo_Visitor::getUserId();

		foreach ($removeExisting AS $id => $tag)
		{
			if ($ignoreNonRemovable && !$this->_permissions['removeOthers'] && $tag['add_user_id'] != $userId)
			{
				// can't remove, just ignore
				continue;
			}

			$this->_removeTags[$tag['tag_id']] = $tag['tag'];
		}

		return $this;
	}

	public function getErrors()
	{
		$errors = array();
		$permissions = $this->getPermissions();
		$userId = XenForo_Visitor::getUserId();

		$totalTags = 0;
		$totalUser = 0;

		if (!$permissions['edit'])
		{
			$errors['edit'] = new XenForo_Phrase('do_not_have_permission');
		}

		if ($this->_newTags && !$permissions['create'])
		{
			$errors['create'] = new XenForo_Phrase('you_may_not_create_new_tags_please_change_x', array('tags' => implode(', ', $this->_newTags)));
		}
		if ($this->_invalidNewTags && $permissions['create'])
		{
			$errors['invalidCreate'] = new XenForo_Phrase('some_tags_not_valid_please_change_x', array('tags' => implode(', ', $this->_invalidNewTags)));
		}

		foreach ($this->_existingTags AS $id => $tag)
		{
			if (isset($this->_removeTags[$id]))
			{
				continue;
			}

			$totalTags++;
			if ($tag['add_user_id'] == $userId)
			{
				$totalUser++;
			}
		}

		foreach ($this->_addTags AS $tag)
		{
			$totalTags++;
			$totalUser++;
		}
		foreach ($this->_newTags AS $tag)
		{
			$totalTags++;
			$totalUser++;
		}

		$removeFail = array();
		foreach ($this->_removeTags AS $id => $tag)
		{
			if (!isset($this->_existingTags[$id]))
			{
				continue;
			}

			$existing = $this->_existingTags[$id];

			if (!$permissions['removeOthers'] && $existing['add_user_id'] != $userId)
			{
				$removeFail[] = $tag;
			}
			// removed tags are already ignored for totals
		}
		if ($removeFail)
		{
			$errors['create'] = new XenForo_Phrase('you_may_not_remove_following_tags_x', array('tags' => implode(', ', $removeFail)));
		}

		if ($permissions['maxUser'] > 0 && $totalUser > $permissions['maxUser'])
		{
			$errors['maxUser'] = new XenForo_Phrase('you_may_only_apply_x_tags_to_this_content', array('count' => $permissions['maxUser']));
		}
		if ($permissions['maxTotal'] > 0 && $totalTags > $permissions['maxTotal'])
		{
			$errors['maxTotal'] = new XenForo_Phrase('this_content_may_only_have_x_tags_in_total', array('count' => $permissions['maxTotal']));
		}

		$minRequired = $permissions['minTotal'];
		if ($permissions['maxUser'] > 0 && $permissions['maxUser'] < $minRequired)
		{
			// if the user can only add 1 tag but you require 3, they could never continue
			$minRequired = $permissions['maxUser'];
		}
		if ($totalTags < $minRequired)
		{
			$errors['minTotal'] = new XenForo_Phrase('this_content_must_have_at_least_x_tags', array('min' => $minRequired));
		}

		return $errors;
	}

	public function save()
	{
		if (!$this->_contentId)
		{
			throw new Exception("Cannot save without a content ID");
		}
		if ($this->getErrors())
		{
			throw new Exception("Cannot save when there are tagging errors");
		}

		$db = XenForo_Application::getDb();
		XenForo_Db::beginTransaction($db);

		foreach ($this->_newTags AS $tag)
		{
			$id = $this->_tagModel->createTag($tag);
			$this->_addTags[$id] = $tag;
		}

		$cache = $this->_tagModel->adjustContentTags(
			$this->_handler->getContentType(), $this->_contentId,
			array_keys($this->_addTags), array_keys($this->_removeTags)
		);

		XenForo_Db::commit($db);

		return $cache;
	}
}