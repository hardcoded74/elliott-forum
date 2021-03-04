<?php

namespace ThemeHouse\Monetize\XF\Purchasable;

use XF\Entity\User;
use XF\Http\Request;
use XF\Payment\CallbackState;

/**
 * Class UserUpgrade
 * @package ThemeHouse\Monetize\XF\Purchasable
 */
class UserUpgrade extends XFCP_UserUpgrade
{
    /**
     * @param Request $request
     * @param User $purchaser
     * @param null $error
     * @return bool|\XF\Purchasable\Purchase
     */
    public function getPurchaseFromRequest(Request $request, User $purchaser, &$error = null)
    {
        $purchase = parent::getPurchaseFromRequest($request, $purchaser, $error);

        if ($purchase) {
            $userUpgradeId = $purchase->purchasableId;
            /** @var \ThemeHouse\Monetize\XF\Entity\UserUpgrade $userUpgrade */
            $userUpgrade = \XF::em()->find('XF:UserUpgrade', $userUpgradeId);
            if ($userUpgrade && $userUpgrade->thmonetize_custom_amount) {
                $costAmount = $request->filter('thmonetize_cost_amount', 'num');
                if ($costAmount < $userUpgrade->cost_amount) {
                    $error = \XF::phrase('thmonetize_amount_must_be_greater_than_x', [
                        'cost' => \XF::app()->data('XF:Currency')->languageFormat(
                            $userUpgrade->cost_amount,
                            $userUpgrade->cost_currency
                        ),
                    ]);
                    return false;
                }

                if ($costAmount) {
                    $purchase->cost = $costAmount;
                }
            }

            return $purchase;
        }

        return false;
    }

    /**
     * @param CallbackState $state
     * @return null
     */
    public function sendPaymentReceipt(CallbackState $state)
    {
        if ($state->getPurchaseRequest()->cost_amount) {
            return parent::sendPaymentReceipt($state);
        }
    }
}
