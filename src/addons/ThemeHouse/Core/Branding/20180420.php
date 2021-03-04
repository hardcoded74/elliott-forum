<?php

namespace ThemeHouse\Core;

class Branding
{
    public static function renderStyleBranding()
    {
        $config = \XF::app()->config();
        if (isset($config['removeThemeHouseBranding']) && $config['removeThemeHouseBranding']) {
            return '';
        }

        $addOns = \XF::app()->get('addon.cache');
        $thAddOnsInstalled = 0;
        foreach ($addOns as $addOnId=>$version) {
            if (strpos($addOnId, 'ThemeHouse/') !== false && $addOnId !== 'ThemeHouse/UIX') {
                ++$thAddOnsInstalled;
            }
        }

        if ($_SERVER['HTTP_HOST']) {
            $domain = $_SERVER['HTTP_HOST'];
        } else {
            $options = \XF::options();
            $urlParts = parse_url($options->boardUrl);
            if ($urlParts['host']) {
                $domain = $urlParts['host'];
            } else {
                $domain = $options->boardUrl;
            }
        }

         $baseBranding = '<span class="thBranding"><span class="thBranding__pipe"> | </span><a href="https://www.themehouse.com/?utm_source=' . urlencode($domain) . '&utm_medium=xf2product&utm_campaign=product_branding" class="u-concealed" target="_BLANK" nofollow="nofollow">Style{additional} by ThemeHouse</a></span>';

        $additionalBranding = '';
        if ($thAddOnsInstalled) {
            $additionalBranding = ' and add-ons';
        }
        $branding = str_replace('{additional}', $additionalBranding, $baseBranding);

        return $branding;
    }

    public static function renderAddonBranding(array $matches)
    {
        $config = \XF::app()->config();
        if (isset($config['removeThemeHouseBranding']) && $config['removeThemeHouseBranding']) {
            return $matches[0];
        }

        if (isset($_SERVER['HTTP_HOST'])) {
            $domain = $_SERVER['HTTP_HOST'];
        } else {
            $options = \XF::options();
            $urlParts = parse_url($options->boardUrl);
            if ($urlParts['host']) {
                $domain = $urlParts['host'];
            } else {
                $domain = $options->boardUrl;
            }
        }

        return $matches[0] . '<xf:if is="!{$thBrandingDisplayed}"><xf:set var="$thBrandingDisplayed" value="1" /><span class="thBranding"> | <a href="https://www.themehouse.com/?utm_source=' . urlencode($domain) . '&utm_medium=xf2product&utm_campaign=product_branding" class="u-concealed" target="_BLANK" nofollow="nofollow">Add-ons by ThemeHouse</a></span></xf:if>';
    }
}
