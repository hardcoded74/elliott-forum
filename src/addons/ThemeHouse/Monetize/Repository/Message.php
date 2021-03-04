<?php

namespace ThemeHouse\Monetize\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Message
 * @package ThemeHouse\Monetize\Repository
 */
class Message extends Repository
{
    /**
     * @return Finder
     */
    public function findMessagesForList()
    {
        return $this->finder('ThemeHouse\Monetize:Message')->order(['message_id']);
    }
}
