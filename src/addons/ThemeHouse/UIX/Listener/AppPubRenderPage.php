<?php

namespace ThemeHouse\UIX\Listener;

use ThemeHouse\UIX\Util\UIX;
use XF\Mvc\Renderer\AbstractRenderer;
use XF\Mvc\Reply\AbstractReply;
use XF\Pub\App;

/**
 * Class AppPubRenderPage
 * @package ThemeHouse\UIX\Listener
 */
class AppPubRenderPage
{
    /**
     * @param App $app
     * @param array $params
     * @param AbstractReply $reply
     * @param AbstractRenderer $renderer
     */
    public static function appPubRenderPage(App $app, array &$params, AbstractReply $reply, AbstractRenderer $renderer)
    {
        $visitor = \XF::visitor();
        $templater = $app->templater();
        $style = $templater->getStyle();
        $session = \XF::session();

        $uix = new UIX();

        $params['uix_footerWidgets'] = $uix->getFooterWidgets($templater);
        $params['uix_sidebarNavWidgets'] = $uix->getSidebarNavWidgets($templater);

        if (!isset($params['uix_showWelcomeSection'])) {
            $params['uix_showWelcomeSection'] = $uix->showWelcomeSection($templater, $params['template']);
        } else {
            $params['uix_showWelcomeSection'] = $uix->showWelcomeSection($templater, $params['template'], false);
        }
        $params['uix_additionalHtmlClasses'] = $uix->getAdditionalHtmlClasses($templater, $params);

        if ($pageWidth = $uix->getPageWidth($templater)) {
            $params['uix_canTogglePageWidth'] = $pageWidth['canTogglePageWidth'];
            $params['uix_pageWidth'] = $pageWidth['pageWidth'];
        }

        if ($style->getProperty('uix_categoryCollapse') && $visitor->hasPermission('th_uix', 'collapseCategories')) {
            $collapsedCategories = [];
            if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
                $collapsedCategories = $session->get('th_uix_collapsedCategories');
            } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
                $collapsedCategories = $app->request()->getCookie('th_uix_collapsedCategories');
            }
            if (empty($collapsedCategories)) {
                $collapsedCategories = [];
            } elseif (!is_array($collapsedCategories)) {
                $collapsedCategories = explode('.', $collapsedCategories);
            }

            $params['uix_canCollapseCategories'] = $visitor->hasPermission('th_uix', 'collapseCategories');
            $params['uix_collapsedCategories'] = $collapsedCategories;
            $params['uix_collapsedCategories'] = json_encode($collapsedCategories);
        }

        if ($style->getProperty('uix_collapsibleSidebar') && $visitor->hasPermission('th_uix', 'collapseSidebar')) {
            $sidebarCollapsed = null;
            if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
                $sidebarCollapsed = $session->get('th_uix_sidebarCollapsed');
            } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
                $sidebarCollapsed = $app->request()->getCookie('th_uix_sidebarCollapsed');
            }
            if (is_null($sidebarCollapsed)) {
                if (isset(\XF::options()->th_sidebarCollapseDefault_uix)) {
                    $sidebarCollapseDefault = \XF::options()->th_sidebarCollapseDefault_uix;
                    if ($visitor->user_id) {
                        if (\XF::options()->thuix_enableStyleOptions) {
                            /** @noinspection PhpUndefinedFieldInspection */
                            $sidebarCollapsed = $visitor->Option->thuix_collapse_sidebar;
                        } else {
                            $sidebarCollapsed = (in_array($sidebarCollapseDefault, ['registered', 'all']));
                        }
                    } else {
                        $sidebarCollapsed = (in_array($sidebarCollapseDefault, ['unregistered', 'all']));
                    }
                } else {
                    $sidebarCollapsed = false;
                }
            }

            $params['uix_canCollapseSidebar'] = $visitor->hasPermission('th_uix', 'collapseSidebar');
            $params['uix_sidebarCollapsed'] = $sidebarCollapsed;
        }

        if ($style->getProperty('uix_navigationType') === 'sidebarNav') {
            $sidebarNavCollapsed = null;
            if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
                $sidebarNavCollapsed = $session->get('th_uix_sidebarNavCollapsed');
            } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
                $sidebarNavCollapsed = $app->request()->getCookie('th_uix_sidebarNavCollapsed');
            }
            if (is_null($sidebarNavCollapsed)) {
                if (isset(\XF::options()->th_sidebarNavCollapseDefault_uix)) {
                    $sidebarNavCollapseDefault = \XF::options()->th_sidebarNavCollapseDefault_uix;
                    if ($visitor->user_id) {
                        if (\XF::options()->thuix_enableStyleOptions) {
                            /** @noinspection PhpUndefinedFieldInspection */
                            $sidebarNavCollapsed = $visitor->Option->thuix_collapse_sidebar_nav;
                        } else {
                            $sidebarNavCollapsed = (in_array($sidebarNavCollapseDefault, ['registered', 'all']));
                        }
                    } else {
                        $sidebarNavCollapsed = (in_array($sidebarNavCollapseDefault, ['unregistered', 'all']));
                    }
                } else {
                    $sidebarNavCollapsed = false;
                }
            }

            $params['uix_canCollapseSidebarNav'] = true;
            $params['uix_sidebarNavCollapsed'] = $sidebarNavCollapsed;
        }

        if ($style->getProperty('uix_collapseExtraInfo')) {
            if (isset(\XF::options()->th_postbitCollapseDefault_uix)) {
                $postbitCollapseDefault = \XF::options()->th_postbitCollapseDefault_uix;
                if ($visitor->user_id) {
                    if (\XF::options()->thuix_enableStyleOptions) {
                        /** @noinspection PhpUndefinedFieldInspection */
                        $postbitCollapsed = $visitor->Option->thuix_collapse_postbit;
                    } else {
                        $postbitCollapsed = (in_array($postbitCollapseDefault, ['registered', 'all']));
                    }
                } else {
                    $postbitCollapsed = (in_array($postbitCollapseDefault, ['unregistered', 'all']));
                }
            } else {
                $postbitCollapsed = true;
            }

            $params['uix_postbitCollapsed'] = $postbitCollapsed;
        }
    }
}
