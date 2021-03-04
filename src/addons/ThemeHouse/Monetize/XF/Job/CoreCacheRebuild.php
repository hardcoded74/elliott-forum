<?php

namespace ThemeHouse\Monetize\XF\Job;

use ThemeHouse\Monetize\Repository\AffiliateLink;
use ThemeHouse\Monetize\Repository\Keyword;
use ThemeHouse\Monetize\Repository\UpgradePage;

/**
 * Class CoreCacheRebuild
 * @package ThemeHouse\Monetize\XF\Job
 */
class CoreCacheRebuild extends XFCP_CoreCacheRebuild
{
    /**
     * @param $maxRunTime
     * @return \XF\Job\JobResult
     */
    public function run($maxRunTime)
    {
        /** @var UpgradePage $upgradePageRepo */
        $upgradePageRepo = \XF::repository('ThemeHouse\Monetize:UpgradePage');
        $upgradePageRepo->rebuildUpgradePageCache();

        /** @var AffiliateLink $affiliateLinkRepo */
        $affiliateLinkRepo = \XF::repository('ThemeHouse\Monetize:AffiliateLink');
        $affiliateLinkRepo->rebuildAffiliateLinkCache();

        /** @var Keyword $keywordRepo */
        $keywordRepo = \XF::repository('ThemeHouse\Monetize:Keyword');
        $keywordRepo->rebuildKeywordCache();

        return parent::run($maxRunTime);
    }
}
