<?php

class XenForo_SpamHandler_ProfilePostComment extends XenForo_SpamHandler_Abstract
{
	/**
	 * Checks that the options array contains a non-empty 'delete_messages' key
	 *
	 * @param array $user
	 * @param array $options
	 *
	 * @return boolean
	 */
	public function cleanUpConditionCheck(array $user, array $options)
	{
		return !empty($options['delete_messages']);
	}

	/**
	 * @see XenForo_SpamHandler_Abstract::cleanUp()
	 */
	public function cleanUp(array $user, array &$log, &$errorKey)
	{
		if ($comments = $this->getModelFromCache('XenForo_Model_ProfilePost')->getProfilePostCommentsByUserId($user['user_id']))
		{
			$commentIds = array_keys($comments);

			$this->getModelFromCache('XenForo_Model_SpamPrevention')->submitSpamCommentData('profile_post_comment', $commentIds);

			$deleteType = (XenForo_Application::get('options')->spamMessageAction == 'delete' ? 'hard' : 'soft');

			$log['profile_post_comment'] = array(
				'deleteType' => $deleteType,
				'profilePostCommentIds' => $commentIds
			);

			foreach ($comments AS $comment)
			{
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_ProfilePostComment', XenForo_DataWriter::ERROR_SILENT);
				$dw->setExistingData($comment);

				if ($deleteType == 'soft')
				{
					$dw->set('message_state', 'deleted');
					$dw->save();
				}
				else
				{
					$dw->delete();
				}
			}
		}

		return true;
	}

	/**
	 * @see XenForo_SpamHandler_Abstract::restore()
	 */
	public function restore(array $log, &$errorKey = '')
	{
		if ($log['deleteType'] == 'soft')
		{
			$comments = $this->getModelFromCache('XenForo_Model_ProfilePost')->getProfilePostCommentsByIds($log['profilePostCommentIds']);
			foreach ($comments AS $comment)
			{
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_ProfilePostComment', XenForo_DataWriter::ERROR_SILENT);
				$dw->setExistingData($comment);
				$dw->set('message_state', 'visible');
				$dw->save();
			}
		}

		return true;
	}
}