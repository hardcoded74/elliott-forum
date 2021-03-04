<?php

namespace ThemeHouse\Monetize\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class AlertLog
 * @package ThemeHouse\Monetize\Repository
 */
class AlertLog extends Repository
{
    /**
     * @return Finder
     */
    public function findLogsForList()
    {
        return $this->finder('ThemeHouse\Monetize:AlertLog')
            ->with('User')
            ->with('Alert')
            ->setDefaultOrder('log_date', 'DESC');
    }

    /**
     * @param $alertId
     * @return Finder
     */
    public function findLogsForAlert($alertId)
    {
        return $this->finder('ThemeHouse\Monetize:AlertLog')
            ->where(['alert_id' => $alertId])
            ->with('User')
            ->setDefaultOrder('log_date', 'DESC');
    }

    /**
     * @return array
     */
    public function getUsersInLog()
    {
        return $this->db()->fetchPairs("
			SELECT user.user_id, user.username
			FROM (
				SELECT DISTINCT user_id FROM xf_th_monetize_alert_log
			) AS log
			INNER JOIN xf_user AS user ON (log.user_id = user.user_id)
			ORDER BY user.username
		");
    }

    /**
     * @param null $cutOff
     * @return int
     */
    public function pruneAlertLogs($cutOff = null)
    {
        if ($cutOff === null) {
            $logLength = $this->options()->thmonetize_alertLogLength;
            if (!$logLength) {
                return 0;
            }

            $cutOff = \XF::$time - 86400 * $logLength;
        }

        return $this->db()->delete('xf_th_monetize_alert_log', 'log_date < ?', $cutOff);
    }
}
