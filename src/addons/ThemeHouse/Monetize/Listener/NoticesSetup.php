<?php

namespace ThemeHouse\Monetize\Listener;

use XF\NoticeList;
use XF\Pub\App;

/**
 * Class NoticesSetup
 * @package ThemeHouse\Monetize\Listener
 */
class NoticesSetup
{
    /**
     * @param App $app
     * @param NoticeList $noticeList
     * @param array $pageParams
     */
    public static function noticesSetup(App $app, NoticeList $noticeList, array $pageParams)
    {
        $visitor = \XF::visitor();
        $templater = $app->templater();

        if ($visitor->user_id && in_array($visitor->user_state, ['thmonetize_upgrade'])) {
            $noticeList->addNotice(
                'thmonetize_upgrade',
                'block',
                $templater->renderTemplate('public:thmonetize_notice_upgrade', $pageParams)
            );
        }
    }
}
