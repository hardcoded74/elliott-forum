<?php

namespace ThemeHouse\UIX\Listener;

class App
{
    public static function pubRenderPage(\XF\Pub\App $app, array &$params, \XF\Mvc\Reply\AbstractReply $reply, \XF\Mvc\Renderer\AbstractRenderer $renderer)
    {
        $visitor = \XF::visitor();
        $templater = $app->templater();
        $style = $templater->getStyle();
        $session = \XF::session();

        $uix = new \ThemeHouse\UIX\Util\UIX();


        $params['uix_footerWidgets'] = $uix->getFooterWidgets($templater);
        $params['uix_sidebarNavWidgets'] = $uix->getSidebarNavWidgets($templater);
        $params['uix_showWelcomeSection'] = $uix->showWelcomeSection($templater, $params['template']);
        $params['uix_additionalHtmlClasses'] = $uix->getAdditionalHtmlClasses($templater, $params);

        if ($pageWidth = $uix->getPageWidth($templater)) {
            $params['uix_canTogglePageWidth'] = $pageWidth['canTogglePageWidth'];
            $params['uix_pageWidth'] = $pageWidth['pageWidth'];
        }

        if ($style->getProperty('uix_categoryCollapse') && $visitor->hasPermission('th_uix', 'collapseCategories')) {
            $collapsedCategories = $session->get('th_uix_collapsedCategories');
            if (empty($collapsedCategories)) {
                $collapsedCategories = [];
            }

            $params['uix_canCollapseCategories'] = true;
            $params['uix_collapsedCategories'] = $collapsedCategories;
            $params['uix_collapsedCategories'] = json_encode($collapsedCategories);
        }

        if ($style->getProperty('uix_collapsibleSidebar') && $visitor->hasPermission('th_uix', 'collapseSidebar')) {
            $sidebarCollapsed = $session->get('th_uix_sidebarCollapsed');
            if (empty($sidebarCollapsed)) {
                $sidebarCollapsed = false;
            }

            $params['uix_canCollapseSidebar'] = true;
            $params['uix_sidebarCollapsed'] = $sidebarCollapsed;
        }

        if ($style->getProperty('uix_navigationType') === 'sidebarNav') {
            $sidebarNavCollapsed = $session->get('th_uix_sidebarNavCollapsed');
            if (empty($sidebarNavCollapsed)) {
                $sidebarNavCollapsed = false;
            }

            $params['uix_canCollapseSidebarNav'] = true;
            $params['uix_sidebarNavCollapsed'] = $sidebarNavCollapsed;
        }


    }

    public static function templaterSetup(\XF\Container $container, \XF\Template\Templater &$templater)
    {
        #$templater->addFunction('')
    }
}