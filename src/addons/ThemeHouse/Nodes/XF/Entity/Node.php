<?php

namespace ThemeHouse\Nodes\XF\Entity;

class Node extends XFCP_Node
{
    public function getNodeStylingForStyle($styleId)
    {
        return $this->finder('ThemeHouse\Nodes:NodeStyling')->where('node_id', $this->node_id)->where('style_id', $styleId)->fetchOne();
    }

    public function getNodeStylingFromCacheForStyle($styleId = null)
    {
        if (!$styleId) {
            $styleId = \XF::app()->style()->getId();
        }

        $registry = $this->app()->registry();

        $nodeStyling = $registry->get('th_nodeStyling_nodes');

        if (!isset($nodeStyling[$styleId][$this->node_id])) {
            return false;
        }

        return $nodeStyling[$styleId][$this->node_id];
    }

    public function getNodeStylingInheritedFeature($styleId = null, $itemType, $featureKey)
    {
        if (!$styleId) {
            $styleId = \XF::app()->style()->getId();
        }

        $registry = $this->app()->registry();

        $nodeStyling = $registry->get('th_nodeStyling_nodes');

        if (!isset($nodeStyling[$styleId][$this->node_id][$itemType][$featureKey])) {
            return false;
        }

        $item = $nodeStyling[$styleId][$this->node_id][$itemType][$featureKey];

        return $item;
    }

    protected function _postDelete()
    {
        parent::_postDelete();

        $db = $this->db();
        $db->beginTransaction();
        $db->delete('xf_th_node_styling', 'node_id IN (' . $db->quote($this->node_id) . ')');
        $db->commit();

        /** @var \ThemeHouse\Nodes\Repository\NodeStyling $repo */
        $repo = $this->repository('ThemeHouse\Nodes:NodeStyling');

        $repo->rebuildNodeStylingCache();
    }
}