<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_DataWriter_Tables extends Xenforo_DataWriter
{
	protected function _getFields()
	{
		return array(
			'xf_siropu_username_change_tables' => array(
				'table_id'    => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
				'table_name'  => array('type' => self::TYPE_STRING, 'required' => true),
				'column_name' => array('type' => self::TYPE_STRING, 'required' => true),
				'description' => array('type' => self::TYPE_STRING, 'default' => '')
			)
		);
	}
	protected function _getExistingData($data)
	{
		if ($id = $this->_getExistingPrimaryKey($data, 'table_id'))
		{
			return array('xf_siropu_username_change_tables' => $this->_getModel()->getTableById($id));
		}
	}
	protected function _getUpdateCondition($tableName)
	{
		return 'table_id = ' . $this->_db->quote($this->getExisting('table_id'));
	}
	protected function _getModel()
	{
		return $this->getModelFromCache('Siropu_UsernameChange_Model_Tables');
	}
}