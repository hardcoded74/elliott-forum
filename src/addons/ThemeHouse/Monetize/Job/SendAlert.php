<?php

namespace ThemeHouse\Monetize\Job;

use ThemeHouse\Monetize\Entity\Alert;

/**
 * Class SendAlert
 * @package ThemeHouse\Monetize\Job
 */
class SendAlert extends AbstractCommunication
{
    /**
     * @return \XF\Phrase
     */
    protected function getTypePhrase()
    {
        return \XF::phrase('thmonetize_alerts');
    }

    /**
     * @return null|\XF\Mvc\Entity\Entity
     */
    protected function getContent()
    {
        if (empty($this->data['alert_id'])) {
            return null;
        }
        return \XF::em()->find('ThemeHouse\Monetize:Alert', intval($this->data['alert_id']));
    }

    /**
     * @param $content
     * @param $user
     * @param $userUpgrades
     * @throws \XF\PrintableException
     */
    protected function processContent($content, $user, $userUpgrades)
    {
        /** @var Alert $content */
        $content->sendAlertForUser($user, $userUpgrades);
    }
}
