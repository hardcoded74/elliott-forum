<?php

class XenForo_Model_Login extends XenForo_Model
{
	public function getLoginAttemptTimes()
	{
		return array(
			'short' => 60 * 5,
			'long' => 60 * 30
		);
	}

	public function countLoginAttempts($usernameOrEmail, $ipAddress = null)
	{
		$ipAddress = XenForo_Helper_Ip::getBinaryIp(null, $ipAddress);

		$times = $this->getLoginAttemptTimes();
		$cutOff = XenForo_Application::$time - $times['short'];

		return $this->_getDb()->fetchOne('
			SELECT COUNT(*)
			FROM xf_login_attempt
			WHERE login = ?
				AND ip_address = ?
				AND attempt_date > ?
		', array($usernameOrEmail, $ipAddress, $cutOff));
	}

	public function countLoginAttemptsInTime($cutOff, $usernameOrEmail = null, $ipAddress = null)
	{
		$ipAddress = XenForo_Helper_Ip::getBinaryIp(null, $ipAddress);

		$db = $this->_getDb();
		$loginWhere = ($usernameOrEmail ? "AND login = " . $db->quote($usernameOrEmail) : '');

		return $db->fetchOne("
			SELECT COUNT(*)
			FROM xf_login_attempt
			WHERE attempt_date > ?
				AND ip_address = ?
				{$loginWhere}
		", array($cutOff, $ipAddress));
	}

	public function requireLoginCaptcha($usernameOrEmail, $maxNoCaptcha = null, $ipAddress = null)
	{
		if ($maxNoCaptcha === null)
		{
			$maxNoCaptcha = 4;
		}

		$times = $this->getLoginAttemptTimes();
		$timeShort = XenForo_Application::$time - $times['short'];
		$timeLong = XenForo_Application::$time - $times['long'];

		// login specific blocking
		$maxAttemptsUserShort = $maxNoCaptcha;
		$maxAttemptsUserLong = $maxAttemptsUserShort * 2;

		$attemptsUserShort = $this->countLoginAttemptsInTime($timeShort, $usernameOrEmail, $ipAddress);
		if ($attemptsUserShort >= $maxAttemptsUserShort)
		{
			return true;
		}

		$attemptsUserLong = $this->countLoginAttemptsInTime($timeLong, $usernameOrEmail, $ipAddress);
		if ($attemptsUserLong >= $maxAttemptsUserLong)
		{
			return true;
		}

		// ip wide blocking
		$maxAttemptsIpShort = $maxNoCaptcha * 2;
		$maxAttemptsIpLong = $maxAttemptsIpShort * 2;

		$attemptsIpShort = $this->countLoginAttemptsInTime($timeShort, null, $ipAddress);
		if ($attemptsIpShort >= $maxAttemptsIpShort)
		{
			return true;
		}

		$attemptsIpLong = $this->countLoginAttemptsInTime($timeLong, null, $ipAddress);
		if ($attemptsIpLong >= $maxAttemptsIpLong)
		{
			return true;
		}

		return false;
	}

	public function logLoginAttempt($usernameOrEmail, $ipAddress = null)
	{
		$this->_getDb()->insert('xf_login_attempt', array(
			'login' => utf8_substr($usernameOrEmail, 0, 60),
			'ip_address' => XenForo_Helper_Ip::getBinaryIp(null, $ipAddress),
			'attempt_date' => XenForo_Application::$time
		));
	}

	public function clearLoginAttempts($usernameOrEmail, $ipAddress = null)
	{
		$ipAddress = XenForo_Helper_Ip::getBinaryIp(null, $ipAddress);

		$db = $this->_getDb();
		$db->delete('xf_login_attempt',
			'login = ' . $db->quote($usernameOrEmail) . ' AND ip_address = ' . $db->quote($ipAddress)
		);
	}

	public function cleanUpLoginAttempts()
	{
		$cutOff = XenForo_Application::$time - 86400;

		$db = $this->_getDb();
		$db->delete('xf_login_attempt', 'attempt_date < ' . $db->quote($cutOff));
	}

	/**
	 * Deprecated as this does not support IPv6. All code should be updated
	 * to the "binary" format in XenForo_Helper_Ip.
	 *
	 * @param null|string $ipAddress
	 *
	 * @return string
	 */
	public function convertIpToLong($ipAddress = null)
	{
		if ($ipAddress === null)
		{
			$ipAddress = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0);
		}

		if (is_string($ipAddress) && strpos($ipAddress, '.'))
		{
			$ipAddress = ip2long($ipAddress);
		}

		return sprintf('%u', $ipAddress);
	}
}