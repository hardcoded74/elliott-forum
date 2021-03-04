<?php

namespace ThemeHouse\Monetize\XF\Entity;

/**
 * Class PaymentProfile
 * @package ThemeHouse\Monetize\XF\Entity
 */
class PaymentProfile extends XFCP_PaymentProfile
{
    /**
     * @param bool $recurring
     * @return bool
     */
    public function thMonetizeSupportsCustomAmount($recurring = false)
    {
        $handler = $this->getPaymentHandler();
        if ($handler && method_exists($handler, 'thMonetizeSupportsCustomAmount')) {
            return $handler->thMonetizeSupportsCustomAmount($recurring);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function thMonetizeSupportsZeroCost()
    {
        if ($this->provider_id === 'thmonetize_free') {
            return true;
        }

        return false;
    }
}
