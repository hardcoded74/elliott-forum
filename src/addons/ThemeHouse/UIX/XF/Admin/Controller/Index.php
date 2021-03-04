<?php

namespace ThemeHouse\UIX\XF\Admin\Controller;

use XF\Mvc\Reply\View;

/**
 * Class Index
 * @package ThemeHouse\UIX\XF\Admin\Controller
 */
class Index extends XFCP_Index
{
    /**
     * @return View
     */
    public function actionIndex()
    {
        $response = parent::actionIndex();

        if ($response instanceof View) {
            $adminNotices = [];
            if (\XF::options()->th_updateCheck_uix) {
                $updateStatus = \XF::registry()['uix_updateStatus'];
                if (empty($updateStatus)) {
                    $updateStatus = [];
                }

                if (!empty($updateStatus['addon'])) {
                    $addOnStatus = $updateStatus['addon'];
                    if ($addOnStatus['requires_update']) {
                        $adminNotices[] = [
                            'class' => 'blockMessage--important',
                            'message' => \XF::phrase('th_uix_addon_requires_update_uix'),
                        ];
                    }

                    if ($addOnStatus['prerelease']) {
                        $adminNotices[] = [
                            'class' => 'blockMessage--warning',
                            'message' => \XF::phrase('th_uix_addon_prerelease_uix'),
                        ];
                    }

                    unset($updateStatus['addon']);
                }

                $prereleaseCount = 0;
                $updateRequiredCount = 0;
                $noVersionAvailableCount = 0;

                foreach ($updateStatus as $status) {
                    if ($status['requires_update']) {
                        $updateRequiredCount++;
                    }
                    if ($status['prerelease']) {
                        $prereleaseCount++;
                    }
                    if (!empty($status['no_version_available'])) {
                        $noVersionAvailableCount++;
                    }
                }

                if ($prereleaseCount) {
                    $adminNotices[] = [
                        'class' => 'blockMessage--warning',
                        'message' => \XF::phrase('th_x_prerelease_styles_uix', [
                            'count' => $prereleaseCount,
                        ]),
                    ];
                }

                if ($updateRequiredCount) {
                    $adminNotices[] = [
                        'class' => 'blockMessage--important',
                        'message' => \XF::phrase('th_x_updated_styles_uix', [
                            'count' => $updateRequiredCount,
                        ]),
                    ];
                }

                if ($noVersionAvailableCount) {
                    $adminNotices[] = [
                        'class' => 'blockMessage--important',
                        'message' => \XF::phrase('th_x_no_version_available_styles_uix', [
                            'count' => $noVersionAvailableCount,
                        ]),
                    ];
                }
            }

            $response->setParam('uix_adminNotices', $adminNotices);
        }

        return $response;
    }
}
