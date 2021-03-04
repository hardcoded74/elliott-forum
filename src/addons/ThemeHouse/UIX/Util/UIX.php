<?php

namespace ThemeHouse\UIX\Util;


use XF\Template\Templater;

/**
 * Class UIX
 * @package ThemeHouse\UIX\Util
 */
class UIX
{
    /**
     * @param Templater $templater
     * @param array $params
     * @return string
     */
    public function getAdditionalHtmlClasses(Templater $templater, array $params = [])
    {
        $classes = [];
        if (!empty($params['breadcrumbs'])) {
            $classes[] = 'uix_hasCrumbs';
        }

        if (!empty($params['pageAction'])) {
            $classes[] = 'uix_hasPageAction';
        }

        if (!empty($classes)) {
            return ' ' . implode(' ', $classes);
        }

        return '';
    }

    /**
     * @param Templater $templater
     * @return string
     */
    public function getBackstretchImages(Templater $templater)
    {
        $backstretchImages = '';

        if ($imageStr = $templater->getStyle()->getProperty('uix_backstretchImages')) {
            $images = explode(',', $imageStr);
            if (!empty($images)) {
                foreach ($images as &$image) {
                    $image = trim($image);
                    $image = str_replace('"', '', $image);
                    $image = $templater->fnBaseUrl($templater, $escape, $image);
                    $image = '"' . $image . '"';
                }

                $backstretchImages = implode(',', $images);
            }
        }

        return $backstretchImages;
    }

    /**
     * @param Templater $templater
     * @return bool|string
     */
    public function getFooterWidgets(Templater $templater)
    {
        $footerWidgets = false;
        if ($templater->getStyle()->getProperty('uix_enableExtendedFooter')) {
            $footerWidgets = $templater->widgetPosition('th_footer_uix');
        }

        return $footerWidgets;
    }

    /**
     * @param Templater $templater
     * @return bool|string
     */
    public function getSidebarNavWidgets(Templater $templater)
    {
        $sidebarNavWidgets = false;
        if ($templater->getStyle()->getProperty('uix_navigationType') === 'sidebarNav') {
            $sidebarNavWidgets = $templater->widgetPosition('th_sidebarNavigation_uix');
        }

        return $sidebarNavWidgets;
    }

    /**
     * @param Templater $templater
     * @param $contentTemplate
     * @param bool $templateCheck
     * @return bool
     */
    public function showWelcomeSection(Templater $templater, $contentTemplate, $templateCheck = true)
    {
        $visitor = \XF::visitor();

        $showWelcomeSection = false;
        $welcomeSectionVisible = $templater->getStyle()->getProperty('uix_welcomeSectionVisible');
        if ($welcomeSectionVisible !== 'off') {
            switch ($welcomeSectionVisible) {
                case 'guests':
                    $showWelcomeSection = $visitor->user_id === 0;
                    break;
                case 'always':
                    $showWelcomeSection = true;
                    break;
                case 'userPermissions':
                    $showWelcomeSection = $visitor->hasPermission('th_uix', 'showWelcomeSection');
                    break;
            }
        }

        if ($showWelcomeSection && $templateCheck) {
            /* @var \ThemeHouse\UIX\XF\Template\Templater $templater */
            $forumOverviewWrapper = $templater->getTemplateDataFromCacheForUIX('public', 'forum_overview_wrapper');
            if ($templater->getStyle()->getProperty('uix_welcomeSectionForumListOnly') && !$forumOverviewWrapper) {
                return false;
            }
        }

        return $showWelcomeSection;
    }

    /**
     * @param Templater $templater
     * @return array|bool
     */
    public function getPageWidth(Templater $templater)
    {
        $pageWidth = $templater->getStyle()->getProperty('uix_pageWidthToggle');
        if ($pageWidth && $pageWidth !== 'disabled') {
            $storageKey = 'th_uix_widthToggle';
            $value = '';
            if (\XF::options()->thuix_userStyleChangeStorage === 'session') {
                $value = \XF::session()->get($storageKey);
            } elseif (\XF::options()->thuix_userStyleChangeStorage === 'cookie') {
                $value = \XF::app()->request()->getCookie($storageKey);
            }
            if ($canTogglePageWidth = \XF::visitor()->hasPermission('th_uix', 'togglePageWidth')) {
                if ($value === 'fixed') {
                    $pageWidth = 'fixed';
                } elseif ($value) {
                    $pageWidth = 'fluid';
                }
            }

            return [
                'canTogglePageWidth' => $canTogglePageWidth,
                'pageWidth' => $pageWidth,
            ];
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canWriteToJsAndStyleDirectories()
    {
        $baseDir = \XF::getRootDirectory();
        $dirs = [
            $baseDir . '/js',
            $baseDir . '/styles'
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir) || !is_writable($dir)) {
                return false;
            }
        }

        return true;
    }


    /**
     * @param $host
     * @param $port
     * @param $user
     * @param $password
     * @param $directory
     * @return Ftp
     * @throws \Exception
     */
    public function createFtpConnection($host, $port, $user, $password, $directory)
    {
        return new Ftp($host, $port, $user, $password, $directory);
    }
}
