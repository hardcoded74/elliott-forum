<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_DataWriter_History extends Xenforo_DataWriter
{
	protected function _getFields()
	{
		return array(
			'xf_siropu_username_change_history' => array(
				'history_id'   => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
				'user_id'      => array('type' => self::TYPE_UINT, 'required' => true),
				'username_old' => array('type' => self::TYPE_STRING, 'required' => true),
				'username_new' => array('type' => self::TYPE_STRING, 'required' => true),
				'date'         => array('type' => self::TYPE_UINT, 'default' => XenForo_Application::$time),
				'incognito'    => array('type' => self::TYPE_UINT, 'default' => 0),
			)
		);
	}
	protected function _getExistingData($data)
	{
		if ($id = $this->_getExistingPrimaryKey($data, 'history_id'))
		{
			return array('xf_siropu_username_change_history' => $this->_getModel()->getHistoryById($id));
		}
	}
	protected function _getUpdateCondition($tableName)
	{
		return 'history_id = ' . $this->_db->quote($this->getExisting('history_id'));
	}
	protected function _getModel()
	{
		return $this->getModelFromCache('Siropu_UsernameChange_Model_History');
	}
}