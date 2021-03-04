<?php

namespace ThemeHouse\Monetize\Payment;

use XF\Entity\PaymentProfile;
use XF\Entity\PurchaseRequest;
use XF\Http\Request;
use XF\Mvc\Controller;
use XF\Payment\AbstractProvider;
use XF\Payment\CallbackState;
use XF\Purchasable\Purchase;

/**
 * Class Free
 * @package ThemeHouse\Monetize\Payment
 */
class Free extends AbstractProvider
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return '[TH] Monetize: Free';
    }

    /**
     * @param Controller $controller
     * @param PurchaseRequest $purchaseRequest
     * @param Purchase $purchase
     * @return \XF\Mvc\Reply\Redirect
     */
    public function initiatePayment(Controller $controller, PurchaseRequest $purchaseRequest, Purchase $purchase)
    {
        $request = \XF::app()->request();

        $state = $this->setupCallback($request);
        $state->purchaseRequest = $purchaseRequest;
        $this->getPaymentResult($state);
        $this->completeTransaction($state);

        $visitor = \XF::visitor();
        if ($visitor->register_date > \XF::$time - 3600 || $visitor->user_state !== 'valid') {
            return $controller->redirect($controller->buildLink('register/complete'));
        }

        return $controller->redirect($controller->buildLink('account/upgrade-free'));
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
        $state->paymentResult = CallbackState::PAYMENT_RECEIVED;
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
     * @return array
     */
    protected function getSupportedRecurrenceRanges()
    {
        return [];
    }
}
