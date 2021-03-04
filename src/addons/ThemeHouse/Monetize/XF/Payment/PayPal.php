<?php

namespace ThemeHouse\Monetize\XF\Payment;

use ThemeHouse\Monetize\XF\Entity\UserUpgrade;
use XF\Entity\PurchaseRequest;
use XF\Payment\CallbackState;
use XF\Purchasable\Purchase;

/**
 * Class PayPal
 * @package ThemeHouse\Monetize\XF\Payment
 */
class PayPal extends XFCP_PayPal
{
    /**
     * @param bool $recurring
     * @return bool
     */
    public function thMonetizeSupportsCustomAmount($recurring = false)
    {
        return !$recurring;
    }

    /**
     * @param CallbackState $state
     * @return bool
     */
    public function validateCost(CallbackState $state)
    {
        $purchaseRequest = $state->getPurchaseRequest();
        /** @noinspection PhpUndefinedFieldInspection */
        if (!$state->legacy && $purchaseRequest->purchasable_type_id === 'user_upgrade') {
            if (isset($purchaseRequest->extra_data['user_upgrade_id'])) {
                $userUpgradeId = $purchaseRequest->extra_data['user_upgrade_id'];
                /** @var UserUpgrade $userUpgrade */
                $userUpgrade = \XF::em()->find('XF:UserUpgrade', $userUpgradeId);
                if (!$userUpgrade->recurring && $userUpgrade->thmonetize_custom_amount) {
                    return true;
                }
            }
        }

        return parent::validateCost($state);
    }

    /**
     * @param PurchaseRequest $purchaseRequest
     * @param Purchase $purchase
     * @return array
     */
    protected function getPaymentParams(PurchaseRequest $purchaseRequest, Purchase $purchase)
    {
        $params = parent::getPaymentParams($purchaseRequest, $purchase);

        if ($purchaseRequest->purchasable_type_id === 'user_upgrade') {
            if (!$purchase->recurring && isset($purchaseRequest->extra_data['user_upgrade_id'])) {
                $userUpgradeId = $purchaseRequest->extra_data['user_upgrade_id'];
                /** @var UserUpgrade $userUpgrade */
                $userUpgrade = \XF::em()->find('XF:UserUpgrade', $userUpgradeId);
                if ($userUpgrade->thmonetize_custom_amount) {
                    unset($params['amount']);
                }
            }
        }

        return $params;
    }
}
