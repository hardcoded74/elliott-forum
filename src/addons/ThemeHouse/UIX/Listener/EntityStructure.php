<?php

namespace ThemeHouse\UIX\Listener;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Manager;
use XF\Mvc\Entity\Structure;

/**
 * Class EntityStructure
 * @package ThemeHouse\UIX\Listener
 */
class EntityStructure
{
    /**
     * @param Manager $em
     * @param Structure $structure
     */
    public static function xfStyle(Manager $em, Structure &$structure)
    {
        $structure->columns += [
            'th_product_id_uix' => [
                'type' => Entity::UINT,
                'default' => 0,
            ],
            'th_product_version_uix' => [
                'type' => Entity::STR,
                'default' => null,
            ],
            'th_primary_child_uix' => [
                'type' => Entity::BOOL,
                'default' => 0,
            ],
            'th_child_style_xml_uix' => [
                'type' => Entity::STR,
                'default' => '',
            ],
            'th_child_style_cache_uix' => [
                'type' => Entity::JSON_ARRAY,
                'default' => [],
            ]
        ];
    }

    /**
     * @param Manager $em
     * @param Structure $structure
     */
    public static function xfUserOption(Manager $em, Structure &$structure)
    {
        $structure->columns += [
            'thuix_collapse_postbit' => [
                'type' => Entity::BOOL,
                'default' => intval(in_array(\XF::options()->th_sidebarNavCollapseDefault_uix, ['registered', 'all'])),
            ],
            'thuix_collapse_sidebar' => [
                'type' => Entity::UINT,
                'default' => intval(in_array(\XF::options()->th_sidebarCollapseDefault_uix, ['registered', 'all'])),
            ],
            'thuix_collapse_sidebar_nav' => [
                'type' => Entity::UINT,
                'default' => intval(in_array(\XF::options()->th_sidebarNavCollapseDefault_uix, ['registered', 'all'])),
            ],
            'thuix_font_size' => [
                'type' => Entity::INT,
                'default' => 0,
            ],
        ];
    }

    /**
     * @param Manager $em
     * @param Structure $structure
     */
    public static function xFNotice(Manager $em, Structure &$structure)
    {
        $structure->columns += [
            'thuix_icon' => [
                'type' => Entity::STR,
                'default' => 'mdi mdi-information'
            ]
        ];
    }
}
