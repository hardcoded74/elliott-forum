<?php

namespace ThemeHouse\Monetize\Listener\Admin;

use XF\Template\Templater;

/**
 * Class TemplaterMacroPreRender
 * @package ThemeHouse\Monetize\Listener\Admin
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
        if ($arguments['group']->group_id == 'thmonetize') {
            $template = 'thmonetize_option_macros';
            $tabs = [
                \XF::phrase('thmonetize_affiliate_links_and_keywords'),
                \XF::phrase('thmonetize_user_upgrades'),
                \XF::phrase('thmonetize_sponsors'),
                \XF::phrase('thmonetize_communication'),
                \XF::phrase('thmonetize_advanced_options'),
            ];
            $options = [];
            foreach ($arguments['options'] as $optionId => $option) {
                foreach ($tabs as $key => $title) {
                    if ($option->Relations[$arguments['group']->group_id]->display_order < ($key * 1000 + 1000)) {
                        $options[$key][$optionId] = $option;
                        continue(2);
                    }
                }
            }
            $arguments['options'] = $options;
            $arguments['tabs'] = $tabs;
        }
    }
}
