<?php

namespace ThemeHouse\UIX\Listener\Pub;

use ThemeHouse\UIX\Util\UIX;
use XF\Template\Templater;

/**
 * Class TemplaterMacroPreRender
 * @package ThemeHouse\UIX\Listener\Pub
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
    public static function helperJsGlobalBody(
        Templater $templater,
        &$type,
        &$template,
        &$name,
        array &$arguments,
        array &$globalVars
    ) {
        $uix = new UIX();

        $globalVars['uix_backstretchImages'] = $uix->getBackstretchImages($templater);
    }

    /**
     * @param Templater $templater
     * @param $type
     * @param $template
     * @param $name
     * @param array $arguments
     * @param array $globalVars
     */
    public static function messageMacrosUserInfo(
        Templater $templater,
        &$type,
        &$template,
        &$name,
        array &$arguments,
        array &$globalVars
    ) {
        $style = $templater->getStyle();

        if ($style->getProperty('uix_collapseExtraInfo')) {
            if (isset(\XF::options()->th_postbitCollapseDefault_uix)) {
                $postbitCollapseDefault = \XF::options()->th_postbitCollapseDefault_uix;
                if (\XF::visitor()->user_id) {
                    if (\XF::options()->thuix_enableStyleOptions) {
                        /** @noinspection PhpUndefinedFieldInspection */
                        $postbitCollapsed = \XF::visitor()->Option->thuix_collapse_postbit;
                    } else {
                        $postbitCollapsed = (in_array($postbitCollapseDefault, ['registered', 'all']));
                    }
                } else {
                    $postbitCollapsed = (in_array($postbitCollapseDefault, ['unregistered', 'all']));
                }
            } else {
                $postbitCollapsed = true;
            }

            $globalVars['uix_postbitCollapsed'] = $postbitCollapsed;
        }
    }
}
