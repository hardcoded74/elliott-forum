<?php

namespace ThemeHouse\UIX\XF\Entity;

/**
 * Class Node
 * @package ThemeHouse\UIX\XF\Entity
 */
class Node extends XFCP_Node
{
    /**
     * @return bool
     */
    public function isCollapsed_uix()
    {
        $storageKey = 'th_uix_collapsedCategories';
        $collapsedCategories = [];
        if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
            $collapsedCategories = \XF::session()->get($storageKey);
        } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
            $collapsedCategories = \XF::app()->request()->getCookie($storageKey);
        }
        if (empty($collapsedCategories)) {
            $collapsedCategories = [];
        } elseif (!is_array($collapsedCategories)) {
            $collapsedCategories = explode('.', $collapsedCategories);
        }
        if (in_array($this->node_id, $collapsedCategories)) {
            return true;
        }

        return false;
    }
}
