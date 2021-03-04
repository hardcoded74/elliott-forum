<?php

class XenForo_Deferred_UserMessage extends XenForo_Deferred_Abstract
{
	public function canTriggerManually()
	{
		return false;
	}

	public function execute(array $deferred, array $data, $targetRunTime, &$status)
	{
		$data = array_merge(array(
			'start' => 0,
			'count' => 0,
			'criteria' => null,
			'userIds' => null,
			'conversation' => array()
		), $data);

		$s = microtime(true);

		/* @var $userModel XenForo_Model_User */
		$userModel = XenForo_Model::create('XenForo_Model_User');

		if (is_array($data['criteria']))
		{
			$userIds = $userModel->getUserIds($data['criteria'], $data['start'], 1000);
		}
		else if (is_array($data['userIds']))
		{
			$userIds = $data['userIds'];
		}
		else
		{
			$userIds = array();
		}

		if (!$userIds)
		{
			return false;
		}

		$conversation = $data['conversation'];
		$conversationUser = $userModel->getUserById($conversation['user_id']);

		$limitTime = ($targetRunTime > 0);

		foreach ($userIds AS $key => $userId)
		{
			$data['count']++;
			$data['start'] = $userId;
			unset($userIds[$key]);

			if ($userId == $conversationUser['user_id'])
			{
				continue;
			}

			$user = $userModel->getUserById($userId);
			if ($user)
			{
				$this->_sendMessage($conversation, $conversationUser, $user);
			}

			if ($limitTime && microtime(true) - $s > $targetRunTime)
			{
				break;
			}
		}

		if (is_array($data['userIds']))
		{
			$data['userIds'] = $userIds;
		}

		$actionPhrase = new XenForo_Phrase('messaging');
		$typePhrase = new XenForo_Phrase('users');
		$status = sprintf('%s... %s (%d)', $actionPhrase, $typePhrase, $data['count']);

		return $data;
	}

	protected function _sendMessage(array $conversation, array $conversationUser, array $user)
	{
		$title = $conversation['message_title'];
		$body = $conversation['message_body'];

		$phraseTitles = XenForo_Helper_String::findPhraseNamesFromStringSimple($title . $body);

		/** @var XenForo_Model_Phrase $phraseModel */
		$phraseModel = XenForo_Model::create('XenForo_Model_Phrase');
		$phrases = $phraseModel->getPhraseTextFromPhraseTitles($phraseTitles, $user['language_id']);

		foreach ($phraseTitles AS $search => $phraseTitle)
		{
			if (isset($phrases[$phraseTitle]))
			{
				$title = str_replace($search, $phrases[$phraseTitle], $title);
				$body = str_replace($search, $phrases[$phraseTitle], $body);
			}
		}

		$replacements = array(
			'{name}' => $user['username'],
			'{id}' => $user['user_id']
		);
		$body = strtr($body, $replacements);

		/* @var $conversationModel XenForo_Model_Conversation */
		$conversationModel = XenForo_Model::create('XenForo_Model_Conversation');

		/** @var XenForo_DataWriter_ConversationMaster $conversationDw */
		$conversationDw = XenForo_DataWriter::create('XenForo_DataWriter_ConversationMaster');

		$conversationDw->setExtraData(XenForo_DataWriter_ConversationMaster::DATA_ACTION_USER, $conversationUser);
		$conversationDw->setExtraData(XenForo_DataWriter_ConversationMaster::DATA_MESSAGE, $body);
		$conversationDw->set('user_id', $conversationUser['user_id']);
		$conversationDw->set('username', $conversationUser['username']);
		$conversationDw->set('title', utf8_substr($title, 0, 100));
		$conversationDw->set('open_invite', $conversation['open_invite']);
		$conversationDw->set('conversation_open', $conversation['conversation_locked'] ? 0 : 1);
		$conversationDw->addRecipientUserIds(array($user['user_id'])); // bypasses permissions

		$messageDw = $conversationDw->getFirstMessageDw();
		$messageDw->set('message', $body);

		$conversationDw->save();

		$conversationModel->markConversationAsRead(
			$conversationDw->get('conversation_id'), $conversationUser['user_id'], XenForo_Application::$time
		);

		if ($conversation['delete_type'])
		{
			$conversationModel->deleteConversationForUser(
				$conversationDw->get('conversation_id'), $conversationUser['user_id'], $conversation['delete_type']
			);
		}
	}

	public function canCancel()
	{
		return true;
	}
}