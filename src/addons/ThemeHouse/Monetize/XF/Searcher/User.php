<?php

namespace ThemeHouse\Monetize\XF\Searcher;

/**
 * Class User
 * @package ThemeHouse\Monetize\XF\Searcher
 */
class User extends XFCP_User
{
    /**
     * @return array
     */
    public function getFormDefaults()
    {
        $formDefaults = parent::getFormDefaults();

        $formDefaults['user_state'][] = 'thmonetize_upgrade';

        return $formDefaults;
    }
}
