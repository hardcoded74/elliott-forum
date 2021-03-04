<?php

class XenForo_Install_Upgrade_1050170 extends XenForo_Install_Upgrade_Abstract
{
	public function getVersionName()
	{
		return '1.5.1';
	}

	public function step1()
	{
		$this->executeUpgradeQuery('
			UPDATE xf_bb_code_media_site
			SET embed_html = REPLACE(embed_html, \'class="fb-post"\', \'class="fb-video"\')
			WHERE media_site_id = \'facebook\'
		');
	}
}