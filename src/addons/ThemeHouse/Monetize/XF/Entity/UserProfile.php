<?php

namespace ThemeHouse\Monetize\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class UserProfile
 * @package ThemeHouse\Monetize\XF\Entity
 *
 * @property array thmonetize_active_upgrades
 * @property array thmonetize_expired_upgrades
 */
class UserProfile extends XFCP_UserProfile
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['thmonetize_active_upgrades'] = ['type' => self::JSON_ARRAY, 'default' => []];
        $structure->columns['thmonetize_expired_upgrades'] = ['type' => self::JSON_ARRAY, 'default' => []];

        return $structure;
    }

    /**
     * @return int
     */
    public function getThMonetizeActiveUpgradesCount()
    {
        if ($this->thmonetize_active_upgrades) {
            return count($this->thmonetize_active_upgrades);
        }

        return 0;
    }

    /**
     * @return int|mixed
     */
    public function getThMonetizeActiveUpgradesEndDate()
    {
        if ($this->thmonetize_active_upgrades) {
            $expiryDates = array_filter(array_column($this->thmonetize_active_upgrades, 'end_date'));
            return $expiryDates ? min($expiryDates) : 0;
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getThMonetizeExpiredUpgradesCount()
    {
        if ($this->thmonetize_expired_upgrades) {
            return count($this->thmonetize_expired_upgrades);
        }

        return 0;
    }

    /**
     * @return int|mixed
     */
    public function getThMonetizeExpiredUpgradesEndDate()
    {
        if ($this->thmonetize_expired_upgrades) {
            $expiryDates = array_filter(array_column($this->thmonetize_expired_upgrades, 'end_date'));
            return $expiryDates ? min($expiryDates) : 0;
        }

        return 0;
    }
}
