<?php

namespace ThemeHouse\Monetize\XF\Pub\Controller;

use ThemeHouse\Monetize\XF\Entity\PaymentProfile;
use ThemeHouse\Monetize\XF\Entity\UserUpgrade;
use XF\Mvc\ParameterBag;

/**
 * Class Purchase
 * @package ThemeHouse\Monetize\XF\Pub\Controller
 */
class Purchase extends XFCP_Purchase
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        if ($params['purchasable_type_id'] === 'user_upgrade') {
            $userUpgradeId = $this->filter('user_upgrade_id', 'int');
            $paymentProfileId = $this->filter('payment_profile_id', 'int');

            if ($userUpgradeId) {
                if (!\XF::visitor()->user_id) {
                    return $this->redirect($this->buildLink('register', '', [
                        'user_upgrade_id' => $userUpgradeId,
                        'payment_profile_id' => $paymentProfileId,
                    ]));
                }

                /** @var UserUpgrade $userUpgrade */
                $userUpgrade = \XF::em()->find('XF:UserUpgrade', $userUpgradeId);
                if ($userUpgrade && $userUpgrade->canPurchase()) {
                    $costAmount = $this->filter('thmonetize_cost_amount', 'num');
                    if ($userUpgrade->thmonetize_custom_amount && !$costAmount) {
                        /** @var PaymentProfile $paymentProfile */
                        $paymentProfile = \XF::em()->find('XF:PaymentProfile', $paymentProfileId);

                        if (!$paymentProfile->thMonetizeSupportsCustomAmount($userUpgrade->recurring)) {
                            $viewParams = [
                                'upgrade' => $userUpgrade,
                                'paymentProfile' => $paymentProfile,
                            ];

                            return $this->view(
                                'ThemeHouse\Monetize:UpgradeCustom',
                                'thmonetize_upgrade_custom',
                                $viewParams
                            );
                        }
                    }
                }
            }
        }

        return parent::actionIndex($params);
    }
}
