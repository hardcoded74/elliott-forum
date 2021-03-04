<?php

namespace ThemeHouse\Nodes\Repository;

use XF\Mvc\Entity\Repository;

class NodeStyling extends Repository
{
    public function getNodeStylingForNodeIds($nodeIds)
    {
        /** @var \XF\Mvc\Entity\Finder $finder */
        $finder = $this->finder('ThemeHouse\Nodes:NodeStyling');

        return $finder->where('node_id', '=', $nodeIds)->fetch();
    }

    public function getNodeStylingForDefault($styleId)
    {
        /** @var \XF\Mvc\Entity\Finder $finder */
        $finder = $this->finder('ThemeHouse\Nodes:NodeStyling');

        return $finder->where('node_id', '=', 0)->where('style_id', '=', $styleId)->fetchOne();
    }

    public function getDefaultNodeStylingForNode(\XF\Entity\Node $node = null, $styleId)
    {
        $nodeId = 0;
        if ($node && $node->node_id) {
            $nodeId = $node->node_id;
        }

        $nodeStyling = $this->em->create('ThemeHouse\Nodes:NodeStyling');
        $nodeStyling->bulkSet([
            'node_id' => $nodeId,
            'style_id' => $styleId
        ]);

        if (!$node) {
            $nodeStyling->inherit_styling = 0;
            $nodeStyling->inherit_grid = 0;
        }

        return $nodeStyling;
    }

    public function getNodeStylingCache()
    {
        $registry = \XF::registry();

        return $registry->get('th_nodeStyling_nodes');
    }

    public function getStandardizedNodeStylingCache($returnStyleId = null)
    {
        $nodeStyling = $this->getNodeStylingCache();
        if ($nodeStyling === null) {
            $nodeStyling = $this->rebuildNodeStylingCache();
        }

        if (!$nodeStyling) {
            return [];
        }

        foreach ($nodeStyling as $styleId => &$nodes) {
            foreach ($nodes as $nodeId => &$node) {
                $node = array_merge([
                    'inherit_styling' => false,
                    'styling_options' => [],
                ], $node);

                $node['styling_options'] = array_merge([
                    'class_name' => '',
                    'background_image_url' => '',
                    'background_color' => '',
                    'text_color' => '',
                ], $node['styling_options']);

                if (isset($node['grid_options'])) {
                    $node['grid_options'] = array_merge([
                        'max_columns' => [
                            'enabled' => 0
                        ],
                        'min_column_width' => [
                            'enabled' => 0
                        ],
                        'fill_last_row' => [
                            'enabled' => 0
                        ],
                    ], $node['grid_options']);
                }
            }
        }


        if ($returnStyleId === null) {
            $returnStyling = $nodeStyling;
        } else {
            if (isset($nodeStyling[$returnStyleId])) {
                $returnStyling = $nodeStyling[$returnStyleId];
            } else {
                $returnStyling = [];
            }
        }

        return $returnStyling;
    }

    public function rebuildNodeStylingCache()
    {
        $service = \XF::app()->service('ThemeHouse\Nodes:NodeStyling\Cache');
        return $service->rebuildCache();
    }

    public function buildNodeStylingTemplate($styles)
    {
        $cssOutput = '<xf:comment>Do not edit this template directly, everything contained within this template is automatically generated and manual changes will cause issues</xf:comment>';
        foreach ($styles as $style) {
            $styleManager = \XF::app()->style($style->style_id);
            $styling = $this->renderNodeStylingForStyle($styleManager);
            if ($styling) {
                $cssOutput .= "\n\n<xf:if is=\"\$xf.style.style_id === {$style->style_id}\">\n";
                $cssOutput .= $styling;
                $cssOutput .= "</xf:if>";
            }
        }

        $template = $this->finder('XF:Template')->where('style_id', '=', 0)->where('title', '=', 'th_nodeStyling_nodes.css')->fetchOne();
        if (empty($template)) {
            $template = $this->em->create('XF:Template');
            $template->bulkSet([
                'type' => 'public',
                'title' => 'th_nodeStyling_nodes.css',
                'style_id' => 0,
            ]);
        }

        $template->template = $cssOutput;
        $template->addon_id = '';
        $template->last_edit_date = \XF::$time;
        $template->save();

        return $template;
    }

    public function renderNodeStylingForStyle(\XF\Style $style)
    {
        $styleId = $style->getId();
        $nodeStylingCache = $this->getStandardizedNodeStylingCache($styleId);

        // Style properties necessary for generating css
        $backgroundSelector = $style->getProperty('th_backgroundSelector_nodes');
        $textSelector = $style->getProperty('th_textSelector_nodes');

        $cssOutput = '';
        foreach ($nodeStylingCache as $nodeId => $options) {
            $nodeBackgroundSelector = $this->processSelectorForNode($backgroundSelector, $nodeId);
            $nodeTextSelector = $this->processSelectorForNode($textSelector, $nodeId);

            $cssProperties = '';

            if (isset($options['styling_options']['background_image_url']['enable']) && $options['styling_options']['background_image_url']['enable'] && $options['styling_options']['background_image_url']['value']) {
                $cssProperties .= "  background-image: url({$options['styling_options']['background_image_url']['value']}) !important;\n";
                $cssProperties .= "  background-size: cover;\n";
            }
            if (isset($options['styling_options']['background_color']['enable']) && $options['styling_options']['background_color']['enable'] && $options['styling_options']['background_color']['value']) {
                $cssProperties .= "  background-color: {$options['styling_options']['background_color']['value']} !important;\n";
            }
            if ($cssProperties) {
                $cssOutput .= "{$nodeBackgroundSelector} {\n";
                $cssOutput .= $cssProperties;
                $cssOutput .= "}\n\n";
            }

            if (isset($options['styling_options']['text_color']['enable']) && $options['styling_options']['text_color']['enable'] && $options['styling_options']['text_color']['value']) {
                $cssOutput .= "{$nodeTextSelector} {\n";
                if ($options['styling_options']['background_color']) {
                    $cssOutput .= "  color: {$options['styling_options']['text_color']['value']} !important;\n";
                }
                $cssOutput .= "}\n\n";
            }
        }

        return $cssOutput;
    }

    public function getClassesForNode(\XF\Entity\Node $node, Array $extras = [])
    {
        $nodeStylingCache = $node->getNodeStylingFromCacheForStyle();
        $classes = [];

        if ($node->depth && $this->hasNodeIcon($node, $extras)) {
            $classes[] = 'th_node--hasCustomIcon';
        }

        if (isset($nodeStylingCache['styling_options']['class_name']['enable'])
            && $nodeStylingCache['styling_options']['class_name']['enable']
            && !empty($nodeStylingCache['styling_options']['class_name']['value'])) {
            $classes[] = $nodeStylingCache['styling_options']['class_name']['value'];

            if ($nodeStylingCache['styling_options']['class_name']['inherited']) {
                $classes[] = $nodeStylingCache['styling_options']['class_name']['value'] . '--inherited';
            }
        }

        if (isset($nodeStylingCache['styling_options']['retain_text_styling']['enable'])
            && $nodeStylingCache['styling_options']['retain_text_styling']['enable']
            && !empty($nodeStylingCache['styling_options']['retain_text_styling']['value'])
            && $nodeStylingCache['styling_options']['retain_text_styling']['value'] === '1') {
            $classes[] = 'th_node--retainTextStyling';
        } else {
            $classes[] = 'th_node--overwriteTextStyling';
        }

        if (isset($nodeStylingCache['styling_options']['background_image_url']['enable'])
            && $nodeStylingCache['styling_options']['background_image_url']['enable']
            && !empty($nodeStylingCache['styling_options']['background_image_url']['value'])) {
            $classes[] = 'th_node--hasBackground';
            $classes[] = 'th_node--hasBackgroundImage';
        } elseif (isset($nodeStylingCache['styling_options']['background_color']['enable'])
            && $nodeStylingCache['styling_options']['background_color']['enable']
            && !empty($nodeStylingCache['styling_options']['background_color']['value'])) {
            $classes[] = 'th_node--hasBackground';
        }

        return $classes;
    }

    public function getNodeIconClass(\XF\Entity\Node $node, $extras = [])
    {
        $featureKey = false;
        switch ($node->node_type_id) {
            case 'Forum':
                if ($extras['hasNew']) {
                    $featureKey = 'forum_icon_class_unread';
                } else {
                    $featureKey = 'forum_icon_class';
                }
                break;
            case 'Category':
                if ($extras['hasNew']) {
                    $featureKey = 'category_icon_class_unread';
                } else {
                    $featureKey = 'category_icon_class';
                }
                break;
            case 'Page':
                $featureKey = 'page_icon_class';
                break;
            case 'LinkForum':
                $featureKey = 'link_forum_icon_class';
                break;
        }

        if (!$featureKey) {
            return false;
        }

        $nodeStylingCache = $node->getNodeStylingFromCacheForStyle();

        if (isset($nodeStylingCache['styling_options'][$featureKey])) {
            $icon = $nodeStylingCache['styling_options'][$featureKey];
            if (isset($icon['enable']) && $icon['enable'] && isset($icon['value']) && $icon['value']) {
                return [
                    'class_name' => $icon['value'],
                    'inherited' => $icon['inherited'],
                ];
            }
        }

        return false;
    }

    protected function hasNodeIcon(\XF\Entity\Node $node, Array $extras = [])
    {
        $iconClass = $this->getNodeIconClass($node, $extras);

        if ($iconClass) {
            return true;
        }

        return false;
    }

    protected function processSelectorForNode($selector, $nodeId)
    {
        return str_ireplace('{node_id}', $nodeId, $selector);
    }
}