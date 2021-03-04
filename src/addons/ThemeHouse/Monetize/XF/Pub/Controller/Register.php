<?php

namespace ThemeHouse\Monetize\XF\Pub\Controller;

use ThemeHouse\Monetize\XF\Entity\UserUpgrade;
use XF\Mvc\Reply\Redirect;
use XF\Mvc\Reply\View;

/**
 * Class Register
 * @package ThemeHouse\Monetize\XF\Pub\Controller
 */
class Register extends XFCP_Register
{
    /**
     * @return Redirect|View
     */
    public function actionIndex()
    {
        $reply = parent::actionIndex();

        if ($reply instanceof View) {
            $fields = $reply->getParam('fields');
            if (!empty($fields['thmonetize_user_upgrade_id'])) {
                $userUpgradeId = $fields['thmonetize_user_upgrade_id'];
            } else {
                $userUpgradeId = $this->filter('user_upgrade_id', 'int');
            }
            if ($userUpgradeId) {
                /** @var UserUpgrade $userUpgrade */
                $userUpgrade = $this->app->em()->find('XF:UserUpgrade', $userUpgradeId);
                if ($userUpgrade && $userUpgrade->canPurchase()) {
                    $fields['thmonetize_user_upgrade_id'] = $userUpgradeId;
                    if (!empty($fields['thmonetize_payment_profile_id'])) {
                        $paymentProfileId = $fields['thmonetize_payment_profile_id'];
                    } else {
                        $paymentProfileId = $this->filter('payment_profile_id', 'int');
                    }
                    if ($paymentProfileId) {
                        $fields['thmonetize_payment_profile_id'] = $paymentProfileId;
                    }
                    /** @var \XF\Repository\Payment $paymentRepo */
                    $paymentRepo = $this->repository('XF:Payment');
                    $reply->setParam('thmonetize_profiles', $paymentRepo->getPaymentProfileOptionsData());
                    $reply->setParam('thmonetize_upgrade', $userUpgrade);
                    $reply->setParam('fields', $fields);
                }
            }
        }

        return $reply;
    }

    /**
     * @return \XF\Mvc\Reply\Error|Redirect
     */
    public function actionRegister()
    {
        $reply = parent::actionRegister();

        if ($reply instanceof Redirect) {
            $registerUrl = $this->buildLink('register');
            $completeUrl = $this->buildLink('register/complete');

            $userUpgradeId = $this->filter('thmonetize_user_upgrade_id', 'int');
            $params = [
                'user_upgrade_id' => $userUpgradeId,
                'payment_profile_id' => $this->filter('thmonetize_payment_profile_id', 'int'),
            ];

            if ($reply->getUrl() === $registerUrl) {
                $reply->setUrl($this->buildLink('register', null, $params));
            } elseif ($reply->getUrl() === $completeUrl) {
                $userUpgrade = $this->app->em()->find('XF:UserUpgrade', $userUpgradeId);
                if ($userUpgrade) {
                    $reply->setUrl($this->buildLink('purchase', $userUpgrade, $params));
                }
            }
        }

        return $reply;
    }

    /**
     * @return Redirect|View
     */
    public function actionComplete()
    {
        $visitor = \XF::visitor();
        if ($visitor->user_state === 'thmonetize_upgrade') {
            return $this->redirect($this->buildLink('account/upgrades'));
        }

        return parent::actionComplete();
    }
}
