<?php

namespace ThemeHouse\Monetize\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class EmailLog
 * @package ThemeHouse\Monetize\Repository
 */
class EmailLog extends Repository
{
    /**
     * @return Finder
     */
    public function findLogsForList()
    {
        return $this->finder('ThemeHouse\Monetize:EmailLog')
            ->with('User')
            ->with('Email')
            ->setDefaultOrder('log_date', 'DESC');
    }

    /**
     * @param $emailId
     * @return Finder
     */
    public function findLogsForEmail($emailId)
    {
        return $this->finder('ThemeHouse\Monetize:EmailLog')
            ->where(['email_id' => $emailId])
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
				SELECT DISTINCT user_id FROM xf_th_monetize_email_log
			) AS log
			INNER JOIN xf_user AS user ON (log.user_id = user.user_id)
			ORDER BY user.username
		");
    }

    /**
     * @param null $cutOff
     * @return int
     */
    public function pruneEmailLogs($cutOff = null)
    {
        if ($cutOff === null) {
            $logLength = $this->options()->thmonetize_emailLogLength;
            if (!$logLength) {
                return 0;
            }

            $cutOff = \XF::$time - 86400 * $logLength;
        }

        return $this->db()->delete('xf_th_monetize_email_log', 'log_date < ?', $cutOff);
    }
}
