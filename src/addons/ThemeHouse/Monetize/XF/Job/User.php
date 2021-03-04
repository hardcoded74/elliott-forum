<?php

namespace ThemeHouse\Monetize\XF\Job;

class User extends XFCP_User
{
    protected function rebuildById($id)
    {
        parent::rebuildById($id);

        /** @var \ThemeHouse\Monetize\XF\Repository\UserUpgrade $userUpgradeRepo */
        $userUpgradeRepo = \XF::repository('XF:UserUpgrade');
        $userUpgradeRepo->rebuildThMonetizeUserUpgradeCaches($id);
    }
}
