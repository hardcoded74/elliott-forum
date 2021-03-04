<?php

namespace ThemeHouse\Monetize\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Sponsor
 * @package ThemeHouse\Monetize\Finder
 */
class Sponsor extends Finder
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
    public function inDirectory()
    {
        $this->where('directory', 1);

        return $this;
    }
}
