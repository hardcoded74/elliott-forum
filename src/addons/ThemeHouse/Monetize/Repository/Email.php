<?php

namespace ThemeHouse\Monetize\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Email
 * @package ThemeHouse\Monetize\Repository
 */
class Email extends Repository
{
    /**
     * @return Finder
     */
    public function findEmailsForList()
    {
        return $this->finder('ThemeHouse\Monetize:Email')->order(['email_id']);
    }
}
