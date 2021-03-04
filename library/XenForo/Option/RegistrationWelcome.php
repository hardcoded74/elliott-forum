<?php

class XenForo_Option_RegistrationWelcome
{
	/**
	 * Verifies the registrationWelcome setting
	 *
	 * @param array $values
	 * @param XenForo_DataWriter $dw Calling DW
	 * @param string $fieldName Name of field/option
	 *
	 * @return true
	 */
	public static function verifyOption(array &$values, XenForo_DataWriter $dw, $fieldName)
	{
		if ($dw->isInsert())
		{
			// insert - just trust the default value
			return true;
		}

		if (!empty($values['messageEnabled']))
		{
			/** @var XenForo_Model_User $userModel */
			$userModel = XenForo_Model::create('XenForo_Model_User');

			$participants = explode(',', $values['messageParticipants']);

			$starter = array_shift($participants);
			$participantUsers = $userModel->getUsersByNames($participants);

			$starterUser = $userModel->getUserByName($starter);
			if (!$starterUser)
			{
				if ($starter)
				{
					$dw->error(new XenForo_Phrase('the_following_recipients_could_not_be_found_x', array('names' => $starter)), $fieldName);
				}
				else
				{
					$dw->error(new XenForo_Phrase('please_enter_at_least_one_valid_recipient'), $fieldName);
				}

				return false;
			}

			/** @var XenForo_DataWriter_ConversationMaster $conversationDw */
			$conversationDw = XenForo_DataWriter::create('XenForo_DataWriter_ConversationMaster');

			$conversationDw->setExtraData(XenForo_DataWriter_ConversationMaster::DATA_ACTION_USER, $starterUser);
			$conversationDw->setExtraData(XenForo_DataWriter_ConversationMaster::DATA_MESSAGE, $values['messageBody']);
			$conversationDw->set('user_id', $starterUser['user_id']);
			$conversationDw->set('username', $starterUser['username']);
			$conversationDw->set('title', $values['messageTitle']);
			$conversationDw->addRecipientUserIds(array_keys($participantUsers)); // skips permissions

			$messageDw = $conversationDw->getFirstMessageDw();
			$messageDw->set('message', $values['messageBody']);

			$conversationDw->preSave();

			if ($conversationDw->hasErrors())
			{
				$errors = $conversationDw->getErrors();

				// Skip recipient errors. We've already verified the recipients are valid.
				if (isset($errors['recipients']))
				{
					unset($errors['recipients']);
				}

				if (count($errors))
				{
					$dw->error(reset($errors), $fieldName);
					return false;
				}
			}

			$validUsernames = XenForo_Application::arrayColumn($participantUsers, 'username');
			array_unshift($validUsernames, $starter);
			$values['messageParticipants'] = implode(', ', array_unique($validUsernames));
		}

		if (!empty($values['emailEnabled']) && !strlen(trim($values['emailBody'])))
		{
			$dw->error(new XenForo_Phrase('you_must_enter_email_message_to_enable_welcome_email'), $fieldName);
			return false;
		}

		return true;
	}
}