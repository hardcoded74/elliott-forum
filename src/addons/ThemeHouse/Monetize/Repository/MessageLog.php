<?php

namespace ThemeHouse\Monetize\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class MessageLog
 * @package ThemeHouse\Monetize\Repository
 */
class MessageLog extends Repository
{
    /**
     * @return Finder
     */
    public function findLogsForList()
    {
        return $this->finder('ThemeHouse\Monetize:MessageLog')
            ->with('User')
            ->with('Message')
            ->setDefaultOrder('log_date', 'DESC');
    }

    /**
     * @param $messageId
     * @return Finder
     */
    public function findLogsForMessage($messageId)
    {
        return $this->finder('ThemeHouse\Monetize:MessageLog')
            ->where(['message_id' => $messageId])
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
				SELECT DISTINCT user_id FROM xf_th_monetize_message_log
			) AS log
			INNER JOIN xf_user AS user ON (log.user_id = user.user_id)
			ORDER BY user.username
		");
    }

    /**
     * @param null $cutOff
     * @return int
     */
    public function pruneMessageLogs($cutOff = null)
    {
        if ($cutOff === null) {
            $logLength = $this->options()->thmonetize_messageLogLength;
            if (!$logLength) {
                return 0;
            }

            $cutOff = \XF::$time - 86400 * $logLength;
        }

        return $this->db()->delete('xf_th_monetize_message_log', 'log_date < ?', $cutOff);
    }
}
