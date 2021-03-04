<?php

class bdApi_XenForo_Model_Conversation extends XFCP_bdApi_XenForo_Model_Conversation
{
	const FETCH_OPTIONS_JOIN = 'bdApi_join';
	const FETCH_OPTIONS_JOIN_FETCH_FIRST_MESSAGE_AVATAR = 0x01;

	public function getFetchOptionsToPrepareApiData(array $fetchOptions = array())
	{
		if (!empty($fetchOptions['join']))
		{
			if ($fetchOptions['join'] & XenForo_Model_Conversation::FETCH_FIRST_MESSAGE)
			{
				if (empty($fetchOptions[self::FETCH_OPTIONS_JOIN]))
				{
					$fetchOptions[self::FETCH_OPTIONS_JOIN] = 0;
				}

				$fetchOptions[self::FETCH_OPTIONS_JOIN] |= self::FETCH_OPTIONS_JOIN_FETCH_FIRST_MESSAGE_AVATAR;
			}
		}

		return $fetchOptions;
	}

	public function getFetchOptionsToPrepareApiDataForMessages(array $fetchOptions = array())
	{
		return $fetchOptions;
	}

	public function prepareApiDataForConversations(array $conversations, $getRecipients = false)
	{
		$data = array();

		foreach ($conversations as $key => $conversation)
		{
			$data[] = $this->prepareApiDataForConversation($conversation, $getRecipients);
		}

		return $data;
	}

	public function prepareApiDataForConversation(array $conversation, $getRecipients = false)
	{
		$conversation = $this->prepareConversation($conversation);

		$publicKeys = array(
			// xf_conversation_master
			'conversation_id' => 'conversation_id',
			'title' => 'conversation_title',
			'user_id' => 'creator_user_id',
			'username' => 'creator_username',
			'start_date' => 'conversation_create_date',
			'last_message_date' => 'conversation_update_date',
		);

		$data = bdApi_Data_Helper_Core::filter($conversation, $publicKeys);

		if (isset($conversation['reply_count']))
		{
			$data['conversation_message_count'] = $conversation['reply_count'] + 1;
		}

		$data['conversation_has_new_message'] = !empty($conversation['is_unread']);

		if (isset($conversation['conversation_open']) and isset($conversation['recipent_state']))
		{
			switch ($conversation['recipent_state'])
			{
				case 'active':
					$data['conversation_is_open'] = empty($conversation['conversation_open']) ? false : true;
					$data['conversation_is_deleted'] = false;
					break;
				case 'deleted':
				case 'deleted_ignored':
					$data['conversation_is_open'] = false;
					$data['conversation_is_deleted'] = true;
					break;
			}
		}

		$data['links'] = array(
			'permalink' => bdApi_Link::buildPublicLink('conversations', $conversation),
			'detail' => bdApi_Link::buildApiLink('conversations', $conversation),
			'messages' => bdApi_Link::buildApiLink('conversation-messages', array(), array('conversation_id' => $conversation['conversation_id']))
		);

		$data['permissions'] = array(
			'reply' => $this->canReplyToConversation($conversation),
			'delete' => true,
		);

		if (isset($conversation['message']))
		{
			$firstMessage = $conversation;
			$firstMessage['message_id'] = $conversation['first_message_id'];
			$firstMessage['message_date'] = $conversation['start_date'];

			if (isset($conversation['first_message_avatar_date']))
			{
				$firstMessage['avatar_date'] = $conversation['first_message_avatar_date'];
				$firstMessage['gender'] = $conversation['first_message_gender'];
				$firstMessage['gravatar'] = $conversation['first_message_gravatar'];
			}

			$data['first_message'] = $this->prepareApiDataForMessage($firstMessage, $conversation);
		}

		if (!empty($getRecipients))
		{
			$recipients = $this->getConversationRecipients($conversation['conversation_id']);
			$data['recipients'] = array();
			foreach ($recipients as $recipient)
			{
				$data['recipients'][] = array(
					'user_id' => $recipient['user_id'],
					'username' => $recipient['username']
				);
			}
		}

		return $data;
	}

	public function prepareApiDataForMessages(array $messages, array $conversation)
	{
		$data = array();

		foreach ($messages as $key => $message)
		{
			$data[] = $this->prepareApiDataForMessage($message, $conversation);
		}

		return $data;
	}

	public function prepareApiDataForMessage(array $message, array $conversation)
	{
		$message = $this->prepareMessage($message, $conversation);

		if (!isset($message['messageHtml']))
		{
			$message['messageHtml'] = $this->_renderApiMessage($message);
		}

		if (isset($message['message']))
		{
			$message['messagePlainText'] = bdApi_Data_Helper_Message::getPlainText($message['message']);
		}

		$publicKeys = array(
			// xf_conversation_message
			'message_id' => 'message_id',
			'conversation_id' => 'conversation_id',
			'user_id' => 'creator_user_id',
			'username' => 'creator_username',
			'message_date' => 'message_create_date',
			'message' => 'message_body',
			'messageHtml' => 'message_body_html',
			'messagePlainText' => 'message_body_plain_text'
		);

		$data = bdApi_Data_Helper_Core::filter($message, $publicKeys);

		$data['links'] = array(
			'detail' => bdApi_Link::buildApiLink('conversation-messages', $message),
			'conversation' => bdApi_Link::buildApiLink('conversations', $conversation),
			'creator' => bdApi_Link::buildApiLink('users', $message),
			'creator_avatar' => XenForo_Template_Helper_Core::callHelper('avatar', array(
				$message,
				'm',
				false,
				true
			))
		);

		return $data;
	}

	public function prepareConversationFetchOptions(array $fetchOptions)
	{
		$prepared = parent::prepareConversationFetchOptions($fetchOptions);
		extract($prepared);

		if (!empty($fetchOptions[self::FETCH_OPTIONS_JOIN]))
		{
			if ($fetchOptions[self::FETCH_OPTIONS_JOIN] & self::FETCH_OPTIONS_JOIN_FETCH_FIRST_MESSAGE_AVATAR)
			{
				$selectFields .= ',
						first_message_user.avatar_date AS first_message_avatar_date,
						first_message_user.gender AS first_message_gender,
						first_message_user.gravatar AS first_message_gravatar';
				$joinTables .= '
						LEFT JOIN xf_user AS first_message_user ON
						(first_message_user.user_id = conversation_master.user_id)';
			}
		}

		return compact(array_keys($prepared));
	}

	protected function _renderApiMessage(array $conversation)
	{
		static $bbCodeParser = false;

		if ($bbCodeParser === false)
		{
			$bbCodeParser = new XenForo_BbCode_Parser(XenForo_BbCode_Formatter_Base::create('Base'));
		}

		return new XenForo_BbCode_TextWrapper($conversation['message'], $bbCodeParser);
	}

}
