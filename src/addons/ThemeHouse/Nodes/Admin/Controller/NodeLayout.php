<?php

namespace ThemeHouse\Nodes\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class NodeLayout extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {
        if ($params['node_id']) {
            return $this->rerouteController(__CLASS__, 'node',  $params);
        }
        $styleId = $this->filter('style_id', 'uint');
        $nodeRepo = $this->getNodeRepo();
        $nodeTree = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList(null, 'NodeType'));

        $style = $this->assertStyleExists($styleId);

        /** @var \XF\Repository\Moderator $moderatorRepo */
        $moderatorRepo = $this->repository('XF:Moderator');
        $moderators = $moderatorRepo->findContentModeratorsForList()
            ->where('content_type', 'node')
            ->fetch()->groupBy('content_id');

        $viewParams = [
            'nodeTree' => $nodeTree,
            'moderators' => $moderators,

			'styleTree' => $this->getStyleRepo()->getStyleTree(),
            'style' => $style,
        ];
        return $this->view('ThemeHouse\Nodes:NodeLayout\Index', 'th_node_layout_nodes', $viewParams);
    }

    public function actionRebuild()
    {
        $nodeStyleRepo = $this->getNodeStyleRepo();
        $nodeStyleRepo->rebuildNodeStylingCache();

        return $this->redirect($this->buildLink('node-layout'));
    }

    public function actionViewCache()
    {
        /** @var \XF\DataRegistry $registry */
        $registry = $this->app->registry();

        $cache = $registry->get('th_nodeStyling_nodes');
        \XF::dump($cache);die;
    }

    public function actionDefaultGrid()
    {
        return $this->rerouteController(__CLASS__, 'node');
    }

    public function actionNode(ParameterBag $params)
    {
        $nodeStyleRepo = $this->getNodeStyleRepo();
        $nodeId = $params['node_id'];

        $styleId = $this->filter('style_id', 'uint');

        if ($nodeId) {
            list($node, $style) = $this->assertNodeAndStyleExists($nodeId, $styleId);
            $nodeStyling = $node->getNodeStylingForStyle($styleId);
        } else {
            $style = $this->assertStyleExists($styleId);
            $node = null;
            $nodeStyling = $nodeStyleRepo->getNodeStylingForDefault($styleId);
        }


        if (!$nodeStyling) {
            $nodeStyling = $nodeStyleRepo->getDefaultNodeStylingForNode($node, $styleId);
        }

        $viewParams = [
            'node' => $node,
            'style' => $style,
            'nodeStyling' => $nodeStyling,
        ];

        return $this->view('ThemeHouse\Nodes:NodeLayout:Edit', 'th_node_layout_edit_nodes', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        $nodeStyleRepo = $this->getNodeStyleRepo();

        $nodeId = $params['node_id'];
        $styleId = $this->filter('style_id', 'uint');

        if ($nodeId) {
            list($node, $style) = $this->assertNodeAndStyleExists($nodeId, $styleId);
            $nodeStyling = $node->getNodeStylingForStyle($styleId);
        } else {
            $this->assertStyleExists($styleId);
            $node = null;
            $nodeStyling = $nodeStyleRepo->getNodeStylingForDefault($styleId);
        }

        if (!$nodeStyling) {
            $nodeStyling = $nodeStyleRepo->getDefaultNodeStylingForNode($node, $styleId);
        }

        $this->nodeStylingSaveProcess($nodeStyling)->run();

        $nodeStyleRepo->rebuildNodeStylingCache();

        return $this->redirect($this->buildLink('node-layout', '', [
            'style_id' => $styleId,
        ]));
    }

    protected function nodeStylingSaveProcess(\ThemeHouse\Nodes\Entity\NodeStyling $nodeStyling)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'styling_options' => 'array',
            'grid_options' => 'array',
        ]);

        $form->basicEntitySave($nodeStyling, $input);

        return $form;
    }

    protected function assertNodeAndStyleExists($nodeId, $styleId)
    {
        $node = $this->assertNodeExists($nodeId);
        $style = $this->assertStyleExists($styleId);

        return [$node, $style];
    }


    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \XF\Entity\Node
     */
    protected function assertNodeExists($id, $with = null, $phraseKey = null)
    {
        if (!is_array($with))
        {
            $with = $with ? [$with] : [];
        }
        $with[] = 'NodeType';

        return $this->assertRecordExists('XF:Node', $id, $with, $phraseKey);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \XF\Entity\Style
     */
    protected function assertStyleExists($id, $with = null, $phraseKey = null)
    {
        if ($id === 0 || $id === "0")
        {
            return $this->getStyleRepo()->getMasterStyle();
        }

        return $this->assertRecordExists('XF:Style', $id, $with, $phraseKey);
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