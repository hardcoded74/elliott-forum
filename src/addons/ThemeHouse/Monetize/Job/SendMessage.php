<?php

namespace ThemeHouse\Monetize\Job;

use ThemeHouse\Monetize\Entity\Message;

/**
 * Class SendMessage
 * @package ThemeHouse\Monetize\Job
 */
class SendMessage extends AbstractCommunication
{
    /**
     * @return null|\XF\Mvc\Entity\Entity
     */
    protected function getContent()
    {
        if (empty($this->data['message_id'])) {
            return null;
        }
        return \XF::em()->find('ThemeHouse\Monetize:Message', intval($this->data['message_id']));
    }

    /**
     * @param $content
     * @param $user
     * @param $userUpgrades
     * @throws \XF\PrintableException
     */
    protected function processContent($content, $user, $userUpgrades)
    {
        /** @var Message $content */
        $content->sendMessageForUser($user, $userUpgrades);
    }

    /**
     * @return \XF\Phrase
     */
    protected function getTypePhrase()
    {
        return \XF::phrase('thmonetize_messages');
    }
}
