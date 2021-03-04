<?php

namespace ThemeHouse\Nodes\Service\NodeStyling;

use XF\Service\AbstractService;

class Cache extends AbstractService
{
    protected $nodes;
    protected $nodeTree;

    protected $nodeStyling;
    protected $nodeGrid;

    protected $styles = [];
    protected $styleTree;

    public function __construct(\XF\App $app)
    {
        parent::__construct($app);

        $nodeRepo = $this->getNodeRepo();
        $this->nodes = $nodeRepo->getNodeList();
        $this->nodeTree = count($this->nodes) ? $nodeRepo->createNodeTree($this->nodes) : null;

        $styleRepo = $this->getStyleRepo();
        $this->styleTree = $styleRepo->getStyleTree(true);

        $nodeIds = [0];
        foreach($this->nodes as $node) {
            $nodeIds[] = $node->node_id;
        }

        $nodeStyleRepo = $this->getNodeStyleRepo();
        $nodeStyling = $nodeStyleRepo->getNodeStylingForNodeIds($nodeIds);

        foreach ($nodeStyling as $item) {
            if (!isset($this->nodeStyling[$item->node_id])) {
                $this->nodeStyling[$item->node_id] = [];
            }

            $this->nodeStyling[$item->node_id][$item->style_id] = [
                'styling_options' => $item->styling_options,
            ];

            if (!isset($this->nodeGrid[$item->node_id])) {
                $this->nodeGrid[$item->node_id] = [];
            }

            $this->nodeGrid[$item->node_id][$item->style_id] = [
                'is_default_grid' => false,
                'grid_options' => $item->grid_options,
            ];
        }
    }

    public function rebuildCache()
    {
        $this->buildNodeStylingCache();
        return $this->updateNodeStylingCache();
    }

    protected function getCacheValuesForNode(\XF\Entity\Node $node = null, $styleId)
    {
        if (!$node) {
            $nodeId = 0;
            $nodeDepth = 0;
        } else {
            $nodeId = $node->node_id;
            $nodeDepth = $node->depth;
        }

        if (!isset($this->nodeStyling[$nodeId][$styleId])) {
            $nodeStyling = [];
        } else {
            $nodeStyling = $this->nodeStyling[$nodeId][$styleId];
        }

        if (!isset($this->nodeGrid[$nodeId][$styleId])) {
            $nodeGrid = [];
        } else {
            $nodeGrid = $this->nodeGrid[$nodeId][$styleId];
        }

        $returnStyling = [];

        if ($nodeId && isset($nodeStyling['styling_options'])) {
            $stylingOptions = [];

            if (!empty($nodeStyling['styling_options'])) {
                $returnStyling['styling_options'] = $nodeStyling['styling_options'];
            }
        }

        if ((!$nodeDepth || ($nodeId && $node->node_type_id === 'LayoutSeparator')) && isset($nodeGrid['grid_options'])) {
            $gridOptions = [];
            foreach ($nodeGrid['grid_options'] as $key => $values) {
                if ($values['enable'] && isset($values['value'])) {
                    $gridOptions[$key] = [
                        'enable' => 1,
                        'value' => $values['value'],
                    ];
                } else {
                    $gridOptions[$key] = [
                        'enable' => 0,
                    ];
                }
            }

            if (!empty($gridOptions)) {
                $returnStyling['grid_options'] = $gridOptions;
            }
        }

        if (empty($returnStyling)) {
            return [];
        }

        return $returnStyling;
    }

    protected function updateNodeStylingCache()
    {
        /** @var \XF\DataRegistry $registry */
        $registry = $this->app->registry();

        $stylingCache = [];

        foreach ($this->styles as $style) {
            $styleId = $style->style_id;
            $styling = [];

            $nodeStyling = $this->getCacheValuesForNode(null, $styleId);

            if (!empty($nodeStyling)) {
                $styling[0] = $nodeStyling;
            }

            foreach ($this->nodes as $node) {
                $nodeStyling = $this->getCacheValuesForNode($node, $styleId);

                if (!empty($nodeStyling)) {
                    $styling[$node->node_id] = $nodeStyling;
                }
            }

            if (!empty($styling)) {
                $stylingCache[$styleId] = $styling;
            }
        }

        $registry->set('th_nodeStyling_nodes', $stylingCache);

        $this->getNodeStyleRepo()->buildNodeStylingTemplate($this->styles);

        return $stylingCache;
    }

    protected function buildNodeStylingCache()
    {
        foreach ($this->styleTree as $subTree) {
            $this->handleStyleSubTree($subTree);
        }
    }

    protected function handleStyleSubTree(\XF\SubTree $subTree)
    {
        $this->handleStyle($subTree->record);

        foreach ($subTree->children() as $child) {
            $this->handleStyleSubTree($child);
        }
    }

    protected function handleNodeSubTree(\XF\SubTree $subTree, \XF\Entity\Style $style)
    {
        $this->handleNode($subTree->record, $style);

        foreach ($subTree->children() as $child) {
            $this->handleNodeSubTree($child, $style);
        }
    }

    protected function handleStyle(\XF\Entity\Style $style)
    {
        $this->styles[$style->style_id] = $style;

        // Handle default grid options
        $this->handleNodeGrid(null, $style);

        foreach ($this->nodeTree as $subTree) {
            $this->handleNodeSubTree($subTree, $style);
        }
    }

    protected function handleNode(\XF\Entity\Node $node, \XF\Entity\Style $style)
    {
        $this->handleNodeStyling($node, $style);
        $this->handleNodeGrid($node, $style);
    }

    protected function handleNodeGrid(\XF\Entity\Node $node = null, \XF\Entity\Style $style)
    {
        $nodeId = 0;
        if ($node) {
            $nodeId = $node->node_id;
        }

        if (isset($this->nodeGrid[$nodeId][$style->style_id])) {
            $gridItem = $this->nodeGrid[$nodeId][$style->style_id];
        } else {
            $gridItem = $this->getDefaultGrid();
        }

        $this->nodeGrid[$nodeId][$style->style_id] = $this->inheritFeaturesForGrid($node, $style, $gridItem);
    }

    protected function inheritFeaturesForGrid(\XF\Entity\Node $node = null, \XF\Entity\Style $style, $nodeGrid)
    {
        $defaultGrid = $this->getDefaultGrid();
        $nodeGrid['grid_options'] = array_merge($defaultGrid['grid_options'], $nodeGrid['grid_options']);

        foreach ($nodeGrid['grid_options'] as $key=>&$gridOption) {
            if (!$gridOption['enable']) {
                $gridOption = $this->inheritFeatureForGrid($node, $style, $key);
            } else {
                $gridOption = array_merge($gridOption, [
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ]);
            }
        }

        return $nodeGrid;
    }

    protected function inheritFeatureForGrid(\XF\Entity\Node $node = null, \XF\Entity\Style $style, $featureKey)
    {
        return $this->inheritFeature($node, $style, 'grid', $featureKey);
    }

    protected function inheritFeature(\XF\Entity\Node $node = null, \XF\Entity\Style $style, $itemType, $featureKey, $inheritNodes = false)
    {
        $nodeId = 0;
        $parentNodeId = 0;
        if ($node) {
            $nodeId = $node->node_id;
            $parentNodeId = $node->parent_node_id;
        }

        $parentKey = null;
        $typeArray = null;
        switch ($itemType) {
            case 'grid':
                $parentKey = 'grid_options';
                $typeArray = $this->nodeGrid;
                break;
            case 'styling':
                $parentKey = 'styling_options';
                $typeArray = $this->nodeStyling;
                break;
        }

        if (!$parentKey) {
            return false;
        }

        $inheritedFromParentStyle = false;
        if ($style->style_id) {
            $parentStyle = $typeArray[$nodeId][$style->parent_id];

            if (!isset($parentStyle[$parentKey][$featureKey])) {
                $inheritedFromParentStyle = [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ];
            } else {
                $inheritedFromParentStyle = array_merge($parentStyle[$parentKey][$featureKey], [
                    'inherited_from_parent_style' => true,
                ]);
            }
        }

        $inheritedFromParentNode = false;
        if ($node && $inheritNodes && $parentNodeId) {
            $parentNode = $typeArray[$parentNodeId][$style->style_id];
            if ((!$inheritedFromParentStyle || !$inheritedFromParentStyle['enable'])
                && isset($parentNode[$parentKey][$featureKey])) {
                $inheritedFromParentNode = array_merge($parentNode[$parentKey][$featureKey], [
                    'inherited_from_parent_node' => true,
                ]);
            }
        }

        $inheritedFromDefaultStyling = false;
        if ($node) {
            if ((!$inheritedFromParentStyle || !$inheritedFromParentStyle['enable'])
                && (!$inheritedFromParentNode || !$inheritedFromParentNode['enable'])
                && isset($typeArray[0][$style->style_id][$parentKey][$featureKey])) {
                $inheritedFromDefaultStyling = array_merge($typeArray[0][$style->style_id][$parentKey][$featureKey], [
                    'inherited_from_default_styling' => true,
                ]);
            }
        }

        if ($inheritedFromDefaultStyling) {
            $use = $inheritedFromDefaultStyling;
        } elseif ($inheritedFromParentStyle) {
            $use = $inheritedFromParentStyle;
        } elseif ($inheritedFromParentNode) {
            $use = $inheritedFromParentNode;
        } else {
            $use = [
                'enable' => 0,
                'inherited_from_parent_style' => false,
                'inherited_from_default_styling' => false,
                'inherited_from_parent_node' => false,
            ];
        }

        $inherited = false;

        if ((isset($use['inherited_from_parent_style']) && $use['inherited_from_parent_style'])
            || isset($use['inherited_from_default_styling']) && $use['inherited_from_default_styling']
            || isset($use['inherited_from_parent_node']) && $use['inherited_from_parent_node']) {
            $inherited = true;
        }

        return array_merge($use, [
            'inherited_from_parent_style' => true,
            'inherited_from_default_styling' => false,
            'inherited_from_parent_node' => false,
            'inherited' => $inherited,
        ]);
    }

    protected function handleNodeStyling(\XF\Entity\Node $node = null, \XF\Entity\Style $style)
    {
        $nodeId = 0;
        if ($node) {
            $nodeId = $node->node_id;
        }

        if (isset($this->nodeStyling[$nodeId][$style->style_id])) {
            $stylingItem = $this->nodeStyling[$nodeId][$style->style_id];
        } else {
            $stylingItem = $this->getDefaultStyling();
        }

        $this->nodeStyling[$nodeId][$style->style_id] = $this->inheritFeaturesForStyling($node, $style, $stylingItem);;
    }

    protected function inheritFeaturesForStyling(\XF\Entity\Node $node = null, \XF\Entity\Style $style, $stylingItem)
    {
        $defaultStyling = $this->getDefaultStyling();
        $stylingItem['styling_options'] = array_merge($defaultStyling['styling_options'], $stylingItem['styling_options']);

        foreach ($stylingItem['styling_options'] as $key=>&$stylingOption) {
            if (!$stylingOption['enable']) {
                $stylingOption = $this->inheritFeatureForStyling($node, $style, $key);
            } else {
                $stylingOption = array_merge($stylingOption, [
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ]);
            }
        }

        return $stylingItem;
    }

    protected function inheritFeatureForStyling(\XF\Entity\Node $node = null, \XF\Entity\Style $style, $featureKey)
    {
        return $this->inheritFeature($node, $style, 'styling', $featureKey, true);
    }

    protected function getDefaultStyling()
    {
        return [
            'styling_options' => [
                'class_name' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'background_image_url' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'background_color' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'text_color' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'retain_text_styling' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],

                // Node Icons
                'category_icon_class' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'category_icon_class_unread' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'forum_icon_class' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'forum_icon_class_unread' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'page_icon_class' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'link_forum_icon_class' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
            ],
        ];
    }

    protected function getDefaultGrid()
    {
        return [
            'grid_options' => [
                'max_columns' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'min_column_width' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
                'fill_last_row' => [
                    'enable' => 0,
                    'inherited_from_parent_style' => false,
                    'inherited_from_default_styling' => false,
                    'inherited_from_parent_node' => false,
                ],
            ],
        ];
    }

    /**
     * @return \XF\Repository\Node
     */
    protected function getNodeRepo()
    {
        return $this->repository('XF:Node');
    }

    /**
     * @return \ThemeHouse\Nodes\Repository\NodeStyling
     */
    protected function getNodeStyleRepo()
    {
        return $this->repository('ThemeHouse\Nodes:NodeStyling');
    }

    /**
     * @return \XF\Repository\Style
     */
    protected function getStyleRepo()
    {
        return $this->repository('XF:Style');
    }
}