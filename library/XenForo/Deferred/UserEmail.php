<?php

class XenForo_Deferred_UserEmail extends XenForo_Deferred_Abstract
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
			'email' => array()
		), $data);

		if (!XenForo_Application::get('config')->enableMail)
		{
			return false;
		}

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

		$email = $data['email'];
		$transport = XenForo_Mail::getTransport();

		$limitTime = ($targetRunTime > 0);

		foreach ($userIds AS $key => $userId)
		{
			$data['count']++;
			$data['start'] = $userId;
			unset($userIds[$key]);

			$user = $userModel->getUserById($userId);
			if ($user)
			{
				$this->_sendEmail($email, $user, $transport);
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

		$actionPhrase = new XenForo_Phrase('emailing');
		$typePhrase = new XenForo_Phrase('users');
		$status = sprintf('%s... %s (%d)', $actionPhrase, $typePhrase, $data['count']);

		return $data;
	}

	protected function _sendEmail(array $email, array $user, Zend_Mail_Transport_Abstract $transport)
	{
		if (!$user['email'])
		{
			return;
		}

		$phraseTitles = XenForo_Helper_String::findPhraseNamesFromStringSimple(
			$email['email_title'] . $email['email_body']
		);

		/** @var XenForo_Model_Phrase $phraseModel */
		$phraseModel = XenForo_Model::create('XenForo_Model_Phrase');
		$phrases = $phraseModel->getPhraseTextFromPhraseTitles($phraseTitles, $user['language_id']);

		foreach ($phraseTitles AS $search => $phraseTitle)
		{
			if (isset($phrases[$phraseTitle]))
			{
				$email['email_title'] = str_replace($search, $phrases[$phraseTitle], $email['email_title']);
				$email['email_body'] = str_replace($search, $phrases[$phraseTitle], $email['email_body']);
			}
		}

		$mailObj = new Zend_Mail('utf-8');
		$mailObj->setSubject($email['email_title'])
			->addTo($user['email'], $user['username'])
			->setFrom($email['from_email'], $email['from_name']);

		$options = XenForo_Application::getOptions();
		$bounceEmailAddress = $options->bounceEmailAddress;
		if (!$bounceEmailAddress)
		{
			$bounceEmailAddress = $options->defaultEmailAddress;
		}

		$toEmail = $user['email'];
		$bounceHmac = substr(hash_hmac('md5', $toEmail, XenForo_Application::getConfig()->globalSalt), 0, 8);

		$mailObj->addHeader('X-To-Validate', "$bounceHmac+$toEmail");

		if ($options->enableVerp)
		{
			$verpValue = str_replace('@', '=', $toEmail);
			$bounceEmailAddress = str_replace('@', "+$bounceHmac+$verpValue@", $bounceEmailAddress);
		}
		$mailObj->setReturnPath($bounceEmailAddress);

		if ($email['email_format'] == 'html')
		{
			$replacements = array(
				'{name}' => htmlspecialchars($user['username']),
				'{email}' => htmlspecialchars($user['email']),
				'{id}' => $user['user_id']
			);
			$email['email_body'] = strtr($email['email_body'], $replacements);

			$text = trim(
				htmlspecialchars_decode(strip_tags($email['email_body']))
			);

			$mailObj->setBodyHtml($email['email_body'])
				->setBodyText($text);
		}
		else
		{
			$replacements = array(
				'{name}' => $user['username'],
				'{email}' => $user['email'],
				'{id}' => $user['user_id']
			);
			$email['email_body'] = strtr($email['email_body'], $replacements);

			$mailObj->setBodyText($email['email_body']);
		}

		if (!$mailObj->getMessageId())
		{
			$mailObj->setMessageId();
		}

		$thisTransport = XenForo_Mail::getFinalTransportForMail($mailObj, $transport);

		try
		{
			$mailObj->send($thisTransport);
		}
		catch (Exception $e)
		{
			if (method_exists($thisTransport, 'resetConnection'))
			{
				XenForo_Error::logException($e, false, "Email to $user[email] failed: ");
				$thisTransport->resetConnection();

				try
				{
					$mailObj->send($thisTransport);
				}
				catch (Exception $e)
				{
					XenForo_Error::logException($e, false, "Email to $user[email] failed (after retry): ");
				}
			}
			else
			{
				XenForo_Error::logException($e, false, "Email to $user[email] failed: ");
			}
		}
	}

	public function canCancel()
	{
		return true;
	}
}