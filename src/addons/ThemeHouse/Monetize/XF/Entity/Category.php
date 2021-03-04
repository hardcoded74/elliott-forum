<?php

namespace ThemeHouse\Monetize\XF\Entity;

/**
 * Class Category
 * @package ThemeHouse\Monetize\XF\Entity
 */
class Category extends XFCP_Category
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
