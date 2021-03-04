<?php

namespace ThemeHouse\Monetize\XF\Service\User;

/**
 * Class Registration
 * @package ThemeHouse\Monetize\XF\Service\User
 */
class Registration extends XFCP_Registration
{
    /**
     *
     */
    protected function setInitialUserState()
    {
        $user = $this->user;
        $options = $this->app->options();

        if ($user->user_state != 'valid') {
            return null; // We have likely already set the user state elsewhere, e.g. spam trigger
        }

        if ($options->thmonetize_requireUserUpgradeOnRegistration) {
            $user->user_state = 'thmonetize_upgrade';
        } else {
            return parent::setInitialUserState();
        }

        return null;
    }
}
