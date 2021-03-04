<?php

namespace ThemeHouse\Monetize\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class UpgradePage
 * @package ThemeHouse\Monetize\Finder
 */
class UpgradePage extends Finder
{
    /**
     * @return $this
     */
    public function activeOnly()
    {
        $this->where('active', 1);

        return $this;
    }

    /**
     * @return $this
     */
    public function notShowAll()
    {
        $this->where('show_all', 0);

        return $this;
    }
}
