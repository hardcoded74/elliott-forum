<?php

namespace ThemeHouse\Monetize\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class Sponsor
 * @package ThemeHouse\Monetize\Repository
 */
class Sponsor extends Repository
{
    /**
     * @return \ThemeHouse\Monetize\Finder\Sponsor
     */
    public function findSponsorsForList()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->finder('ThemeHouse\Monetize:Sponsor')->order(['th_sponsor_id']);
    }
}
