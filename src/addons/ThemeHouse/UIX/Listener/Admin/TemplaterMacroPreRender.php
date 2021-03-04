<?php

namespace ThemeHouse\UIX\Listener\Admin;

use XF\Template\Templater;

/**
 * Class TemplaterMacroPreRender
 * @package ThemeHouse\UIX\Listener\Admin
 */
class TemplaterMacroPreRender
{
    /**
     * @param Templater $templater
     * @param $type
     * @param $template
     * @param $name
     * @param array $arguments
     * @param array $globalVars
     */
    public static function optionMacrosOptionFormBlock(
        Templater $templater,
        &$type,
        &$template,
        &$name,
        array &$arguments,
        array &$globalVars
    ) {
        if ($arguments['group']->group_id == 'th_uix') {
            $template = 'thuix_options';
        }
    }
}
