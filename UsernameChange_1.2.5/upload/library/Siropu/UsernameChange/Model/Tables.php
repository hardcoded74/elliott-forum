<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_Model_Tables extends Xenforo_Model
{
	public function getAllTables()
	{
		return $this->_getDb()->fetchAll('
			SELECT *
			FROM xf_siropu_username_change_tables
		');
	}
	public function getTableById($id)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
			FROM xf_siropu_username_change_tables
			WHERE table_id = ?
			', $id);
	}
	public function checkIfTableExists($data)
	{
		$table = $this->_getDb()->fetchRow('
			SELECT TABLE_NAME
			FROM information_schema.TABLES
			WHERE TABLE_NAME = ?
			', $data['table_name']);

		if ($table)
		{
			return true;
		}
	}
	public function checkIfColumnExists($data)
	{
		$column = $this->_getDb()->fetchRow('
			SHOW COLUMNS FROM ' . $data['table_name'] . '
			WHERE Field = ?
			', $data['column_name']);

		if ($column)
		{
			return true;
		}
	}
	public function changeUsernameInTables($data)
	{
		$db = $this->_getDb();

		foreach ($this->getAllTables() as $table)
		{
			$db->query('
				UPDATE IGNORE ' . $table['table_name'] . '
				SET ' . $table['column_name'] . '=' . $db->quote($data['username_new']) . '
				WHERE ' . $table['column_name'] . ' = ?
			', $data['username_old']);
		}
	}
}