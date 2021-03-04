<?php

namespace ThemeHouse\Monetize\Cron;

/**
 * Class CleanUp
 * @package ThemeHouse\Monetize\Cron
 */
class CleanUp
{
    /**
     * Clean up tasks that should be done daily. This task cannot be relied on
     * to run daily, consistently.
     */
    public static function runDailyCleanUp()
    {
        $app = \XF::app();

        /** @var \ThemeHouse\Monetize\Repository\AlertLog $alertLogRepo */
        $alertLogRepo = $app->repository('ThemeHouse\Monetize:AlertLog');
        $alertLogRepo->pruneAlertLogs();

        /** @var \ThemeHouse\Monetize\Repository\EmailLog $emailLogRepo */
        $emailLogRepo = $app->repository('ThemeHouse\Monetize:EmailLog');
        $emailLogRepo->pruneEmailLogs();

        /** @var \ThemeHouse\Monetize\Repository\MessageLog $messageLogRepo */
        $messageLogRepo = $app->repository('ThemeHouse\Monetize:MessageLog');
        $messageLogRepo->pruneMessageLogs();
    }
}
