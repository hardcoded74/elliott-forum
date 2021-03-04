<?php

namespace ThemeHouse\UIX\XF\Entity;

/**
 * Class Style
 * @package ThemeHouse\UIX\XF\Entity
 *
 * @property integer th_product_id_uix
 * @property string th_product_version_uix
 * @property bool th_primary_child_uix
 * @property string th_child_style_xml_uix
 * @property array th_child_style_cache_uix
 */
class Style extends XFCP_Style
{
    /**
     * @return array
     */
    public function getChildStyles()
    {
        if (empty($this->th_child_style_cache_uix)) {
            return [];
        }

        $childStyles = $this->th_child_style_cache_uix;

        $childStyleXmls = array_keys($childStyles);
        $installedChildStyles = $this->finder('XF:Style')->where('th_child_style_xml_uix', '=',
            $childStyleXmls)->whereSql('FIND_IN_SET(' . $this->style_id . ', parent_list)')->fetch();
        $childStyleMap = [];

        foreach ($installedChildStyles as $style) {
            if (empty($childStyles[$style->th_child_style_xml_uix])) {
                continue;
            }
            $childStyleMap[$style->th_child_style_xml_uix] = $style->style_id;
        }

        $return = [];

        foreach ($this->th_child_style_cache_uix as $xmlName => $childStyleName) {
            $installed = false;
            if (!empty($childStyleMap[$xmlName])) {
                $installed = $installedChildStyles[$childStyleMap[$xmlName]];
            }
            $return[$xmlName] = [
                'style_name' => $childStyleName,
                'installed' => $installed,
            ];
        }

        return $return;
    }
}
