<?php

namespace ThemeHouse\Monetize\Job;

use ThemeHouse\Monetize\Entity\Email;

/**
 * Class SendEmail
 * @package ThemeHouse\Monetize\Job
 */
class SendEmail extends AbstractCommunication
{
    /**
     * @return \XF\Phrase
     */
    protected function getTypePhrase()
    {
        return \XF::phrase('thmonetize_emails');
    }

    /**
     * @return null|\XF\Mvc\Entity\Entity
     */
    protected function getContent()
    {
        if (empty($this->data['email_id'])) {
            return null;
        }
        return \XF::em()->find('ThemeHouse\Monetize:Email', intval($this->data['email_id']));
    }

    /**
     * @param $content
     * @param $user
     * @param $userUpgrades
     * @throws \XF\PrintableException
     */
    protected function processContent($content, $user, $userUpgrades)
    {
        /** @var Email $content */
        $content->sendEmailForUser($user, $userUpgrades);
    }
}
