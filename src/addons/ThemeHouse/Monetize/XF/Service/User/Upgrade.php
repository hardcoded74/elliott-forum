<?php

namespace ThemeHouse\Monetize\XF\Service\User;

use XF\Entity\ConnectedAccountProvider;
use XF\Repository\ConnectedAccount;

/**
 * Class Upgrade
 * @package ThemeHouse\Monetize\XF\Service\User
 */
class Upgrade extends XFCP_Upgrade
{
    /**
     * @return bool|\XF\Entity\UserUpgradeActive
     * @throws \XF\PrintableException
     */
    public function upgrade()
    {
        $active = parent::upgrade();
        $user = $this->user;

        if ($user->user_state === 'thmonetize_upgrade') {
            $options = $this->app->options();

            /** @var ConnectedAccount $connectedAccountRepo */
            $connectedAccountRepo = $this->repository('XF:ConnectedAccount');
            $providers = $connectedAccountRepo->getUsableProviders();

            $skipEmailConfirm = false;
            foreach ($providers as $provider) {
                /** @var ConnectedAccountProvider $provider */
                if ($provider->isAssociated($user)) {
                    $providerData = $provider->getUserInfo($user);
                    if ($providerData->email) {
                        $skipEmailConfirm = true;
                    }
                }
            }

            if ($options->registrationSetup['emailConfirmation'] && !$skipEmailConfirm) {
                $user->user_state = 'email_confirm';
            } elseif ($options->registrationSetup['moderation']) {
                $user->user_state = 'moderated';
            } else {
                $user->user_state = 'valid';
            }
            $user->save();

            if ($user->user_state == 'email_confirm') {
                /** @var \XF\Service\User\EmailConfirmation $emailConfirmation */
                $emailConfirmation = $this->service('XF:User\EmailConfirmation', $user);
                $emailConfirmation->triggerConfirmation();
            } elseif ($user->user_state == 'valid') {
                /** @var \XF\Service\User\Welcome $userWelcome */
                $userWelcome = $this->service('XF:User\Welcome', $user);
                $userWelcome->send();
            }
        }

        return $active;
    }
}
