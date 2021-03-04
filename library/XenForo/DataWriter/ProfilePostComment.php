<?php

/**
* Data writer for profile post comments
*
* @package XenForo_ProfilePost
*/
class XenForo_DataWriter_ProfilePostComment extends XenForo_DataWriter
{
	/**
	 * Holds the reason for soft deletion.
	 *
	 * @var string
	 */
	const DATA_DELETE_REASON = 'deleteReason';

	const DATA_PROFILE_USER = 'profileUser';

	const DATA_PROFILE_POST = 'profilePost';

	/**
	 * Option to control whether or not to log the IP address of the message sender
	 *
	 * @var string
	 */
	const OPTION_SET_IP_ADDRESS = 'setIpAddress';

	/**
	 * Controls the maximum number of tag alerts that can be sent.
	 *
	 * @var string
	 */
	const OPTION_MAX_TAGGED_USERS = 'maxTaggedUsers';

	/**
	 * Title of the phrase that will be created when a call to set the
	 * existing data fails (when the data doesn't exist).
	 *
	 * @var string
	 */
	protected $_existingDataErrorPhrase = 'requested_comment_not_found';

	protected $_taggedUsers = array();

	/**
	* Gets the fields that are defined for the table. See parent for explanation.
	*
	* @return array
	*/
	protected function _getFields()
	{
		return array(
			'xf_profile_post_comment' => array(
				'profile_post_comment_id'   => array('type' => self::TYPE_UINT,   'autoIncrement' => true),
				'profile_post_id'           => array('type' => self::TYPE_UINT,   'required' => true),
				'user_id'                   => array('type' => self::TYPE_UINT,   'required' => true),
				'username'                  => array('type' => self::TYPE_STRING, 'required' => true, 'maxLength' => 50,
						'requiredError' => 'please_enter_valid_name'
				),
				'comment_date'           => array('type' => self::TYPE_UINT,   'required' => true, 'default' => XenForo_Application::$time),
				'message'                => array('type' => self::TYPE_STRING, 'required' => true,
						'requiredError' => 'please_enter_valid_message'
				),
				'ip_id'                  => array('type' => self::TYPE_UINT,   'default' => 0),
				'message_state'          => array('type' => self::TYPE_STRING, 'default' => 'visible',
					'allowedValues' => array('visible', 'moderated', 'deleted')
				),
				'likes'                  => array('type' => self::TYPE_UINT_FORCED, 'default' => 0),
				'like_users'             => array('type' => self::TYPE_SERIALIZED, 'default' => 'a:0:{}'),
				'warning_id'             => array('type' => self::TYPE_UINT,   'default' => 0),
				'warning_message'        => array('type' => self::TYPE_STRING, 'default' => '', 'maxLength' => 255)
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
		if (!$id = $this->_getExistingPrimaryKey($data))
		{
			return false;
		}

		return array('xf_profile_post_comment' => $this->_getProfilePostModel()->getProfilePostCommentById($id));
	}

	/**
	* Gets SQL condition to update the existing record.
	*
	* @return string
	*/
	protected function _getUpdateCondition($tableName)
	{
		return 'profile_post_comment_id = ' . $this->_db->quote($this->getExisting('profile_post_comment_id'));
	}

	/**
	 * Gets the data writer's default options.
	 *
	 * @return array
	 */
	protected function _getDefaultOptions()
	{
		return array(
			self::OPTION_SET_IP_ADDRESS => true,
			self::OPTION_MAX_TAGGED_USERS => 0
		);
	}

	protected function _preSave()
	{
		if ($this->isChanged('message'))
		{
			$maxLength = 420;
			if (utf8_strlen($this->get('message')) > $maxLength)
			{
				$this->error(new XenForo_Phrase('please_enter_message_with_no_more_than_x_characters', array('count' => $maxLength)), 'message');
			}
		}

		// do this auto linking after length counting
		/** @var $taggingModel XenForo_Model_UserTagging */
		$taggingModel = $this->getModelFromCache('XenForo_Model_UserTagging');

		$this->_taggedUsers = $taggingModel->getTaggedUsersInMessage(
			$this->get('message'), $newMessage, 'text'
		);
		$this->set('message', $newMessage);
	}

	protected function _postSave()
	{
		$this->_updateDeletionLog();
		$this->_updateModerationQueue();
		$this->_submitSpamLog();
		$this->_indexForSearch();

		$profilePostId = $this->get('profile_post_id');
		$commentId = $this->get('profile_post_comment_id');

		if ($this->isUpdate() && $this->isChanged('message_state'))
		{
			$this->_rebuildProfilePostCommentCounters();

			if ($this->get('message_state') == 'deleted' && $this->getExisting('message_state') == 'visible')
			{
				$this->getModelFromCache('XenForo_Model_Alert')->deleteAlerts('profile_post_comment', $commentId);
			}

			if ($this->get('user_id') && $this->get('likes'))
			{
				$this->_updateUserLikeCount();
			}
		}

		if ($this->isInsert())
		{
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_DiscussionMessage_ProfilePost');
			$dw->setExistingData($profilePostId);
			$dw->insertNewComment($commentId, $this->get('comment_date'));
			$dw->save();

			$profileUser = $this->getExtraData(self::DATA_PROFILE_USER);
			$profilePost = $this->getExtraData(self::DATA_PROFILE_POST);

			$userModel = $this->_getUserModel();

			$alertedUserIds = array();

			if ($profilePost && $profileUser && $profileUser['user_id'] != $this->get('user_id'))
			{
				// alert profile owner - only if not ignoring either profile post itself or this comment
				if (!$userModel->isUserIgnored($profileUser, $this->get('user_id'))
					&& !$userModel->isUserIgnored($profileUser, $profilePost['user_id'])
					&& XenForo_Model_Alert::userReceivesAlert($profileUser, 'profile_post_comment', 'your_profile')
				)
				{
					XenForo_Model_Alert::alert(
						$profileUser['user_id'],
						$this->get('user_id'),
						$this->get('username'),
						'profile_post_comment',
						$commentId,
						'your_profile'
					);

					$alertedUserIds[] = $profileUser['user_id'];
				}
			}

			if ($profilePost && $profilePost['profile_user_id'] != $profilePost['user_id']
				&& $profilePost['user_id'] != $this->get('user_id')
			)
			{
				// alert post owner
				$user = $userModel->getUserById($profilePost['user_id'], array(
					'join' => XenForo_Model_User::FETCH_USER_OPTION | XenForo_Model_User::FETCH_USER_PROFILE
				));
				if ($user && !$userModel->isUserIgnored($user, $this->get('user_id'))
					&& XenForo_Model_Alert::userReceivesAlert($user, 'profile_post_comment', 'your_post')
				)
				{
					XenForo_Model_Alert::alert(
						$user['user_id'],
						$this->get('user_id'),
						$this->get('username'),
						'profile_post_comment',
						$commentId,
						'your_post'
					);

					$alertedUserIds[] = $user['user_id'];
				}
			}

			$maxTagged = $this->getOption(self::OPTION_MAX_TAGGED_USERS);
			if ($maxTagged && $this->_taggedUsers && $profilePost && $profileUser)
			{
				if ($maxTagged > 0)
				{
					$alertTagged = array_slice($this->_taggedUsers, 0, $maxTagged, true);
				}
				else
				{
					$alertTagged = $this->_taggedUsers;
				}
				$alertedUserIds = array_merge($alertedUserIds, $this->_getProfilePostModel()->alertTaggedMembers(
					$profilePost, $profileUser, $alertTagged, $alertedUserIds, $commentId, array(
						'user_id' => $this->get('user_id'),
						'username' => $this->get('username')
					)
				));
			}

			$otherCommenterIds = $this->_getProfilePostModel()->getProfilePostCommentUserIds($profilePostId, 'visible');

			$otherCommenters = $userModel->getUsersByIds($otherCommenterIds, array(
				'join' => XenForo_Model_User::FETCH_USER_OPTION  | XenForo_Model_User::FETCH_USER_PROFILE
			));

			$profileUserId = empty($profileUser) ? 0 : $profileUser['user_id'];
			$profilePostUserId = empty($profilePost) ? 0 : $profilePost['user_id'];

			foreach ($otherCommenters AS $otherCommenter)
			{
				if (in_array($otherCommenter['user_id'], $alertedUserIds))
				{
					continue;
				}

				switch ($otherCommenter['user_id'])
				{
					case $profileUserId:
					case $profilePostUserId:
					case $this->get('user_id'):
					case 0:
						break;

					default:
						if (!$userModel->isUserIgnored($otherCommenter, $this->get('user_id'))
							&& XenForo_Model_Alert::userReceivesAlert($otherCommenter, 'profile_post_comment', 'other_commenter')
						)
						{
							XenForo_Model_Alert::alert(
								$otherCommenter['user_id'],
								$this->get('user_id'),
								$this->get('username'),
								'profile_post_comment',
								$commentId,
								'other_commenter'
							);

							$alertedUserIds[] = $otherCommenter['user_id'];
						}
				}
			}

			if ($this->getOption(self::OPTION_SET_IP_ADDRESS) && !$this->get('ip_id'))
			{
				$this->_updateIpData();
			}

			$this->_getNewsFeedModel()->publish(
				$this->get('user_id'),
				$this->get('username'),
				'profile_post_comment',
				$this->get('profile_post_comment_id'),
				'insert'
			);
		}
	}

	protected function _postDelete()
	{
		if ($this->get('message_state') == 'visible')
		{
			$this->getModelFromCache('XenForo_Model_Alert')->deleteAlerts('profile_post_comment', $this->get('profile_post_comment_id'));
		}

		$this->_rebuildProfilePostCommentCounters();
		$this->_deleteFromSearchIndex();

		if ($this->get('likes'))
		{
			$this->_deleteLikes();
		}
	}

	protected function _rebuildProfilePostCommentCounters()
	{
		$dw = XenForo_DataWriter::create('XenForo_DataWriter_DiscussionMessage_ProfilePost');
		$dw->setExistingData($this->get('profile_post_id'));
		$dw->rebuildProfilePostCommentCounters();
		$dw->save();
	}

	/**
	* Upates the IP data.
	*/
	protected function _updateIpData()
	{
		if (!empty($this->_extraData['ipAddress']))
		{
			$ipAddress = $this->_extraData['ipAddress'];
		}
		else
		{
			$ipAddress = null;
		}

		$ipId = XenForo_Model_Ip::log($this->get('user_id'), 'profile_post_comment', $this->get('profile_post_comment_id'), 'insert', $ipAddress);
		$this->set('ip_id', $ipId, '', array('setAfterPreSave' => true));

		$this->_db->update('xf_profile_post_comment',
			array('ip_id' => $ipId),
			'profile_post_comment_id = ' .  $this->_db->quote($this->get('profile_post_comment_id'))
		);
	}

	/**
	 * Updates the deletion log if necessary.
	 */
	protected function _updateDeletionLog()
	{
		if (!$this->isChanged('message_state'))
		{
			return;
		}

		if ($this->get('message_state') == 'deleted')
		{
			$reason = $this->getExtraData(self::DATA_DELETE_REASON);
			$this->getModelFromCache('XenForo_Model_DeletionLog')->logDeletion(
				'profile_post_comment', $this->get('profile_post_comment_id'), $reason
			);
		}
		else if ($this->getExisting('message_state') == 'deleted')
		{
			$this->getModelFromCache('XenForo_Model_DeletionLog')->removeDeletionLog(
				'profile_post_comment', $this->get('profile_post_comment_id')
			);
		}
	}

	/**
	 * Updates the moderation queue if necessary.
	 */
	protected function _updateModerationQueue()
	{
		if (!$this->isChanged('message_state'))
		{
			return;
		}

		if ($this->get('message_state') == 'moderated' )
		{
			$this->getModelFromCache('XenForo_Model_ModerationQueue')->insertIntoModerationQueue(
				'profile_post_comment', $this->get('profile_post_comment_id'), $this->get('comment_date')
			);
		}
		else if ($this->getExisting('message_state') == 'moderated')
		{
			$this->getModelFromCache('XenForo_Model_ModerationQueue')->deleteFromModerationQueue(
				'profile_post_comment', $this->get('profile_post_comment_id')
			);
		}
	}

	protected function _submitSpamLog()
	{
		if ($this->getExisting('message_state') == 'moderated' && $this->get('message_state') == 'visible')
		{
			/** @var $spamModel XenForo_Model_SpamPrevention */
			$spamModel = $this->getModelFromCache('XenForo_Model_SpamPrevention');
			$spamModel->submitHamCommentData('profile_post_comment', $this->get('profile_post_comment_id'));
		}
	}

	/**
	 * Updates the search index for this message.
	 */
	protected function _indexForSearch()
	{
		if ($this->get('message_state') == 'visible')
		{
			if ($this->getExisting('message_state') != 'visible' || $this->isChanged('message'))
			{
				$this->_insertOrUpdateSearchIndex();
			}
		}
		else if ($this->isUpdate() && $this->get('message_state') != 'visible' && $this->getExisting('message_state') == 'visible')
		{
			$this->_deleteFromSearchIndex();
		}
	}

	/**
	 * Inserts or updates a record in the search index for this message.
	 */
	protected function _insertOrUpdateSearchIndex()
	{
		$dataHandler = XenForo_Search_DataHandler_Abstract::create('XenForo_Search_DataHandler_ProfilePostComment');
		$indexer = new XenForo_Search_Indexer();
		$dataHandler->insertIntoIndex($indexer, $this->getMergedData());
	}

	/**
	 * Deletes this message from the search index.
	 */
	protected function _deleteFromSearchIndex()
	{
		$dataHandler = XenForo_Search_DataHandler_Abstract::create('XenForo_Search_DataHandler_ProfilePostComment');
		$indexer = new XenForo_Search_Indexer();
		$dataHandler->deleteFromIndex($indexer, $this->getMergedData());
	}

	/**
	 * Updates the user like count.
	 *
	 * @param boolean $isDelete True if hard deleting the message
	 */
	protected function _updateUserLikeCount($isDelete = false)
	{
		$likes = $this->get('likes');
		if (!$likes)
		{
			return;
		}

		$profilePost = $this->_getProfilePostModel()->getProfilePostById($this->get('profile_post_id'));
		if ($profilePost && $profilePost['message_state'] != 'visible')
		{
			return;
		}

		if ($this->getExisting('message_state') == 'visible'
			&& ($this->get('message_state') != 'visible' || $isDelete)
		)
		{
			$this->_db->query('
				UPDATE xf_user
				SET like_count = IF(like_count > ?, like_count - ?, 0)
				WHERE user_id = ?
			', array($likes, $likes, $this->get('user_id')));
		}
		else if ($this->get('message_state') == 'visible' && $this->getExisting('message_state') != 'visible')
		{
			$this->_db->query('
				UPDATE xf_user
				SET like_count = like_count + ?
				WHERE user_id = ?
			', array($likes, $this->get('user_id')));
		}
	}

	/**
	 * Delete all like entries for content.
	 */
	protected function _deleteLikes()
	{
		$updateUserLikeCounter = ($this->get('message_state') == 'visible');
		$profilePost = $this->_getProfilePostModel()->getProfilePostById($this->get('profile_post_id'));
		if ($updateUserLikeCounter && $profilePost && $profilePost['message_state'] != 'visible')
		{
			$updateUserLikeCounter = false;
		}

		$this->getModelFromCache('XenForo_Model_Like')->deleteContentLikes(
			'profile_post_comment', $this->get('profile_post_comment_id'), $updateUserLikeCounter
		);
	}

	/**
	 * @return XenForo_Model_ProfilePost
	 */
	protected function _getProfilePostModel()
	{
		return $this->getModelFromCache('XenForo_Model_ProfilePost');
	}
}