<?php

class XenForo_Install_Upgrade_1050270 extends XenForo_Install_Upgrade_Abstract
{
	public function getVersionName()
	{
		return '1.5.2';
	}

	public function step1()
	{
		$db = $this->_getDb();

		$existingValue = $db->fetchOne('SELECT option_value FROM xf_option WHERE option_id = ?', 'ipv6InfoUrl');

		if ($existingValue == 'http://ip-lookup.net/index.php?ip={ip}')
		{
			$this->executeUpgradeQuery('
				UPDATE xf_option
				SET option_value = ?
				WHERE option_id = \'ipv6InfoUrl\'
			', array('http://whatismyipaddress.com/ip/{ip}'));
		}
	}
}