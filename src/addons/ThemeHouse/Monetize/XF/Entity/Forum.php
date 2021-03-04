<?php

namespace ThemeHouse\Monetize\XF\Entity;

/**
 * Class Forum
 * @package ThemeHouse\Monetize\XF\Entity
 */
class Forum extends XFCP_Forum
{
    /**
     * @return array
     */
    public static function getListedWith()
    {
        $with = parent::getListedWith();

        $with[] = "Node.Sponsor";

        return $with;
    }
}
