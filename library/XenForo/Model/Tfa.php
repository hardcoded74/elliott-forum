<?php

class XenForo_Model_Tfa extends XenForo_Model
{
	/**
	 * @return XenForo_Tfa_AbstractProvider[]
	 */
	public function getValidProviders()
	{
		$providers = $this->_getDb()->fetchPairs("
			SELECT provider_id, provider_class
			FROM xf_tfa_provider
			WHERE active = 1
			ORDER BY priority
		");
		$output = array();
		foreach ($providers AS $id => $class)
		{
			if (class_exists($class))
			{
				$class = XenForo_Application::resolveDynamicClass($class);
				$output[$id] = new $class($id);
			}
		}

		return $output;
	}

	/**
	 * @param string $providerId
	 *
	 * @return XenForo_Tfa_AbstractProvider|null
	 */
	public function getValidProvider($providerId)
	{
		$class = $this->_getDb()->fetchOne("
			SELECT provider_class
			FROM xf_tfa_provider
			WHERE provider_id = ?
				AND active = 1
		", $providerId);

		if ($class && class_exists($class))
		{
			$class = XenForo_Application::resolveDynamicClass($class);
			return new $class($providerId);
		}
		else
		{
			return null;
		}
	}

	public function getUserTfaEntries($userId)
	{
		return $this->fetchAllKeyed("
			SELECT *
			FROM xf_user_tfa
			WHERE user_id = ?
		", 'provider_id', $userId);
	}

	public function getUserTfaEntry($userId, $providerId)
	{
		return $this->_getDb()->fetchRow("
			SELECT *
			FROM xf_user_tfa
			WHERE user_id = ?
				AND provider_id = ?
		", array($userId, $providerId));
	}

	public function getTfaConfigurationForUser($userId, &$userData)
	{
		$userData = $this->getUserTfaEntries($userId);
		$providers = $this->getValidProviders();
		$providers = $this->filterProvidersForUserEnabled($providers, $userData);

		return $providers;
	}

	public function userRequiresTfa($userId)
	{
		$providers = $this->getValidProviders();
		$userData = $this->getUserTfaEntries($userId);

		$providers = $this->filterProvidersForUserEnabled($providers, $userData);
		if (!$providers)
		{
			return false;
		}

		if (count($providers) == 1)
		{
			/** @var XenForo_Tfa_AbstractProvider $provider */
			$provider = reset($providers);
			if ($provider->getProviderId() == 'backup')
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * @param XenForo_Tfa_AbstractProvider[] $providers
	 * @param array $userData
	 *
	 * @return XenForo_Tfa_AbstractProvider[]
	 */
	public function filterProvidersForUserEnabled(array $providers, array $userData)
	{
		foreach ($providers AS $id => $provider)
		{
			if (!isset($userData[$id]))
			{
				unset($providers[$id]);
			}
		}

		return $providers;
	}

	/**
	 * @param XenForo_Tfa_AbstractProvider[] $providers
	 * @param array $userData
	 *
	 * @return array
	 */
	public function prepareTfaProviderList(array $providers, array $userData = array())
	{
		$output = array();
		foreach ($providers AS $id => $provider)
		{
			$enabled = isset($userData[$id]);
			$canEnable = $provider->canEnable();
			if (!$enabled && !$canEnable)
			{
				continue;
			}

			$output[$id] = array(
				'provider_id' => $id,
				'title' => $provider->getTitle(),
				'description' => $provider->getDescription(),
				'enabled' => $enabled,
				'canEnable' => !$enabled && $canEnable,
				'canDisable' => $enabled && $provider->canDisable(),
				'canManage' => $enabled && $provider->canManage()
			);
		}

		return $output;
	}

	public function enableUserTfaProvider($userId, $providerId, array $providerData)
	{
		$db = $this->_getDb();

		$db->beginTransaction();
		$db->query("
			INSERT INTO xf_user_tfa
				(user_id, provider_id, provider_data, last_used_date)
			VALUES
				(?, ?, ?, ?)
			ON DUPLICATE KEY UPDATE
				provider_data = VALUES(provider_data),
				last_used_date = VALUES(last_used_date)
		", array($userId, $providerId, serialize($providerData), XenForo_Application::$time));
		$db->update('xf_user_option', array('use_tfa' => 1), 'user_id = ' . $db->quote($userId));
		$db->commit();

		return true;
	}

	public function enableBackupTfaProviderIfNeeded(array $user)
	{
		$provider = $this->getValidProvider('backup');
		if (!$provider)
		{
			return false;
		}

		$data = $this->getUserTfaEntry($user['user_id'], 'backup');
		if ($data)
		{
			return false;
		}

		$providerData = $provider->generateInitialData($user, array());
		$this->enableUserTfaProvider($user['user_id'], 'backup', $providerData);

		return true;
	}

	public function disableUserTfaProvider($userId, $providerId)
	{
		$db = $this->_getDb();

		$db->beginTransaction();

		$this->_getDb()->query("
			DELETE FROM xf_user_tfa
			WHERE user_id = ?
				AND provider_id = ?
		", array($userId, $providerId));

		if (!$this->userRequiresTfa($userId))
		{
			// this delete is to clear out the backup codes and any disabled providers since
			// we're setting use_tfa to 0
			$this->disableTfaForUser($userId);
		}

		$db->commit();
	}

	public function disableTfaForUser($userId)
	{
		$db = $this->_getDb();

		$db->beginTransaction();
		$db->delete('xf_user_tfa', 'user_id = ' . $db->quote($userId));
		$db->update('xf_user_option', array('use_tfa' => 0), 'user_id = ' . $db->quote($userId));
		$this->deleteUserTrustedRecords($userId);
		$db->commit();
	}

	public function updateUserProvider($userId, $providerId, array $providerData, $updateLastUsed = false)
	{
		$update = array('provider_data' => serialize($providerData));
		if ($updateLastUsed)
		{
			$update['last_used_date'] = XenForo_Application::$time;
		}

		$db = $this->_getDb();
		$db->update('xf_user_tfa', $update,
			'user_id = ' . $db->quote($userId) . ' AND provider_id = ' . $db->quote($providerId)
		);
	}

	public function createTrustedKey($userId, $trustedUntil = null)
	{
		if ($trustedUntil === null)
		{
			$trustedUntil = XenForo_Application::$time + 86400 * 30;

			// jitter between 0 and 96 hours (4 days). This attempts to reduce situations where multiple
			// devices all expire at almost identical times
			$offsetJitter = mt_rand(0, 4 * 24) * 3600;
			$trustedUntil += $offsetJitter;
		}

		$key = XenForo_Application::generateRandomString(32);

		$this->_getDb()->query("
			INSERT IGNORE INTO xf_user_tfa_trusted
				(user_id, trusted_key, trusted_until)
			VALUES
				(?, ?, ?)
		", array($userId, $key, $trustedUntil));

		return $key;
	}

	public function getUserTrustedRecord($userId, $trustedKey)
	{
		return $this->_getDb()->fetchRow("
			SELECT *
			FROM xf_user_tfa_trusted
			WHERE user_id = ?
				AND trusted_key = ?
				AND trusted_until >= ?
		", array($userId, $trustedKey, XenForo_Application::$time));
	}

	public function countUserTrustedRecords($userId, $notTrustedKey = null)
	{
		if ($notTrustedKey)
		{
			$notKeySql = 'AND trusted_key <> ' . $this->_getDb()->quote($notTrustedKey);
		}
		else
		{
			$notKeySql = '';
		}

		return $this->_getDb()->fetchOne("
			SELECT COUNT(*)
			FROM xf_user_tfa_trusted
			WHERE user_id = ?
				{$notKeySql}
		", array($userId));
	}

	public function deleteUserTrustedRecord($userId, $trustedKey)
	{
		$this->_getDb()->query("
			DELETE FROM xf_user_tfa_trusted
			WHERE user_id = ?
				AND trusted_key = ?
		", array($userId, $trustedKey));
	}

	public function deleteUserTrustedRecords($userId, $notTrustedKey = null)
	{
		if ($notTrustedKey)
		{
			$notKeySql = 'AND trusted_key <> ' . $this->_getDb()->quote($notTrustedKey);
		}
		else
		{
			$notKeySql = '';
		}

		$this->_getDb()->query("
			DELETE FROM xf_user_tfa_trusted
			WHERE user_id = ?
				{$notKeySql}
		", array($userId));
	}

	public function pruneTrustedKeys($cutOff = null)
	{
		if ($cutOff === null)
		{
			$cutOff = XenForo_Application::$time;
		}

		$db = $this->_getDb();
		return $db->delete('xf_user_tfa_trusted', 'trusted_until < ' . $db->quote($cutOff));
	}

	public function getTfaAttemptLimits()
	{
		return array(
			// [time, max attempts]
			array(60 * 5, 8),
			array(60, 4),
		);
	}

	public function logFailedTfaAttempt($userId)
	{
		$this->_getDb()->insert('xf_tfa_attempt', array(
			'user_id' => $userId,
			'attempt_date' => XenForo_Application::$time
		));
	}

	public function clearTfaAttemptsForUser($userId)
	{
		$this->_getDb()->delete('xf_tfa_attempt', 'user_id = ' . $this->_getDb()->quote($userId));
	}

	public function countTfaAttemptsInTime($cutOff, $userId)
	{
		$db = $this->_getDb();

		return $db->fetchOne("
			SELECT COUNT(*)
			FROM xf_tfa_attempt
			WHERE attempt_date > ?
				AND user_id = ?
		", array($cutOff, $userId));
	}

	public function isTfaAttemptLimited($userId)
	{
		$limits = $this->getTfaAttemptLimits();
		foreach ($limits AS $limit)
		{
			$timeLimit = $limit[0];
			$attemptLimit = $limit[1];

			$attempts = $this->countTfaAttemptsInTime(XenForo_Application::$time - $timeLimit, $userId);
			if ($attempts >= $attemptLimit)
			{
				return true;
			}
		}

		return false;
	}

	public function pruneFailedTfaAttempts($cutOff = null)
	{
		if ($cutOff === null)
		{
			$cutOff = XenForo_Application::$time - 86400;
		}

		$db = $this->_getDb();
		$db->delete('xf_tfa_attempt', 'attempt_date < ' . $db->quote($cutOff));
	}
}