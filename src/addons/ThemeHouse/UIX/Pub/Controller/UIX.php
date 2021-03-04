<?php

namespace ThemeHouse\UIX\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

/**
 * Class UIX
 * @package ThemeHouse\UIX\Pub\Controller
 */
class UIX extends AbstractController
{
    /**
     * @param $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     */
    public function preDispatch($action, ParameterBag $params)
    {
        if ($this->responseType() !== 'json') {
            throw $this->exception($this->notFound());
        }
    }

    /**
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionToggleWidth()
    {
        $this->assertValidCsrfToken($this->filter('t', 'str'));

        $storageKey = 'th_uix_widthToggle';
        $newWidth = $this->filter('width', 'string');
        if ($newWidth !== 'fluid') {
            $newWidth = 'fixed';
        }

        $visitor = \XF::visitor();
        $styleId = $visitor->style_id;
        if (!$styleId) {
            $styleId = (int)$this->app()->options()->defaultStyleId;
        }

        $style = $this->app()->style($styleId);
        $propertyValue = $style->getProperty('uix_pageWidthToggle');
        if (!$propertyValue || $propertyValue === 'disabled') {
            return $this->notFound();
        }

        if (!$visitor->hasPermission('th_uix', 'togglePageWidth')) {
            return $this->noPermission();
        }

        if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
            $this->session()->set($storageKey, $newWidth);
        } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
            $this->app()->response()->setCookie($storageKey, $newWidth, 86400 * 365);
        }

        $viewParams = [
            'width' => $newWidth,
        ];

        return $this->view('ThemeHouse\UIX:UIX\ToggleWidth', '', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionToggleCategory()
    {
        $this->assertValidCsrfToken($this->filter('t', 'str'));

        $storageKey = 'th_uix_collapsedCategories';
        $nodeId = $this->filter('node_id', 'uint');
        $collapsed = $this->filter('collapsed', 'bool');

        $visitor = \XF::visitor();
        if (!$visitor->hasPermission('th_uix', 'collapseCategories')) {
            return $this->noPermission();
        }

        $styleId = $visitor->style_id;
        if (!$styleId) {
            $styleId = (int)$this->app()->options()->defaultStyleId;
        }

        $style = $this->app()->style($styleId);
        if (!$style->getProperty('uix_categoryCollapse')) {
            return $this->notFound();
        }

        /** @var \XF\Entity\Category $category */
        $category = $this->assertRecordExists('XF:Category', $nodeId);

        /** @var \XF\Entity\Node $node */
        $node = $category->Node;

        if (!$node->canView($error) || $node->depth !== 0) {
            return $this->noPermission($error);
        }

        $collapsedCategories = [];
        if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
            $collapsedCategories = $this->session()->get($storageKey);
        } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
            $collapsedCategories = $this->request()->getCookie($storageKey);
        }
        if (empty($collapsedCategories)) {
            $collapsedCategories = [];
        } elseif (!is_array($collapsedCategories)) {
            $collapsedCategories = explode('.', $collapsedCategories);
        }

        $currentState = in_array($nodeId, $collapsedCategories);

        if ($collapsed && !$currentState) {
            $collapsedCategories[] = $nodeId;
        }

        if (!$collapsed && $currentState) {
            foreach ($collapsedCategories as $key => $thisNodeId) {
                if (intval($thisNodeId) === $nodeId) {
                    unset($collapsedCategories[$key]);
                }
            }
        }

        if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
            $this->session()->set($storageKey, $collapsedCategories);
        } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
            $this->app()->response()->setCookie($storageKey, implode('.', $collapsedCategories), 86400 * 365);
        }

        $viewParams = [
            'collapsed' => $collapsed,
            'collapsedCategories' => $collapsedCategories,
        ];

        return $this->view('ThemeHouse\UIX:UIX\ToggleCategory', '', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionToggleSidebar()
    {
        $this->assertValidCsrfToken($this->filter('t', 'str'));

        $storageKey = 'th_uix_sidebarCollapsed';
        $collapsed = $this->filter('collapsed', 'bool');

        $visitor = \XF::visitor();
        if (!$visitor->hasPermission('th_uix', 'collapseSidebar')) {
            return $this->noPermission();
        }

        $styleId = $visitor->style_id;
        if (!$styleId) {
            $styleId = (int)$this->app()->options()->defaultStyleId;
        }

        $style = $this->app()->style($styleId);
        if (!$style->getProperty('uix_collapsibleSidebar')) {
            return $this->notFound();
        }

        if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
            $this->session()->set($storageKey, $collapsed);
        } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
            $this->app()->response()->setCookie($storageKey, $collapsed, 86400 * 365);
        }

        $viewParams = [
            'collapsed' => $collapsed,
        ];

        return $this->view('ThemeHouse\UIX:UIX\Toggle', '', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionToggleSidebarNavigation()
    {
        $this->assertValidCsrfToken($this->filter('t', 'str'));

        $storageKey = 'th_uix_sidebarNavCollapsed';
        $collapsed = $this->filter('collapsed', 'bool');

        $visitor = \XF::visitor();

        $styleId = $visitor->style_id;
        if (!$styleId) {
            $styleId = (int)$this->app()->options()->defaultStyleId;
        }

        $style = $this->app()->style($styleId);
        if ($style->getProperty('uix_navigationType') !== 'sidebarNav') {
            return $this->notFound();
        }

        if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
            $this->session()->set($storageKey, $collapsed);
        } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
            $this->app()->response()->setCookie($storageKey, $collapsed, 86400 * 365);
        }

        $viewParams = [
            'collapsed' => $collapsed,
        ];

        return $this->view('ThemeHouse\UIX:UIX\Toggle', '', $viewParams);
    }
}
