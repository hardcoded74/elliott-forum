<?php

namespace ThemeHouse\Monetize\XF\ControllerPlugin;

use ThemeHouse\Monetize\Repository\UpgradePage;
use ThemeHouse\Monetize\XF\PermissionSet;
use XF\Entity\Purchasable;
use XF\Pub\App;
use XF\Repository\ConnectedAccount;

/**
 * Class Error
 * @package ThemeHouse\Monetize\XF\ControllerPlugin
 */
class Error extends XFCP_Error
{
    /**
     * @param $message
     * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
     */
    public function actionNoPermission($message)
    {
        $visitor = \XF::visitor();

        if (!($this->app instanceof App)) {
            return parent::actionNoPermission($message);
        }

        $suggestUpgrade = \XF::options()->thmonetize_suggestUpgradeOnNoPermissionError;

        if (!$suggestUpgrade) {
            return parent::actionNoPermission($message);
        }

        /** @var PermissionSet $permissionSet */
        $permissionSet = $visitor->PermissionSet;

        $lastFailedGlobalPermissionCheck = $permissionSet->getThMonetizeLastFailedGlobalPermissionCheck();
        if ($lastFailedGlobalPermissionCheck && \XF::options()->thmonetize_excludeSuggestUpgradeGlobal) {
            $permissionId = implode('-', $lastFailedGlobalPermissionCheck);
            if (in_array($permissionId, \XF::options()->thmonetize_excludeSuggestUpgradeGlobal)) {
                return parent::actionNoPermission($message);
            }
        }

        $lastFailedContentPermissionCheck = $permissionSet->getThMonetizeLastFailedContentPermissionCheck();
        if ($lastFailedContentPermissionCheck && \XF::options()->thmonetize_excludeSuggestUpgradeContent) {
            $contentType = $lastFailedContentPermissionCheck[0];
            if (in_array($contentType, \XF::options()->thmonetize_excludeSuggestUpgradeContent)) {
                return parent::actionNoPermission($message);
            }
        }

        /** @var Purchasable $purchasable */
        $purchasable = $this->em()->find('XF:Purchasable', 'user_upgrade', 'AddOn');
        if (!$purchasable->isActive()) {
            return parent::actionNoPermission($message);
        }

        if (!$message) {
            $message = \XF::phrase('thmonetize_an_account_upgrade_is_required_to_continue', [
                'link' => \XF::app()->router('public')->buildLink('account/upgrade'),
            ]);
        }

        $viewParams = [
            'error' => $message,
        ];

        if (!$visitor->user_id) {
            /** @var ConnectedAccount $connectedAccountRepo */
            $connectedAccountRepo = $this->repository('XF:ConnectedAccount');
            $viewParams['providers'] = $connectedAccountRepo->getUsableProviders(false);
        }

        if ($visitor->user_id || \XF::options()->thmonetize_allowGuestsToViewUserUpgrades) {
            /** @var UpgradePage $upgradePageRepo */
            $upgradePageRepo = $this->repository('ThemeHouse\Monetize:UpgradePage');
            list($upgradePage, $upgrades) =
                $upgradePageRepo->suggestUserUpgradePageForUser('error_message');

            if ($upgradePage) {
                /** @var \XF\Repository\Payment $paymentRepo */
                $paymentRepo = $this->repository('XF:Payment');
                $profiles = $paymentRepo->getPaymentProfileOptionsData();

                $viewParams['upgradePage'] = $upgradePage;
                $viewParams['upgrades'] = $upgrades;
                $viewParams['profiles'] = $profiles;
            }
        }

        $view = $this->view('ThemeHouse\Monetize:Error\UpgradeRequired', 'thmonetize_upgrade_required', $viewParams);
        $view->setResponseCode(403);

        return $view;
    }
}
