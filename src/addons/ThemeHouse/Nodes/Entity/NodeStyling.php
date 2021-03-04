<?php

namespace ThemeHouse\Nodes\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class NodeStyling extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_node_styling';
        $structure->shortName = 'ThemeHouse\Nodes:NodeStyling';
        $structure->primaryKey = 'node_styling_id';
        $structure->columns = [
            'node_styling_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'node_id' => ['type' => self::UINT, 'required' => true],
            'style_id' => ['type' => self::UINT, 'default' => 0],
            'inherit_styling' => ['type' => self::BOOL, 'default' => 1],
            'styling_options' => ['type' => self::JSON, 'default' => []],
            'inherit_grid' => ['type' => self::BOOL, 'default' => 1],
            'grid_options' => ['type' => self::JSON_ARRAY, 'default' => []],
        ];
        $structure->relations = [
            'Node' => [
                'entity' => 'XF:Node',
                'type' => self::TO_ONE,
                'conditions' => 'node_id',
            ],
            'Style' => [
                'entity' => 'XF:Style',
                'type' => self::TO_ONE,
                'conditions' => 'style_id'
            ]
        ];

        return $structure;
    }
}