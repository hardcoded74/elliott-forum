<?php

namespace ThemeHouse\Nodes\Listener;

use XF\Mvc\Entity\Entity;

class EntityStructure
{
    public static function xfNode(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
        $structure->relations['NodeStyling'] = [
            'entity' => 'ThemeHouse\Nodes:NodeStyling',
            'type' => Entity::TO_MANY,
            'conditions' => 'node_id',
        ];
    }
}