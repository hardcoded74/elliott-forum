<?php

namespace ThemeHouse\Monetize\Listener;

use XF\App;
use XF\Container;

/**
 * Class AppSetup
 * @package ThemeHouse\Monetize\Listener
 */
class AppSetup
{
    /**
     * @param App $app
     * @throws \XF\Db\Exception
     */
    public static function appSetup(App $app)
    {
        $container = $app->container();

        $container['thMonetize.affiliateLinks'] = $app->fromRegistry(
            'thMonetize_affiliateLinks',
            function (Container $c) {
                return $c['em']->getRepository('ThemeHouse\Monetize:AffiliateLink')->rebuildAffiliateLinkCache();
            }
        );

        $container['thMonetize.keywords'] = $app->fromRegistry(
            'thMonetize_keywords',
            function (Container $c) {
                return $c['em']->getRepository('ThemeHouse\Monetize:Keyword')->rebuildKeywordCache();
            }
        );

        $container['thMonetize.upgradePages'] = $app->fromRegistry(
            'thMonetize_upgradePages',
            function (Container $c) {
                return $c['em']->getRepository('ThemeHouse\Monetize:UpgradePage')->rebuildUpgradePageCache();
            }
        );

        $container['permission.cache'] = function (Container $c) use ($app) {
            $class = $app->extendClass('XF\PermissionCache');
            return new $class($c['db']);
        };
    }
}
