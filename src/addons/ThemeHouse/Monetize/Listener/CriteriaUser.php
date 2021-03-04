<?php

namespace ThemeHouse\Monetize\Listener;

use ThemeHouse\Monetize\XF\Entity\UserProfile;
use XF\Entity\User;

/**
 * Class CriteriaUser
 * @package ThemeHouse\Monetize\Listener
 */
class CriteriaUser
{
    /**
     * @param $rule
     * @param array $data
     * @param User $user
     * @param $returnValue
     */
    public static function criteriaUser($rule, array $data, User $user, &$returnValue)
    {
        /** @var UserProfile $profile */
        $profile = $user->Profile;

        switch ($rule) {
            case 'thmonetize_active_upgrades_count':
                if ($profile->getThMonetizeActiveUpgradesCount() >= $data['upgrades']) {
                    $returnValue = true;
                }
                break;

            case 'thmonetize_active_upgrades_maximum':
                if ($profile->getThMonetizeActiveUpgradesCount() <= $data['upgrades']) {
                    $returnValue = true;
                }
                break;

            case 'thmonetize_active_upgrades_expiry':
                $cutOff = \XF::$time + ($data['days'] * 86400);
                $expiryDate = $profile->getThMonetizeActiveUpgradesEndDate();
                if ($expiryDate && $expiryDate < $cutOff) {
                    $returnValue = true;
                }
                break;
        }
    }
}
