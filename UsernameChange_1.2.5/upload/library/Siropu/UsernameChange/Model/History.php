<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_Model_History extends Xenforo_Model
{
	public function getAllHistory($search = '', $fetchOptions)
	{
		$limitOptions = $this->prepareLimitFetchOptions($fetchOptions);

		$db    = $this->_getDb();
		$where = '';

		if ($search)
		{
			if (is_numeric($search))
			{
				$where = 'WHERE user_id = ' . $db->quote($search);
			}
			else
			{
				$where = 'WHERE username_old = ' . $db->quote($search);
			}
		}

		return $db->fetchAll($this->limitQueryResults('
			SELECT *
			FROM xf_siropu_username_change_history
			' . $where . '
			ORDER BY date DESC
		', $limitOptions['limit'], $limitOptions['offset']));
	}
	public function getAllHistoryCount($search = '')
	{
		$db    = $this->_getDb();
		$where = '';

		if ($search)
		{
			if (is_numeric($search))
			{
				$where = 'WHERE user_id = ' . $db->quote($search);
			}
			else
			{
				$where = 'WHERE username_old = ' . $db->quote($search);
			}
		}

		$result = $db->fetchRow('
			SELECT COUNT(*) AS count
			FROM xf_siropu_username_change_history
			' . $where
		);

		return $result['count'];
	}
	public function getHistoryById($id)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
			FROM xf_siropu_username_change_history
			WHERE history_id = ?
			', $id);
	}
	public function getUserHistory($userId, $visitor)
	{
		return $this->_getDb()->fetchAll('
			SELECT *
			FROM xf_siropu_username_change_history
			WHERE user_id = ? '
			. (!$visitor->is_admin ? 'AND incognito = 0' : '') . 
			' ORDER BY history_id DESC'
		, $userId);
	}
	public function usernameExistsInHistory($username, $userId = 0)
	{
		$row = $this->_getDb()->fetchRow('
			SELECT *
			FROM xf_siropu_username_change_history
			WHERE username_old = ?
			', $username);

		if ($row)
		{
			if ($userId && $userId == $row['user_id'])
			{
				return false;	
			}
			return true;
		}
	}
	public function getUserChangeCount($userId, $timeFrame = 0)
	{
		$count = $this->_getDb()->fetchRow('
			SELECT COUNT(history_id) AS count
			FROM xf_siropu_username_change_history
			WHERE user_id = ? '
			. ($timeFrame ? 'AND date BETWEEN ' . strtotime("-{$timeFrame} days") . ' AND ' . time() : '')
			, $userId);

		return $count['count'];
	}
	public function getUserLastUsernameChange($userId, $timeFrame)
	{
		$row = $this->_getDb()->fetchRow('
			SELECT *
			FROM xf_siropu_username_change_history
			WHERE user_id = ?
			ORDER BY date DESC
			', $userId);
	
		if ($row)
		{
			return Siropu_UsernameChange_Helper::getUserWaitTime($row, $timeFrame);
		}
	}
}