<?php

/**
* Data writer for profile posts.
*
* @package XenForo_ProfilePost
*/
class XenForo_DataWriter_DiscussionMessage_ProfilePost extends XenForo_DataWriter_DiscussionMessage
{
	const DATA_PROFILE_USER = 'profileUser';

	const OPTION_MAX_TAGGED_USERS = 'maxTaggedUsers';

	protected $_taggedUsers = array();

	/**
	 * Gets the object that represents the definition of this type of message.
	 *
	 * @return XenForo_DiscussionMessage_Definition_Abstract
	 */
	public function getDiscussionMessageDefinition()
	{
		return new XenForo_DiscussionMessage_Definition_ProfilePost();
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
		if (!$profilePostId = $this->_getExistingPrimaryKey($data))
		{
			return false;
		}

		return array($this->getDiscussionMessageTableName() => $this->_getProfilePostModel()->getProfilePostById($profilePostId));
	}

	/**
	* Gets the fields that are defined for the table. See parent for explanation.
	*
	* @return array
	*/
	protected function _getFields()
	{
		$fields = $this->_getCommonFields();

		$structure = $this->_messageDefinition->getMessageStructure();

		$fields[$structure['table']] += array(
			'comment_count'      => array('type' => self::TYPE_UINT_FORCED, 'default' => 0),
			'first_comment_date' => array('type' => self::TYPE_UINT, 'default' => 0),
			'last_comment_date'  => array('type' => self::TYPE_UINT, 'default' => 0),
			'latest_comment_ids' => array('type' => self::TYPE_SERIALIZED, 'default' => 'a:0:{}')
		);

		return $fields;
	}

	/**
	* Gets the default set of options for this data writer.
	*
	* @return array
	*/
	protected function _getDefaultOptions()
	{
		$options = parent::_getDefaultOptions();
		$options[self::OPTION_MAX_MESSAGE_LENGTH] = 420;
		$options[self::OPTION_MAX_TAGGED_USERS] = 0;

		return $options;
	}

	protected function _publishAndNotify()
	{
		parent::_publishAndNotify();

		// send an alert if the message is visible, is not posted on the user's own profile
		// is an insert, or is an update with message state changed from 'moderated'

		$isAlertable = (
			$this->get('message_state') == 'visible'
			&& ($this->isInsert() || $this->getExisting('message_state') == 'moderated')
		);
		if ($isAlertable)
		{
			$alerted = $this->_alertUser();

			$maxTagged = $this->getOption(self::OPTION_MAX_TAGGED_USERS);
			if ($maxTagged && $this->_taggedUsers)
			{
				if ($maxTagged > 0)
				{
					$alertTagged = array_slice($this->_taggedUsers, 0, $maxTagged, true);
				}
				else
				{
					$alertTagged = $this->_taggedUsers;
				}

				if (!$profileUser = $this->getExtraData(self::DATA_PROFILE_USER))
				{
					$profileUser = $this->_getUserModel()->getUserById($this->get('profile_user_id'), array(
						'join' => XenForo_Model_User::FETCH_USER_FULL
					));
				}

				$profilePost = $this->getMergedData();
				$this->_getProfilePostModel()->alertTaggedMembers(
					$profilePost, $profileUser, $alertTagged, $alerted
				);
			}
		}
	}

	/**
	 * Sends an alert to the profile owner
	 *
	 * Note: ensure that you $dw->setExtraData(self::PROFILE_USER, $profileUser) to save a query
	 *
	 * @return array List of user IDs alerted
	 */
	protected function _alertUser()
	{
		if ($this->get('profile_user_id') == $this->get('user_id'))
		{
			return array();
		}

		$userModel = $this->_getUserModel();
		$alerted = array();

		if (!$profileUser = $this->getExtraData(self::DATA_PROFILE_USER))
		{
			$profileUser = $userModel->getUserById($this->get('profile_user_id'), array(
				'join' => XenForo_Model_User::FETCH_USER_FULL
			));
		}

		if (!$userModel->isUserIgnored($profileUser, $this->get('user_id'))
			&& XenForo_Model_Alert::userReceivesAlert($profileUser, $this->getContentType(), 'insert')
		)
		{
			XenForo_Model_Alert::alert(
				$this->get('profile_user_id'),
				$this->get('user_id'),
				$this->get('username'),
				$this->getContentType(),
				$this->get('profile_post_id'),
				'insert'
			);

			$alerted[] = $this->get('profile_user_id');
		}

		return $alerted;
	}

	protected function _messagePreSave()
	{
		if ($this->get('user_id') == $this->get('profile_user_id') && $this->isChanged('message'))
		{
			// statuses are more limited than other posts
			$message = $this->get('message');
			$maxLength = 140;

			$message = preg_replace('/\r?\n/', ' ', $message);

			if (utf8_strlen($message) > $maxLength)
			{
				$this->error(new XenForo_Phrase('please_enter_message_with_no_more_than_x_characters', array('count' => $maxLength)), 'message');
			}

			$this->set('message', $message);
		}

		// do this auto linking after length counting
		/** @var $taggingModel XenForo_Model_UserTagging */
		$taggingModel = $this->getModelFromCache('XenForo_Model_UserTagging');

		$this->_taggedUsers = $taggingModel->getTaggedUsersInMessage(
			$this->get('message'), $newMessage, 'text'
		);
		$this->set('message', $newMessage);
	}

	protected function _updateUserMessageCount($isDelete = false)
	{
		// disable message counting for profile posts - people are just going to get confused
		// by this, plus the messages are basically one liners
	}

	protected function _messagePostSave()
	{
		if ($this->isChanged('message_state') && $this->get('message_state') == 'visible')
		{
			$this->_updateStatus();
		}
		else if ($this->isUpdate() && $this->isChanged('message_state') && $this->getExisting('message_state') == 'visible')
		{
			$this->_hideStatus();
		}

		if ($this->isUpdate() && $this->get('message_state') == 'deleted' && $this->getExisting('message_state') == 'visible')
		{
			$this->getModelFromCache('XenForo_Model_Alert')->deleteAlerts('profile_post', $this->get('profile_post_id'));
		}

		if ($this->isUpdate() && $this->isStatus() && $this->get('message_state') == 'visible' && $this->isChanged('message'))
		{
			$userDw = XenForo_DataWriter::create('XenForo_DataWriter_User', XenForo_DataWriter::ERROR_SILENT);
			$userDw->setExistingData($this->get('user_id'));
			if ($userDw->get('status_profile_post_id') == $this->get('profile_post_id'))
			{
				$userDw->set('status', $this->get('message'));
				$userDw->save();
			}
		}

		if ($this->isChanged('message_state') && $this->isUpdate())
		{
			$this->_updateUserLikeCountForComments();
		}
	}

	protected function _messagePostDelete()
	{
		$this->_hideStatus();

		$this->getModelFromCache('XenForo_Model_Alert')->deleteAlerts('profile_post', $this->get('profile_post_id'));

		$this->_deleteProfilePostComments();
	}

	protected function _deleteProfilePostComments()
	{
		$comments = $this->_getProfilePostComments();
		if (!$comments)
		{
			return;
		}
		$commentIds = array_keys($comments);

		$this->_db->delete('xf_profile_post_comment',
			'profile_post_comment_id IN (' . $this->_db->quote($commentIds) . ')'
		);

		$this->getModelFromCache('XenForo_Model_DeletionLog')->removeDeletionLog(
			'profile_post_comment', $commentIds
		);
		$this->getModelFromCache('XenForo_Model_ModerationQueue')->deleteFromModerationQueue(
			'profile_post_comment', $commentIds
		);

		$visibleCommentIds = array();
		$nonVisibleCommentIds = array();
		foreach ($comments AS $commentId => $comment)
		{
			if (empty($comment['message_state']) || $comment['message_state'] == 'visible')
			{
				$visibleCommentIds[] = $commentId;
			}
			else
			{
				$nonVisibleCommentIds[] = $commentId;
			}
		}
		$this->getModelFromCache('XenForo_Model_Like')->deleteContentLikes(
			'profile_post_comment', $visibleCommentIds, ($this->get('message_state') == 'visible')
		);
		$this->getModelFromCache('XenForo_Model_Like')->deleteContentLikes(
			'profile_post_comment', $nonVisibleCommentIds, false
		);
	}

	/**
	 * Updates the user like count.
	 *
	 * @param boolean $isDelete True if hard deleting the message
	 */
	protected function _updateUserLikeCountForComments($isDelete = false)
	{
		if ($this->get('message_state') == 'visible'
			&& $this->getExisting('message_state') != 'visible'
		)
		{
			$updateType = 'add';
		}
		else if ($this->getExisting('message_state') == 'visible'
			&& ($this->get('message_state') != 'visible' || $isDelete)
		)
		{
			$updateType = 'subtract';
		}
		else
		{
			return;
		}

		$users = $this->_getUserLikeCountAdjustments();

		foreach ($users AS $userId => $modify)
		{
			if ($updateType == 'add')
			{
				$this->_db->query('
					UPDATE xf_user
					SET like_count = like_count + ?
					WHERE user_id = ?
				', array($modify, $userId));
			}
			else
			{
				$this->_db->query('
					UPDATE xf_user
					SET like_count = IF(like_count > ?, like_count - ?, 0)
					WHERE user_id = ?
				', array($modify, $modify, $userId));
			}
		}
	}

	protected function _getUserLikeCountAdjustments()
	{
		$users = array();

		foreach ($this->_getProfilePostComments() AS $comment)
		{
			if ($comment['likes'] && $comment['message_state'] == 'visible' && $comment['user_id'])
			{
				if (isset($users[$comment['user_id']]))
				{
					$users[$comment['user_id']] += $comment['likes'];
				}
				else
				{
					$users[$comment['user_id']] = $comment['likes'];
				}
			}
		}

		return $users;
	}

	/**
	 * Inserts or updates a record in the search index for this message.
	 */
	protected function _insertOrUpdateSearchIndex()
	{
		parent::_insertOrUpdateSearchIndex();

		if ($this->isUpdate() && $this->getExisting('message_state') != 'visible')
		{
			XenForo_Application::defer('SearchIndexPartial', array(
				'contentType' => 'profile_post_comment',
				'contentIds' => array_keys($this->_getProfilePostComments())
			));
		}
	}

	/**
	 * Deletes this message from the search index.
	 */
	protected function _deleteFromSearchIndex()
	{
		$indexer = new XenForo_Search_Indexer();
		$dataHandler = XenForo_Search_DataHandler_Abstract::create(
			'XenForo_Search_DataHandler_ProfilePostComment'
		);
		$dataHandler->deleteFromIndex($indexer, array_keys($this->_getProfilePostComments()));

		parent::_deleteFromSearchIndex();
	}

	protected function _getProfilePostComments()
	{
		return $this->_getProfilePostModel()->getProfilePostComments(array(
			'profile_post_id' => $this->get('profile_post_id')
		));
	}

	protected function _updateStatus()
	{
		if (!$this->isStatus())
		{
			return;
		}

		$this->_db->query('
			INSERT INTO xf_user_status
				(profile_post_id, user_id, post_date)
			VALUES
				(?, ?, ?)
			ON DUPLICATE KEY UPDATE
				user_id = VALUES(user_id),
				post_date = VALUES(post_date)
		', array($this->get('profile_post_id'), $this->get('user_id'), $this->get('post_date')));

		$userDw = XenForo_DataWriter::create('XenForo_DataWriter_User', XenForo_DataWriter::ERROR_SILENT);
		$userDw->setExistingData($this->get('user_id'));
		if ($this->get('post_date') >= $userDw->get('status_date'))
		{
			$userDw->set('status', $this->get('message'));
			$userDw->set('status_date', $this->get('post_date'));
			$userDw->set('status_profile_post_id', $this->get('profile_post_id'));
			$userDw->save();
		}
	}

	protected function _hideStatus()
	{
		if (!$this->isStatus())
		{
			return;
		}

		$this->_db->delete('xf_user_status', 'profile_post_id = ' . $this->_db->quote($this->get('profile_post_id')));

		$userDw = XenForo_DataWriter::create('XenForo_DataWriter_User');
		$userDw->setExistingData($this->get('user_id'));
		if ($userDw->get('status_profile_post_id') == $this->get('profile_post_id'))
		{
			$userDw->set('status', '');
			$userDw->set('status_date', 0);
			$userDw->set('status_profile_post_id', 0);
			$userDw->save();
		}
	}

	/**
	 * Returns true if this message is a status update.
	 *
	 * @return boolean
	 */
	public function isStatus()
	{
		return ($this->get('user_id') == $this->get('profile_user_id'));
	}

	public function rebuildProfilePostCommentCounters()
	{
		$db = $this->_db;
		$profilePostId = $this->get('profile_post_id');

		$counts = $db->fetchRow('
			SELECT COUNT(*) AS comment_count,
				MIN(comment_date) AS first_comment_date,
				MAX(comment_date) AS last_comment_date
			FROM xf_profile_post_comment
			WHERE profile_post_id = ?
				AND message_state = \'visible\'
		', $profilePostId);

		$cache = $this->_getProfilePostModel()->getProfilePostCommentIdsForCache($this->get('profile_post_id'));

		$this->bulkSet($counts);
		$this->set('latest_comment_ids', serialize($cache));
	}

	public function insertNewComment($commentId, $commentDate)
	{
		$this->set('comment_count', $this->get('comment_count') + 1);
		if (!$this->get('first_comment_date') || $commentDate < $this->get('first_comment_date'))
		{
			$this->set('first_comment_date', $commentDate);
		}
		$this->set('last_comment_date', max($this->get('last_comment_date'), $commentDate));

		$cache = $this->_getProfilePostModel()->getProfilePostCommentIdsForCache($this->get('profile_post_id'));
		$this->set('latest_comment_ids', serialize($cache));
	}

	/**
	 * @return XenForo_Model_ProfilePost
	 */
	protected function _getProfilePostModel()
	{
		return $this->getModelFromCache('XenForo_Model_ProfilePost');
	}
}