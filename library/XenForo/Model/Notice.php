<?php

class XenForo_Model_Notice extends XenForo_Model
{
	/**
	 * Fetch a single notice by its notice_id
	 *
	 * @param integer $noticeId
	 *
	 * @return array
	 */
	public function getNoticeById($noticeId)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
			FROM xf_notice
			WHERE notice_id = ?
		', $noticeId);
	}

	public function getDefaultNotice()
	{
		return array(
			'notice_id' => 0,

			'title' => '',
			'message' => '',

			'display_image' => '',
			'visibility' => '',
			'notice_type' => 'block',
			'display_style' => 'primary',

			'display_duration' => 6000,
			'delay_duration' => 0,

			'user_criteria' => '',
			'userCriteriaList' => array(),

			'page_criteria' => '',
			'pageCriteriaList' => array(),

			'active' => 1,
			'dismissible' => 1,
			'display_order' => 1,
			'wrap' => 1,
		);
	}

	/**
	 * Fetch all notices from the database
	 *
	 * @return array
	 */
	public function getAllNotices()
	{
		return $this->fetchAllKeyed('
			SELECT *
			FROM xf_notice
			ORDER BY display_order
		', 'notice_id');
	}

	public function getAllNoticesGrouped()
	{
		$grouped = array();

		foreach ($this->getAllNotices() AS $noticeId => $notice)
		{
			$grouped[$notice['notice_type']][$noticeId] = $notice;
		}

		return $grouped;
	}

	public function countNotices()
	{
		return $this->_getDb()->fetchOne('
			SELECT COUNT(*)
			FROM xf_notice
		');
	}

	public function prepareNotice(array $notice)
	{
		return $notice;
	}

	public function rebuildNoticeCache()
	{
		$cache = array();

		foreach ($this->getAllNotices() AS $noticeId => $notice)
		{
			if ($notice['active'])
			{
				$cache[$noticeId] = array(
					'title' => $notice['title'],
					'message' => $notice['message'],
					'dismissible' => $notice['dismissible'],
					'wrap' => $notice['wrap'],
					'user_criteria' => XenForo_Helper_Criteria::unserializeCriteria($notice['user_criteria']),
					'page_criteria' => XenForo_Helper_Criteria::unserializeCriteria($notice['page_criteria']),
					'display_image' => $notice['display_image'],
					'image_url' => $notice['image_url'],
					'visibility' => $notice['visibility'],
					'notice_type' => $notice['notice_type'],
					'display_style' => $notice['display_style'],
					'css_class' => $notice['css_class'],
					'display_duration' => $notice['display_duration'],
					'delay_duration' => $notice['delay_duration'],
					'auto_dismiss' => $notice['auto_dismiss']
				);
			}
		}

		$this->_getDataRegistryModel()->set('notices', $cache);
		return $cache;
	}

	public function canDismissNotice(array $notice, &$errorPhraseKey = '', array $viewingUser = null)
	{
		$this->standardizeViewingUserReference($viewingUser);

		if (empty($viewingUser['user_id']) || empty($notice['dismissible']))
		{
			$errorPhraseKey = 'you_may_not_dismiss_this_notice';
			return false;
		}

		return true;
	}

	public function dismissNotice($noticeId, $userId = null)
	{
		if (empty($userId))
		{
			$userId = XenForo_Visitor::getUserId();
		}

		if (!$userId)
		{
			return;
		}

		$this->_getDb()->query('
			INSERT IGNORE INTO xf_notice_dismissed
				(notice_id, user_id, dismiss_date)
			VALUES
				(?, ?, ?)
		', array($noticeId, $userId, XenForo_Application::$time));
	}

	public function restoreNotices(array $user = null)
	{
		$this->standardizeViewingUserReference($user);

		if (!$user['user_id'])
		{
			return;
		}

		$db = $this->_getDb();

		$db->delete('xf_notice_dismissed', 'user_id = ' . $db->quote($user['user_id']));
	}

	public function getDismissedNoticeIdsForUser($userId)
	{
		if (!$userId)
		{
			return array();
		}

		return $this->_getDb()->fetchCol('
			SELECT notice_id
			FROM xf_notice_dismissed
			WHERE user_id = ?
		', $userId);
	}

	public function resetNotice($noticeId)
	{
		$db = $this->_getDb();

		$db->delete('xf_notice_dismissed', 'notice_id = ' . $db->quote($noticeId));
		XenForo_Application::setSimpleCacheData('lastNoticeReset', time());
	}

	public function getNoticesForAdminQuickSearch($searchText)
	{
		$quotedString = XenForo_Db::quoteLike($searchText, 'lr', $this->_getDb());

		return $this->fetchAllKeyed('
			SELECT * FROM xf_notice
			WHERE title LIKE ' . $quotedString . '
			ORDER BY display_order
		', 'notice_id');
	}
}