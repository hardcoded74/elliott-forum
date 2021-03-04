<?php

namespace ThemeHouse\Nodes\Admin\Controller;

use XF\Admin\Controller\AbstractNode;
use XF\Mvc\FormAction;

class LayoutSeparator extends AbstractNode
{
    protected function getNodeTypeId()
    {
        return 'LayoutSeparator';
    }

    protected function getDataParamName()
    {
        return 'layoutSeparator';
    }

    protected function getTemplatePrefix()
    {
        return 'th_layoutSeparator_nodes';
    }

    protected function getViewClassPrefix()
    {
        return 'ThemeHouse\Nodes:LayoutSeparator';
    }

    protected function nodeAddEdit(\XF\Entity\Node $node)
    {
        $response = parent::nodeAddEdit($node);

        if ($response instanceof \XF\Mvc\Reply\View)
        {
            /** @var \ThemeHouse\Nodes\Repository\NodeStyling $nodeStyleRepo */
            $nodeStyleRepo = $this->repository('ThemeHouse\Nodes:NodeStyling');

            if ($node->isInsert()) {
                $nodeStyling = $nodeStyleRepo->getNodeStylingForDefault(0);
            } else {
                $nodeStyling = $node->getNodeStylingForStyle(0);
            }

            if (!$nodeStyling) {
                $nodeStyling = $nodeStyleRepo->getDefaultNodeStylingForNode($node, 0);
            }

            $response->setParams([
                'nodeStyling' => $nodeStyling,
            ]);
        }

        return $response;
    }

    protected function saveTypeData(FormAction $form, \XF\Entity\Node $node, \XF\Entity\AbstractNode $data)
    {
        $input = $this->filter([
            'separator_type' => 'string',
            'separator_max_width' => 'uint',
        ]);

        /** @var \XF\Entity\Forum $data */
        $data->bulkSet($input);

        $gridOptions = $this->filter('grid_options', 'array');
        $form->complete(function() use($data, $gridOptions)
        {
            /** @var \ThemeHouse\Nodes\Repository\NodeStyling $nodeStyleRepo */
            $nodeStyleRepo = $this->repository('ThemeHouse\Nodes:NodeStyling');

            if ($data->isInsert()) {
                $nodeStyling = $nodeStyleRepo->getNodeStylingForDefault(0);
            } else {
                $nodeStyling = $data->Node->getNodeStylingForStyle(0);
            }

            if (!$nodeStyling) {
                $nodeStyling = $nodeStyleRepo->getDefaultNodeStylingForNode($data->Node, 0);
            }

            $nodeStyling->grid_options = $gridOptions;

            $nodeStyling->save();
        });
    }

}