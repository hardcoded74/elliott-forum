<?php

namespace ThemeHouse\Monetize\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Alert
 * @package ThemeHouse\Monetize\Repository
 */
class Alert extends Repository
{
    /**
     * @return Finder
     */
    public function findAlertsForList()
    {
        return $this->finder('ThemeHouse\Monetize:Alert')->order(['alert_id']);
    }
}
