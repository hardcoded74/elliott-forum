<?php

class XenForo_Deferred_UserAlert extends XenForo_Deferred_Abstract
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
			'alert' => array()
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

		$replacements = array();

		$alert = $data['alert'];

		if ($alert['link_url'])
		{
			$link = '<a href="' . $alert['link_url'] . '" class="PopupItemLink">'
				. ($alert['link_title'] ? $alert['link_title'] : $alert['link_url'])
				. '</a>';
			$replacements['{link}'] = $link;

			if (strpos($alert['alert_body'], '{link}') === false)
			{
				$alert['alert_body'] .= ' {link}';
			}
		}

		if ($alert['user_id'])
		{
			$fromUser = $userModel->getUserById($alert['user_id']);
		}
		else
		{
			$fromUser = array('user_id' => 0, 'username' => '');
		}

		$limitTime = ($targetRunTime > 0);

		foreach ($userIds AS $key => $userId)
		{
			$data['count']++;
			$data['start'] = $userId;
			unset($userIds[$key]);

			$user = $userModel->getUserById($userId);
			if ($user)
			{
				$this->_sendAlert($alert, $replacements, $fromUser, $user);
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

		$actionPhrase = new XenForo_Phrase('alerting');
		$typePhrase = new XenForo_Phrase('users');
		$status = sprintf('%s... %s (%d)', $actionPhrase, $typePhrase, $data['count']);

		return $data;
	}

	protected function _sendAlert(array $alert, array $replacements, array $fromUser, array $user)
	{
		$body = $alert['alert_body'];

		/** @var XenForo_Model_Phrase $phraseModel */
		$phraseModel = XenForo_Model::create('XenForo_Model_Phrase');

		$phraseTitles = XenForo_Helper_String::findPhraseNamesFromStringSimple($body);
		$phrases = $phraseModel->getPhraseTextFromPhraseTitles($phraseTitles, $user['language_id']);

		foreach ($phraseTitles AS $search => $phraseTitle)
		{
			if (isset($phrases[$phraseTitle]))
			{
				$body = str_replace($search, $phrases[$phraseTitle], $body);
			}
		}

		$replacements = array_merge($replacements, array(
			'{name}' => htmlspecialchars($user['username']),
			'{id}' => $user['user_id']
		));

		$alert['alert_text'] = strtr($body, $replacements);

		XenForo_Model_Alert::alert(
			$user['user_id'],
			$fromUser['user_id'],
			$fromUser['username'],
			'user', $user['user_id'], 'from_admin',
			$alert
		);
	}

	public function canCancel()
	{
		return true;
	}
}