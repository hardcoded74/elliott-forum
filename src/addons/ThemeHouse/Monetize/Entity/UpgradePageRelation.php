<?php

namespace ThemeHouse\Monetize\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int upgrade_page_id
 * @property int user_upgrade_id
 * @property int display_order
 * @property bool featured
 */
class UpgradePageRelation extends Entity
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_upgrade_page_relation';
        $structure->shortName = 'ThemeHouse\Monetize:UpgradePageRelation';
        $structure->primaryKey = ['upgrade_page_id', 'user_upgrade_id'];
        $structure->columns = [
            'upgrade_page_id' => ['type' => self::UINT, 'required' => true],
            'user_upgrade_id' => ['type' => self::UINT, 'required' => true],
            'display_order' => ['type' => self::UINT, 'default' => 1],
            'featured' => ['type' => self::BOOL, 'default' => false],
        ];
        $structure->getters = [];
        $structure->relations = [];

        return $structure;
    }
}
