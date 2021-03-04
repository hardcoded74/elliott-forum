<?php

namespace ThemeHouse\Nodes\Listener;

class TemplaterSetup
{
    public static function run(\XF\Container $container, \XF\Template\Templater &$templater)
    {
        $templater->addFunction('th_nodeclasses_nodes', ['\ThemeHouse\Nodes\Listener\TemplaterSetup', 'fnNodeClasses']);
        $templater->addFunction('th_inheritedstylingvalue_nodes', ['\ThemeHouse\Nodes\Listener\TemplaterSetup', 'fnInheritedStylingValue']);
        $templater->addFunction('th_nodeicon_nodes', ['\ThemeHouse\Nodes\Listener\TemplaterSetup', 'fnNodeIcon']);
        $templater->addFunction('th_grid_config_nodes', ['\ThemeHouse\Nodes\Listener\TemplaterSetup', 'fnGridConfig']);
    }

    public static function fnNodeClasses(\XF\Template\Templater $templater, &$escape, \XF\Entity\Node $node, Array $extras = [])
    {
        $classes = \XF::repository('ThemeHouse\Nodes:NodeStyling')->getClassesForNode($node, $extras);

        return implode(' ', $classes);
    }

    public static function fnInheritedStylingValue(\XF\Template\Templater $templater, &$escape, \XF\Entity\Node $node = null, \XF\Entity\Style $style = null, $itemType, $featureKey)
    {
        if (!$node || !$style) {
            return '';
        }

        $inheritedValuePhrase = \XF::phrase('th_inherited_value_nodes:');

        $inheritedFeature = $node->getNodeStylingInheritedFeature($style->style_id, $itemType, $featureKey);

        $inheritedValue = \XF::phrase('disabled');
        if ($inheritedFeature) {
            if ($inheritedFeature['inherited'] && $inheritedFeature['value']) {
                $inheritedValue = $inheritedFeature['value'];
            } elseif (!$inheritedFeature['inherited']) {
                return '';
            }
        }

        return $inheritedValuePhrase . ' ' . $inheritedValue;
    }

    public static function fnNodeIcon(\XF\Template\Templater $templater, &$escape, \XF\Entity\Node $node = null, Array $extras = [])
    {
        /** @var \ThemeHouse\Nodes\Repository\NodeStyling $nodeStylingRepo */
        $nodeStylingRepo = \XF::repository('ThemeHouse\Nodes:NodeStyling');

        $iconClass = $nodeStylingRepo->getNodeIconClass($node, $extras);
        if ($iconClass) {
            $escape = false;
            return '<span class="' . $iconClass['class_name'] . '"></span>';
        }
    }

    public static function fnGridConfig(\XF\Template\Templater $templater, &$escape)
    {
        /** @var \ThemeHouse\Nodes\Repository\NodeStyling $repo */
        $repo = \XF::repository('ThemeHouse\Nodes:NodeStyling');

        $data = $repo->getStandardizedNodeStylingCache($templater->getStyleId());

        foreach ($data as $nodeId => $option) {
            if (!isset($option['grid_options'])) continue;

            $returnData[$nodeId] = $option['grid_options'];
        }

        return 'window.themehouse.nodes.grid_options = ' . json_encode((object) $returnData) . ';'; // object so indexed by node_id
    }
}
