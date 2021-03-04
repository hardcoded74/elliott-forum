<?php

namespace ThemeHouse\Monetize\XF\ChangeLog;

/**
 * Class User
 * @package ThemeHouse\Monetize\XF\ChangeLog
 */
class User extends XFCP_User
{
    /**
     * @param $value
     * @return \XF\Phrase
     */
    protected function formatUserState($value)
    {
        if ($value === 'thmonetize_upgrade') {
            return \XF::phrase('thmonetize_awaiting_user_upgrade_purchase');
        }

        return parent::formatUserState($value);
    }
}
