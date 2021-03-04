<?php

namespace ThemeHouse\Nodes\Entity;

use XF\Entity\AbstractNode;
use XF\Mvc\Entity\Structure;

class LayoutSeparator extends AbstractNode
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_node_layout_separator';
        $structure->shortName = 'ThemeHouse\Nodes:LayoutSeparator';
        $structure->primaryKey = 'node_id';
        $structure->columns = [
            'node_id' => ['type' => self::UINT, 'required' => true],
            'separator_type' => ['type' => self::STR, 'default' => 'grid'],
            'separator_max_width' => ['type' => self::UINT, 'default' => 0],

        ];
        $structure->getters = [
        ];
        $structure->relations = [
        ];

        $structure->options = [
        ];

        self::addDefaultNodeElements($structure);

        return $structure;
    }

    public function getNodeTemplateRenderer($depth)
    {
        return [
            'template' => 'th_node_list_separator_nodes',
            'macro'    => 'renderSeparator'
        ];
    }

    public function getNodeListExtras()
    {
        return [
            'separator' => [
                'separator_type' => $this->separator_type,
                'separator_max_width' => $this->separator_max_width,
            ],
        ];
    }

}