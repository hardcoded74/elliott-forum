<?php

namespace ThemeHouse\Monetize\Listener\Pub;

use XF\Template\Templater;

class TemplaterTemplatePostRender
{
    public static function accountUpgrades(Templater $templater, $type, $template, &$output)
    {
        $templater->includeCss('public:thmonetize_upgrade_page.less');
        $templater->includeCss('public:thmonetize_user_upgrade_cache.less');
    }
}
