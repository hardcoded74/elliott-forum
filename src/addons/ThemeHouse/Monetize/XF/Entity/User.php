<?php

namespace ThemeHouse\Monetize\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class User
 * @package ThemeHouse\Monetize\XF\Entity
 */
class User extends XFCP_User
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['user_state']['allowedValues'][] = 'thmonetize_upgrade';

        return $structure;
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canViewFullProfile(&$error = null)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == $this->user_id) {
            return true;
        }

        if ($this->user_state == 'thmonetize_upgrade' && !$visitor->canBypassUserPrivacy()) {
            $error = \XF::phraseDeferred('this_users_profile_is_not_available');
            return false;
        }

        return parent::canViewFullProfile($error);
    }
}
