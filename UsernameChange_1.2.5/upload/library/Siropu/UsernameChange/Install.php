<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_Install
{
	public static function install()
	{
		self::_getDb()->query("
			CREATE TABLE IF NOT EXISTS `xf_siropu_username_change_tables`(
				`table_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`table_name` VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL,
				`column_name` VARCHAR(128) COLLATE utf8_unicode_ci NOT NULL,
				`description` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL
			) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
		");

		self::_getDb()->query("
			CREATE TABLE IF NOT EXISTS `xf_siropu_username_change_history`(
				`history_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`user_id` INT(10) UNSIGNED NOT NULL,
				`username_old` VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
				`username_new` VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
				`date` INT(10) UNSIGNED NOT NULL,
				`incognito` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
				INDEX `user_id` (`user_id`),
				INDEX `username_old` (`username_old`),
				INDEX `date` (`date`),
				INDEX `incognito` (`incognito`)
			) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
		");

	}
	public static function uninstall()
	{
		self::_getDb()->query('
			DROP TABLE
				`xf_siropu_username_change_tables`,
				`xf_siropu_username_change_history`
		');
	}
	protected static function _getDb()
	{
		return XenForo_Application::get('db');
	}
}