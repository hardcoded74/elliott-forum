<?php

namespace ThemeHouse\UIX\XF\Pub\Controller;

use XF\Entity\User;

/**
 * Class Account
 * @package ThemeHouse\UIX\XF\Pub\Controller
 */
class Account extends XFCP_Account
{
    /**
     * @param User $visitor
     * @return \XF\Mvc\FormAction
     */
    protected function preferencesSaveProcess(User $visitor)
    {
        $form = parent::preferencesSaveProcess($visitor);

        $userOptions = $visitor->getRelationOrDefault('Option');

        if (\XF::options()->thuix_enableStyleOptions) {
            $input = $this->filter([
                'option' => [
                    'thuix_collapse_sidebar' => 'bool',
                    'thuix_collapse_sidebar_nav' => 'bool',
                    'thuix_collapse_postbit' => 'bool',
                ],
            ]);

            $sidebarCollapseDefault = \XF::options()->th_sidebarCollapseDefault_uix;
            if (in_array($sidebarCollapseDefault, ['registered', 'all'])) {
                $input['option']['thuix_collapse_sidebar'] = !$input['option']['thuix_collapse_sidebar'];
            }

            $sidebarNavCollapseDefault = \XF::options()->th_sidebarNavCollapseDefault_uix;
            if (in_array($sidebarNavCollapseDefault, ['registered', 'all'])) {
                $input['option']['thuix_collapse_sidebar_nav'] = !$input['option']['thuix_collapse_sidebar_nav'];
            }

            $postbitCollapseDefault = \XF::options()->th_postbitCollapseDefault_uix;
            if (in_array($postbitCollapseDefault, ['registered', 'all'])) {
                $input['option']['thuix_collapse_postbit'] = !$input['option']['thuix_collapse_postbit'];
            }

            $form->setupEntityInput($userOptions, $input['option']);
        }

        if (\XF::$versionId >= 2010010) {
            $input = $this->filter([
                'option' => [
                    'thuix_font_size' => 'int',
                ],
            ]);

            if ($input['option']['thuix_font_size'] < -1) {
                $input['option']['thuix_font_size'] = -1;
            } elseif ($input['option']['thuix_font_size'] > 2) {
                $input['option']['thuix_font_size'] = 2;
            }

            $form->setupEntityInput($userOptions, $input['option']);
        }

        return $form;
    }
}
