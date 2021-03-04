<?php

namespace ThemeHouse\Monetize\Listener;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Manager;
use XF\Mvc\Entity\Structure;

/**
 * Class EntityStructure
 * @package ThemeHouse\Monetize\Listener
 */
class EntityStructure
{
    /**
     * @param Manager $em
     * @param Structure $structure
     */
    public static function xfNode(Manager $em, Structure &$structure)
    {
        $structure->columns['th_sponsor_id'] = ['type' => Entity::UINT, 'default' => 0];

        $structure->relations['Sponsor'] = [
            'entity' => 'ThemeHouse\Monetize:Sponsor',
            'type' => Entity::TO_ONE,
            'conditions' => 'th_sponsor_id'
        ];
    }
}
