<?php

class XenForo_Install_Upgrade_1040570 extends XenForo_Install_Upgrade_Abstract
{
	public function getVersionName()
	{
		return '1.4.5';
	}

	public function step1()
	{
		$this->executeUpgradeQuery("
			ALTER TABLE xf_user_alert
			ADD INDEX user_id (user_id)
		");
	}

	public function step2()
	{
		$this->executeUpgradeQuery("
			ALTER TABLE xf_edit_history
			ADD INDEX edit_user_id (edit_user_id)
		");
	}
}