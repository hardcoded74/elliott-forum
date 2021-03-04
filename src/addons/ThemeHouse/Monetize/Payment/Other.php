<?php

namespace ThemeHouse\Monetize\Payment;

use ThemeHouse\Monetize\XF\Repository\UserUpgrade;
use XF\Entity\PaymentProfile;
use XF\Entity\PurchaseRequest;
use XF\Http\Request;
use XF\Mvc\Controller;
use XF\Payment\AbstractProvider;
use XF\Payment\CallbackState;
use XF\Purchasable\Purchase;

/**
 * Class Other
 * @package ThemeHouse\Monetize\Payment
 */
class Other extends AbstractProvider
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return '[TH] Monetize: Other';
    }

    /**
     * @param Controller $controller
     * @param PurchaseRequest $purchaseRequest
     * @param Purchase $purchase
     * @return \XF\Mvc\Reply\Redirect
     */
    public function initiatePayment(Controller $controller, PurchaseRequest $purchaseRequest, Purchase $purchase)
    {
        $paymentProfile = $purchase->paymentProfile;

        if ($purchase->purchasableTypeId === 'user_upgrade') {
            $userUpgradeId = $purchase->purchasableId;

            if (!empty($paymentProfile->options['alt_url.' . $userUpgradeId])) {
                return $controller->redirect($paymentProfile->options['alt_url.' . $userUpgradeId]);
            }
        }

        return $controller->notFound();
    }

    /**
     * @param Request $request
     *
     * @return CallbackState
     */
    public function setupCallback(Request $request)
    {
        return new CallbackState();
    }

    /**
     * @param CallbackState $state
     */
    public function getPaymentResult(CallbackState $state)
    {
    }

    /**
     * @param CallbackState $state
     */
    public function prepareLogData(CallbackState $state)
    {
    }

    /**
     * @param bool $recurring
     * @return bool
     */
    public function thMonetizeSupportsCustomAmount($recurring = false)
    {
        return true;
    }

    /**
     * @param PaymentProfile $paymentProfile
     * @param $unit
     * @param $amount
     * @param int $result
     * @return bool
     */
    public function supportsRecurring(
        PaymentProfile $paymentProfile,
        $unit,
        $amount,
        &$result = AbstractProvider::ERR_NO_RECURRING
    ) {
        $result = AbstractProvider::ERR_NO_RECURRING;

        return false;
    }

    /**
     * @param PaymentProfile $profile
     * @return string
     */
    public function renderConfig(PaymentProfile $profile)
    {
        /** @var UserUpgrade $upgradeRepo */
        $upgradeRepo = \XF::repository('XF:UserUpgrade');

        $data = [
            'profile' => $profile,
            'userUpgrades' => $upgradeRepo->findUserUpgradesForList()->fetch(),
        ];
        return \XF::app()->templater()->renderTemplate('admin:payment_profile_' . $this->providerId, $data);
    }

    /**
     * @return array
     */
    protected function getSupportedRecurrenceRanges()
    {
        return [];
    }
}
