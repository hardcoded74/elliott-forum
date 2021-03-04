<?php

namespace ThemeHouse\Monetize\Listener;

use XF\Mvc\Renderer\AbstractRenderer;
use XF\Mvc\Reply\AbstractReply;
use XF\Pub\App;

/**
 * Class AppPubRenderPage
 * @package ThemeHouse\Monetize\Listener
 */
class AppPubRenderPage
{
    /**
     * @param App $app
     * @param array $params
     * @param AbstractReply $reply
     * @param AbstractRenderer $renderer
     */
    public static function appPubRenderPage(App $app, array &$params, AbstractReply $reply, AbstractRenderer $renderer)
    {
        if ($params['controller'] === 'XF:Account' && $params['action'] === 'Upgrades') {
            return;
        }

        /** @var \ThemeHouse\Monetize\Repository\UpgradePage $upgradePageRepo */
        $upgradePageRepo = $app->repository('ThemeHouse\Monetize:UpgradePage');
        list($upgradePage, $upgrades) = $upgradePageRepo->suggestUserUpgradePageForUser('overlay', null, null, $params);

        if ($upgradePage) {
            $params['thmonetize_upgradePage'] = $upgradePage;
            $params['thmonetize_upgrades'] = $upgrades;

            /** @var \XF\Repository\Payment $paymentRepo */
            $paymentRepo = $app->repository('XF:Payment');
            $params['thmonetize_profiles'] = $paymentRepo->getPaymentProfileOptionsData();
        }
    }
}
