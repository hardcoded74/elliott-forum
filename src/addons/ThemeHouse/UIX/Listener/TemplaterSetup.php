<?php

namespace ThemeHouse\UIX\Listener;

use XF\Container;
use XF\Entity\StyleProperty;
use XF\Entity\StylePropertyGroup;
use XF\Template\Templater;
use XF\Util\Arr;

/**
 * Class TemplaterSetup
 * @package ThemeHouse\UIX\Listener
 */
class TemplaterSetup
{
    /**
     * @var array
     */
    protected static $_js = [];

    /**
     * @param Container $container
     * @param Templater $templater
     */
    public static function templaterSetup(Container $container, Templater &$templater)
    {
        $templater->addFunction(
            'uix_style_property_prefix',
            ['\ThemeHouse\UIX\Listener\TemplaterSetup', 'fnStylePropertyPrefix']
        );
        $templater->addFunction(
            'uix_style_property_documentation',
            ['\ThemeHouse\UIX\Listener\TemplaterSetup', 'fnStylePropertyDocumentation']
        );
        $templater->addFunction('uix_extra_css_urls', ['\ThemeHouse\UIX\Listener\TemplaterSetup', 'fnExtraCssUrls']);

        $templater->addFunction('uix_js', ['\ThemeHouse\UIX\Listener\TemplaterSetup', 'fnJs']);
    }

    /**
     * @param Templater $templater
     * @param $escape
     * @param $src
     * @param bool $min
     * @param string $attrs
     * @return string
     */
    public static function fnJs(Templater $templater, &$escape, $src, $min = false, $attrs = '')
    {
        $escape = false;
        $app = \XF::app();

        $developmentConfig = $app->config('development');
        $productionMode = empty($developmentConfig['fullJs']);

        $src = $src ? Arr::stringToArray($src, '/[, ]/') : [];

        if ($productionMode) {
            if ($min) {
                $src = array_map(function ($path) {
                    return preg_replace('(\.js$)', '.min.js', $path, 1);
                }, $src);
            }
        }

        $return = '';

        foreach ($src as $path) {
            $url = $templater->getJsUrl($path);

            $return .= "\n";
            $return .= '<script src="' . htmlspecialchars($url) . '" ' . $attrs . '></script>';
        }

        return $return;
    }

    /**
     * @param Templater $templater
     * @param $escape
     * @param $entity
     * @return string
     */
    public static function fnStylePropertyPrefix(Templater $templater, &$escape, $entity)
    {
        $key = '';
        if ($entity instanceof StylePropertyGroup) {
            $key = $entity->group_name;
        }
        if ($entity instanceof StyleProperty) {
            $key = $entity->property_name;
        }

        if (strpos($key, 'uix_') === 0) {
            $escape = false;
            return $templater->renderTemplate('admin:th_style_property_prefix_uix');
        }

        return null;
    }

    /**
     * @param Templater $templater
     * @param $escape
     * @param $entity
     * @return string
     */
    public static function fnStylePropertyDocumentation(Templater $templater, &$escape, $entity)
    {
        $propKey = '';
        $groupKey = '';
        $version = '';
        if ($entity instanceof StyleProperty) {
            $style = \XF::app()->style($entity->style_id);

            $propKey = $entity->property_name;
            $groupKey = $entity->group_name;
            $version = urlencode($style->getProperty('uix_version'));
        }

        if (strpos($propKey, 'uix_') === 0) {
            $escape = false;
            $params = [
                'groupKey' => $groupKey,
                'propKey' => $propKey,
                'version' => $version,
            ];
            return $templater->renderTemplate('admin:th_style_property_documentation_uix', $params);
        }

        return null;
    }

    /**
     * @param Templater $templater
     * @param $escape
     * @param array $cssUrls
     * @return array
     */
    public static function fnExtraCssUrls(Templater $templater, &$escape, array $cssUrls = [])
    {
        $additionalCss = $templater->getStyle()->getProperty('uix_additionalCss');
        if (!empty($additionalCss)) {
            $additionalCss = explode(',', $templater->getStyle()->getProperty('uix_additionalCss'));
            foreach ($additionalCss as $cssFile) {
                if (strpos($cssFile, 'public:') === false) {
                    $cssUrls[] = 'public:' . $cssFile;
                } else {
                    $cssUrls[] = $cssFile;
                }
            }
        }

        return $cssUrls;
    }
}
