<?php

namespace ThemeHouse\UIX\XF\Template;

use XF\App;
use XF\Entity\StyleProperty;
use XF\Language;

/**
 * Class Templater
 * @package ThemeHouse\UIX\XF\Template
 */
class Templater extends XFCP_Templater
{
    /**
     * @var array
     */
    protected $uix_uploadable_style_properties = [
        'publicLogoUrl',
        'publicLogoUrl2x',
        'publicMetadataLogoUrl',
        'publicFaviconUrl',
        'publicPushBadgeUrl',
        'uix_logoSmall',
        'uix_parallaxImage'
    ];
    /**
     * @var array
     */
    protected $uix_materialColors = [
        ['bgColor' => '#ef5350', 'textColor' => '#ff8a80'],
        ['bgColor' => '#f44336', 'textColor' => '#ff8a80'],
        ['bgColor' => '#e53935', 'textColor' => '#ff8a80'],
        ['bgColor' => '#d32f2f', 'textColor' => '#ff8a80'],
        ['bgColor' => '#c62828', 'textColor' => '#ff8a80'],
        ['bgColor' => '#b71c1c', 'textColor' => '#ff8a80'],
        ['bgColor' => '#ec407a', 'textColor' => '#ff80ab'],
        ['bgColor' => '#e91e63', 'textColor' => '#ff80ab'],
        ['bgColor' => '#d81b60', 'textColor' => '#ff80ab'],
        ['bgColor' => '#c2185b', 'textColor' => '#ff80ab'],
        ['bgColor' => '#ad1457', 'textColor' => '#ff80ab'],
        ['bgColor' => '#880e4f', 'textColor' => '#ff80ab'],
        ['bgColor' => '#ab47bc', 'textColor' => '#ea80fc'],
        ['bgColor' => '#9c27b0', 'textColor' => '#ea80fc'],
        ['bgColor' => '#8e24aa', 'textColor' => '#ea80fc'],
        ['bgColor' => '#7b1fa2', 'textColor' => '#ea80fc'],
        ['bgColor' => '#6a1b9a', 'textColor' => '#ea80fc'],
        ['bgColor' => '#4a148c', 'textColor' => '#ea80fc'],
        ['bgColor' => '#7e57c2', 'textColor' => '#b388ff'],
        ['bgColor' => '#673ab7', 'textColor' => '#b388ff'],
        ['bgColor' => '#5e35b1', 'textColor' => '#b388ff'],
        ['bgColor' => '#512da8', 'textColor' => '#b388ff'],
        ['bgColor' => '#4527a0', 'textColor' => '#b388ff'],
        ['bgColor' => '#311b92', 'textColor' => '#b388ff'],
        ['bgColor' => '#5c6bc0', 'textColor' => '#8c9eff'],
        ['bgColor' => '#3f51b5', 'textColor' => '#8c9eff'],
        ['bgColor' => '#3949ab', 'textColor' => '#8c9eff'],
        ['bgColor' => '#303f9f', 'textColor' => '#8c9eff'],
        ['bgColor' => '#283593', 'textColor' => '#8c9eff'],
        ['bgColor' => '#1a237e', 'textColor' => '#8c9eff'],
        ['bgColor' => '#42a5f5', 'textColor' => '#bbdefb'],
        ['bgColor' => '#2196f3', 'textColor' => '#bbdefb'],
        ['bgColor' => '#1e88e5', 'textColor' => '#82b1ff'],
        ['bgColor' => '#1976d2', 'textColor' => '#82b1ff'],
        ['bgColor' => '#1565c0', 'textColor' => '#82b1ff'],
        ['bgColor' => '#0d47a1', 'textColor' => '#82b1ff'],
        ['bgColor' => '#29b6f6', 'textColor' => '#80d8ff'],
        ['bgColor' => '#03a9f4', 'textColor' => '#80d8ff'],
        ['bgColor' => '#039be5', 'textColor' => '#80d8ff'],
        ['bgColor' => '#0288d1', 'textColor' => '#80d8ff'],
        ['bgColor' => '#0277bd', 'textColor' => '#80d8ff'],
        ['bgColor' => '#01579b', 'textColor' => '#80d8ff'],
        ['bgColor' => '#26c6da', 'textColor' => '#84ffff'],
        ['bgColor' => '#00bcd4', 'textColor' => '#84ffff'],
        ['bgColor' => '#00acc1', 'textColor' => '#84ffff'],
        ['bgColor' => '#0097a7', 'textColor' => '#84ffff'],
        ['bgColor' => '#00838f', 'textColor' => '#84ffff'],
        ['bgColor' => '#006064', 'textColor' => '#84ffff'],
        ['bgColor' => '#26a69a', 'textColor' => '#a7ffeb'],
        ['bgColor' => '#009688', 'textColor' => '#a7ffeb'],
        ['bgColor' => '#00897b', 'textColor' => '#a7ffeb'],
        ['bgColor' => '#00796b', 'textColor' => '#a7ffeb'],
        ['bgColor' => '#00695c', 'textColor' => '#a7ffeb'],
        ['bgColor' => '#004d40', 'textColor' => '#a7ffeb'],
        ['bgColor' => '#66bb6a', 'textColor' => '#b9f6ca'],
        ['bgColor' => '#4caf50', 'textColor' => '#b9f6ca'],
        ['bgColor' => '#43a047', 'textColor' => '#b9f6ca'],
        ['bgColor' => '#388e3c', 'textColor' => '#b9f6ca'],
        ['bgColor' => '#2e7d32', 'textColor' => '#b9f6ca'],
        ['bgColor' => '#1b5e20', 'textColor' => '#b9f6ca'],
        ['bgColor' => '#9ccc65', 'textColor' => '#ccff90'],
        ['bgColor' => '#8bc34a', 'textColor' => '#ccff90'],
        ['bgColor' => '#7cb342', 'textColor' => '#ccff90'],
        ['bgColor' => '#689f38', 'textColor' => '#ccff90'],
        ['bgColor' => '#558b2f', 'textColor' => '#ccff90'],
        ['bgColor' => '#33691e', 'textColor' => '#ccff90'],
        ['bgColor' => '#d4e157', 'textColor' => '#f4ff81'],
        ['bgColor' => '#cddc39', 'textColor' => '#f4ff81'],
        ['bgColor' => '#c0ca33', 'textColor' => '#f4ff81'],
        ['bgColor' => '#afb42b', 'textColor' => '#f4ff81'],
        ['bgColor' => '#9e9d24', 'textColor' => '#f4ff81'],
        ['bgColor' => '#827717', 'textColor' => '#f4ff81'],
        ['bgColor' => '#ffee58', 'textColor' => '#f9a825'],
        ['bgColor' => '#ffeb3b', 'textColor' => '#f9a825'],
        ['bgColor' => '#fdd835', 'textColor' => '#ffff8d'],
        ['bgColor' => '#fbc02d', 'textColor' => '#ffff8d'],
        ['bgColor' => '#f9a825', 'textColor' => '#ffff8d'],
        ['bgColor' => '#f57f17', 'textColor' => '#ffff8d'],
        ['bgColor' => '#ffca28', 'textColor' => '#ffe57f'],
        ['bgColor' => '#ffc107', 'textColor' => '#ffe57f'],
        ['bgColor' => '#ffb300', 'textColor' => '#ffe57f'],
        ['bgColor' => '#ffa000', 'textColor' => '#ffe57f'],
        ['bgColor' => '#ff8f00', 'textColor' => '#ffe57f'],
        ['bgColor' => '#ff6f00', 'textColor' => '#ffe57f'],
        ['bgColor' => '#ffa726', 'textColor' => '#ffd180'],
        ['bgColor' => '#ff9800', 'textColor' => '#ffd180'],
        ['bgColor' => '#fb8c00', 'textColor' => '#ffd180'],
        ['bgColor' => '#f57c00', 'textColor' => '#ffd180'],
        ['bgColor' => '#ef6c00', 'textColor' => '#ffd180'],
        ['bgColor' => '#e65100', 'textColor' => '#ffd180'],
        ['bgColor' => '#ff7043', 'textColor' => '#ff9e80'],
        ['bgColor' => '#ff5722', 'textColor' => '#ff9e80'],
        ['bgColor' => '#f4511e', 'textColor' => '#ff9e80'],
        ['bgColor' => '#e64a19', 'textColor' => '#ff9e80'],
        ['bgColor' => '#d84315', 'textColor' => '#ff9e80'],
        ['bgColor' => '#bf360c', 'textColor' => '#ff9e80']
    ];

    /**
     * Templater constructor.
     * @param App $app
     * @param Language $language
     * @param $compiledPath
     */
    public function __construct(App $app, Language $language, $compiledPath)
    {
        parent::__construct($app, $language, $compiledPath);

        $this->addFunction('thuix_uploadable_style_property', 'fnUIXUploadableStyleProperty');
    }

    /**
     * @param Templater $templater
     * @param $escape
     * @param StyleProperty $styleProperty
     * @return bool
     */
    public function fnUIXUploadableStyleProperty(Templater $templater, &$escape, StyleProperty $styleProperty)
    {
        return in_array($styleProperty->property_name, $this->uix_uploadable_style_properties);
    }

    /**
     * @param $type
     * @param $template
     * @return mixed
     */
    public function getTemplateDataFromCacheForUIX($type, $template)
    {
        if (isset($this->templateCache[$type][$template])) {
            return $this->templateCache[$type][$template];
        }

        $languageId = $this->language->getId();
        $cacheKey = "{$languageId}-{$this->styleId}-{$type}-{$template}";

        if (isset($this->templateCache[$cacheKey])) {
            return $this->templateCache[$cacheKey];
        }

        return null;
    }

    /**
     * @param $username
     * @return mixed
     */
    protected function getDefaultAvatarStyling($username)
    {
        $options = \XF::options();

        $style = $this->getStyle();
        if (!$style) {
            $style = \XF::app()->container('style.fallback');
        }

        if (!$options->th_materialAvatars_uix && !$style->getProperty('uix_materialAvatars')) {
            return parent::getDefaultAvatarStyling($username);
        }

        if (!isset($this->avatarDefaultStylingCache[$username])) {
            $hash = md5($username);
            $colorKey = (int)floor(hexdec(substr($hash, 0, 4)) / 683);

            if (!isset($this->uix_materialColors[$colorKey])) {
                $colorKey = 0;
            }

            $colorValue = $this->uix_materialColors[$colorKey];


            if (preg_match($this->avatarLetterRegex, $username, $match)) {
                $innerContent = htmlspecialchars(utf8_strtoupper($match[0]));
            } else {
                $innerContent = '?';
            }

            $this->avatarDefaultStylingCache[$username] = [
                'bgColor' => $colorValue['bgColor'],
                'color' => $colorValue['textColor'],
                'innerContent' => $innerContent
            ];
        }

        return $this->avatarDefaultStylingCache[$username];
    }
}
