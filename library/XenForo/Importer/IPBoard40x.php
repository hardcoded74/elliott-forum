<?php

class XenForo_Importer_IPBoard40x extends XenForo_Importer_IPBoard34x
{
	/**
	 * Name of meta area key for content tags import
	 *
	 * @var string
	 */
	protected $_metaArea = 'forums';

	public static function getName()
	{
		return 'IP.Board 4.0/4.1 (Beta)';
	}

	public function validateConfiguration(array &$config)
	{
		$errors = array();

		$config['db']['prefix'] = preg_replace('/[^a-z0-9_]/i', '', $config['db']['prefix']);

		try
		{
			$db = Zend_Db::factory('mysqli',
				array(
					'host' => $config['db']['host'],
					'port' => $config['db']['port'],
					'username' => $config['db']['username'],
					'password' => $config['db']['password'],
					'dbname' => $config['db']['dbname'],
					'charset' => $config['db']['charset']
				)
			);
			$db->getConnection();
		}
		catch (Zend_Db_Exception $e)
		{
			$errors[] = new XenForo_Phrase('source_database_connection_details_not_correct_x', array('error' => $e->getMessage()));
		}

		if ($errors)
		{
			return $errors;
		}

		try
		{
			$db->query('
				SELECT member_id
				FROM ' . $config['db']['prefix'] . 'core_members
				LIMIT 1
			');
		}
		catch (Zend_Db_Exception $e)
		{
			if ($config['db']['dbname'] === '')
			{
				$errors[] = new XenForo_Phrase('please_enter_database_name');
			}
			else
			{
				$errors[] = new XenForo_Phrase('table_prefix_or_database_name_is_not_correct');
			}
		}

		if (!empty($config['ipboard_path']))
		{
			if (!file_exists($config['ipboard_path']) || !is_dir($config['ipboard_path'] . '/uploads'))
			{
				$errors[] = new XenForo_Phrase('error_could_not_find_uploads_directory_at_specified_path');
			}
		}

		if (!$errors)
		{
			$config['charset'] = 'utf8';
		}

		return $errors;
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::getSteps()
	 */
	public function getSteps()
	{
		$originalSteps = parent::getSteps();

		$newStep = array(
			'title' => new XenForo_Phrase('import_ignored_users'),
			'depends' => array('users')
		);
		$steps = $this->_injectNewStep($originalSteps, $newStep, 'ignoredUsers', 'users');

		return $steps;
	}

	public function stepUserGroups($start, array $options)
	{
		$options = array_merge(array
		(
			'guest_group' => 2,
			'member_group' => 3,
			'admin_group' => 4
		), $options);

		$this->_session->setExtraData('groups', $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		/* @var $model XenForo_Model_Import */
		$model = $this->_importModel;
		$model->retainableKeys[] = 'user_group_id';

		$userGroups = $sDb->fetchAll('
			SELECT ugroup.*, lang.word_custom AS g_title
			FROM ' . $prefix . 'core_groups AS ugroup
			LEFT JOIN ' . $prefix . 'core_sys_lang_words AS lang ON
				(lang.lang_id = 1 AND lang.word_key = CONCAT(\'core_group_\', ugroup.g_id))
			ORDER BY ugroup.g_id
		');

		$total = 0;

		XenForo_Db::beginTransaction();

		foreach ($userGroups AS $userGroup)
		{
			$titlePriority = 5;
			switch ($userGroup['g_id'])
			{
				case $options['guest_group']: // guests (default 2)
					$model->logImportData('userGroup', $userGroup['g_id'], XenForo_Model_User::$defaultGuestGroupId);
					break;

				case $options['member_group']: // registered users (default 3)
					$model->logImportData('userGroup', $userGroup['g_id'], XenForo_Model_User::$defaultRegisteredGroupId);
					break;

				case $options['admin_group']: // admins (default 4)
					$model->logImportData('userGroup', $userGroup['g_id'], XenForo_Model_User::$defaultAdminGroupId);
					continue;

				case 6: // mods
					$model->logImportData('userGroup', $userGroup['g_id'], XenForo_Model_User::$defaultModeratorGroupId);
					continue;

				default:
					$import = array(
						'title' => $this->_convertToUtf8($userGroup['g_title']),
						'user_title' => $this->_convertToUtf8($userGroup['g_title']),
						'display_style_priority' => $titlePriority,
						'permissions' => $this->_calculateUserGroupPermissions($userGroup)
					);

					if ($model->importUserGroup($userGroup['g_id'], $import))
					{
						$total++;
					}
			}
		}

		XenForo_Model::create('XenForo_Model_UserGroup')->rebuildDisplayStyleCache();

		XenForo_Db::commit();

		$this->_session->incrementStepImportTotal($total);

		return true;
	}

	protected function _getUserGroupAvatarPerms(array $userGroup)
	{
		$avatarPerms = array();

		$photoVars = intval($userGroup['g_photo_max_vars']); // take the first value from '500:170:240'
		if ($photoVars)
		{
			$avatarPerms['allowed'] = 'allow';
			$avatarPerms['maxFileSize'] = $photoVars;
			if ($avatarPerms['maxFileSize'] > 2147483647)
			{
				$avatarPerms['maxFileSize'] = -1;
			}
		}

		return $avatarPerms;
	}

	public function stepUserFields($start, array $options)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		/* @var $model XenForo_Model_Import */
		$model = $this->_importModel;

		$profileFields = $sDb->fetchAll('
			SELECT pfields_data.*, pfields_groups.*,
				lang_title.word_custom AS pf_title,
				lang_desc.word_custom AS pf_desc
			FROM ' . $prefix . 'core_pfields_data AS pfields_data
			INNER JOIN ' . $prefix . 'core_pfields_groups AS pfields_groups ON
				(pfields_groups.pf_group_id = pfields_data.pf_group_id)
			LEFT JOIN ' . $prefix . 'core_sys_lang_words AS lang_title ON
				(lang_title.lang_id = 1 AND lang_title.word_key = CONCAT(\'core_pfield_\', pfields_data.pf_id))
			LEFT JOIN ' . $prefix . 'core_sys_lang_words AS lang_desc ON
				(lang_desc.lang_id = 1 AND lang_desc.word_key = CONCAT(\'core_pfield_\', pfields_data.pf_id, \'_desc\'))
		');

		$existingFields = $model->getUserFieldDefinitions();

		$total = 0;

		XenForo_Db::beginTransaction($this->_db);

		foreach ($profileFields AS &$profileField)
		{
			$profileField['pf_key'] = preg_replace('/ /', '_', preg_replace('/[^0-9a-zA-Z ]/', '', strtolower($profileField['pf_title'])));

			switch ($profileField['pf_key'])
			{
				case 'icq':
				case 'aim':
				case 'facebook':
				case 'msn':
				case 'yahoo':
				case 'skype':
				case 'twitter':
				case 'gender':
				case 'website_url':
				case 'location':
				case 'interests':
				{
					// just store the mapping, no need to import these
					$model->logImportData('userField', $profileField['pf_id'], $this->_convertToUtf8($profileField['pf_key']));
					break;
				}

				default:
				{
					$fieldId = $this->_convertToUtf8($model->getUniqueFieldId($profileField['pf_key'], $existingFields, 25));

					$convertChoices = false;

					switch (strtolower($profileField['pf_type']))
					{
						case 'textarea':
							$fieldType = 'textarea';
							break;

						case 'select':
							$fieldType = 'select';
							$convertChoices = true;
							break;

						case 'checkbox':
						case 'checkboxset':
							$fieldType = 'checkbox';
							$convertChoices = true;
							break;

						case 'radio':
							$fieldType = 'radio';
							$convertChoices = true;
							break;

						case 'text':
						default:
							$fieldType ='textbox';
							break;
					}

					if ($profileField['pf_admin_only'])
					{
						$profileField['pf_member_hide'] = true;
						$profileField['pf_member_edit'] = false;
					}

					$displayGroup = 'personal';
					if (isset($profileField['pf_group_key']))
					{
						if ($profileField['pf_group_key'] == 'contact')
						{
							$displayGroup = 'contact';
						}
					}

					$import = array(
						'field_id' => $fieldId,
						'title' => $this->_convertToUtf8($profileField['pf_title']),
						'description' => $this->_convertToUtf8($profileField['pf_desc']),
						'field_type' => $fieldType,
						'display_order' => $profileField['pf_position'],
						'display_group' => $displayGroup,
						'max_length' => $profileField['pf_max_input'],
						'required' => $profileField['pf_not_null'],
						'show_registration' => $profileField['pf_show_on_reg'],
						'user_editable' => ($profileField['pf_member_edit'] ? 'yes' : 'never'),
						'viewable_profile' => !$profileField['pf_member_hide']
					);

					if ($profileField['pf_input_format'])
					{
						$import['match_type'] = 'regex';
						$import['match_regex'] = $this->_convertUserFieldMatchTypeToRegex($profileField['pf_input_format']);
					}

					if ($convertChoices)
					{
						$import['field_choices'] = json_decode($profileField['pf_content'], true);
					}

					if ($newFieldId = $model->importUserField($profileField['pf_id'], $import))
					{
						$total++;
					}
					break;
				}
			}
		}

		XenForo_Db::commit($this->_db);

		$this->_session->incrementStepImportTotal($total);

		return true;
	}

	/**
	 * Converts IPB 4's regex into a regular expression without delimiters so we can check it within our own.
	 *
	 * @param string $inputFormat
	 *
	 * @return string
	 */
	protected function _convertUserFieldMatchTypeToRegex($inputFormat)
	{
		$delimiter = $inputFormat[0];
		$lastDelPos = strrpos($inputFormat, $delimiter);
		if ($lastDelPos !== false)
		{
			$inputFormat = substr($inputFormat, 1, $lastDelPos - 1);
		}
		return $inputFormat;
	}

	public function stepUsers($start, array $options)
	{
		$options = array_merge(array(
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(member_id)
				FROM ' . $prefix . 'core_members
			');
		}

		return parent::stepUsers($start, $options);
	}

	protected function _importUser(array $user, array $options)
	{
		if ($this->_groupMap === null)
		{
			$this->_groupMap = $this->_importModel->getImportContentMap('userGroup');
		}

		if ($this->_userFieldMap === null)
		{
			$this->_userFieldMap = $this->_importModel->getImportContentMap('userField');
		}

		// handle degenerate user group info
		if (empty($user['member_group_id']) || !isset($this->_groupMap[$user['member_group_id']]))
		{
			$groupConfig = $this->_session->getExtraData('groups');

			$user['member_group_id'] = $groupConfig['member_group'];
		}

		// unserialize the 'cache' blob
		$user['members_cache'] = unserialize($user['members_cache']);

		$import = array(
			'username' => $this->_convertToUtf8($user['name'], true),
			'email' => $this->_convertToUtf8($user['email']),
			'user_group_id' => $this->_mapLookUp($this->_groupMap, $user['member_group_id'], XenForo_Model_User::$defaultRegisteredGroupId),
			'secondary_group_ids' => $this->_mapLookUpList($this->_groupMap, $this->_ipbExplode($user['mgroup_others'])),
			'about' => '',
			'last_activity' => $user['last_activity'],
			'register_date' => $user['joined'],
			'ip' => $user['ip_address'],
			'message_count' => $user['posts'],

			'timezone' => $user['timezone'] == 'UTC' ? 'Europe/London' : $user['timezone'],

			'signature' => $this->_parseIPBoardBbCode($user['signature']),
			'content_show_signature' => $user['members_bitoptions'] & 65536 ? true : false,

			'receive_admin_email' => $user['allow_admin_mails'],
			'allow_send_personal_conversation' => ($user['members_disable_pm'] ? 'none' : 'everyone'),
			'allow_post_profile' => ($user['pp_setting_count_comments'] ? 'everyone' : 'none'),

			'dob_day'   => $user['bday_day'],
			'dob_month' => $user['bday_month'],
			'dob_year'  => $user['bday_year'],

			'show_dob_year' => 1,
			'show_dob_date' => 1,

			'is_banned' => ($user['temp_ban'] <> 0), // -1 is perm banned, 0 is not banned, >= 1 is temp banned until timestamp
		);

		if (utf8_strlen($user['members_pass_salt']) === 22) // IPS4 auth
		{
			$import['authentication'] = array(
				'scheme_class' => 'XenForo_Authentication_IPBoard40x',
			);
		}
		else // IPS3 auth
		{
			$import['authentication'] = array(
				'scheme_class' => 'XenForo_Authentication_IPBoard'
			);
		}
		$import['authentication']['data'] = array(
			'hash' => $user['members_pass_hash'],
			'salt' => $user['members_pass_salt']
		);

		// try to give users without an avatar that have actually posted a gravatar
		if ($user['pp_photo_type'] == 'gravatar')
		{
			$import['gravatar'] = $this->_convertToUtf8($user['pp_gravatar']);
		}

		// custom title
		if ($user['member_title'])
		{
			$import['custom_title'] = strip_tags(
				preg_replace('#<br\s*/?>#i', ', ',
					htmlspecialchars_decode(
						$this->_convertToUtf8($user['member_title'])
					)
				)
			);
		}

		// custom user fields
		$userFieldDefinitions = $this->_importModel->getUserFieldDefinitions();

		foreach ($this->_userFieldMap AS $oldFieldId => $newFieldId)
		{
			if (isset($user["field_$oldFieldId"]) && $user["field_$oldFieldId"] !== '')
			{
				$userFieldValue = $this->_convertToUtf8($user["field_$oldFieldId"]);

				switch ($newFieldId)
				{
					// map these custom fields to our hard-coded fields
					case 'gender':
						$import['gender'] = $this->_handleProfileFieldGender($userFieldValue);
						break;

					case 'website':
						$import['homepage'] = $userFieldValue;
						break;

					case 'location':
						$import['location'] = $userFieldValue;
						break;

					case 'about_me':
					case 'interests':
						$import['about'] .= "\n\n" . $this->_parseIPBoardBbCode($userFieldValue);
						break;

					// handle IPB custom fields that we also treat as custom
					default:
					{
						if (!isset($userFieldDefinitions[$newFieldId]))
						{
							continue;
						}

						if ($userFieldDefinitions[$newFieldId]['field_type'] == 'checkbox')
						{
							$keys = preg_split('/\|/', $userFieldValue, -1, PREG_SPLIT_NO_EMPTY);

							$userFieldValue = array_combine($keys, $keys);
						}

						$import[XenForo_Model_Import::USER_FIELD_KEY][$newFieldId] = $userFieldValue;
					}
				}
			}
		}

		if ($user['members_bitoptions'] & 1073741824)
		{
			$import['user_state'] = 'email_confirm';
		}
		else
		{
			$import['user_state'] = 'valid';
		}

		$import['default_watch_state'] = '';

		$autoTrack = json_decode($user['auto_track'], true);
		if ($autoTrack)
		{
			if ($autoTrack['comments'] || $autoTrack['content'])
			{
				if ($autoTrack['method'] == 'immediate')
				{
					$import['default_watch_state'] = 'watch_no_email';
				}
				else
				{
					$import['default_watch_state'] = 'watch_email';
				}
			}
		}

		// is admin
		if ($import['is_admin'] = $this->_isAdmin($user, $adminRestrictions))
		{
			if (empty($adminRestrictions))
			{
				$import['admin_permissions'] = $this->_importModel->getAdminPermissionIds();
			}
			else
			{
				$importAdminPerms = array();

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'applications'))
				{
					$importAdminPerms[] = 'option';
					$importAdminPerms[] = 'import';
					$importAdminPerms[] = 'upgradeXenForo';
					$importAdminPerms[] = 'addOn';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'editor', 'toolbar_manage')
					||	$this->_hasAdminPermission($adminRestrictions, 'core', 'posts', 'emoticons_manage')
				)
				{
					$importAdminPerms[] = 'bbCodeSmilie';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'settings', 'advanced_manage_tasks'))
				{
					$importAdminPerms[] = 'cron';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'customization'))
				{
					$importAdminPerms[] = 'style';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'languages'))
				{
					$importAdminPerms[] = 'language';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'forums', 'forums'))
				{
					$importAdminPerms[] = 'node';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'members'))
				{
					$importAdminPerms[] = 'user';
					$importAdminPerms[] = 'trophy';
					$importAdminPerms[] = 'userUpgrade';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'members', 'member_ban'))
				{
					$importAdminPerms[] = 'ban';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'members', 'groups_manage'))
				{
					$importAdminPerms[] = 'userGroup';
				}

				if ($this->_hasAdminPermission($adminRestrictions, 'core', 'membersettings', 'profilefields_manage'))
				{
					$importAdminPerms[] = 'identityService';
				}

				$import['admin_permissions'] = $importAdminPerms;
			}
		}

		$importedUserId = $this->_importModel->importUser($user['member_id'], $import, $failedKey);

		if ($importedUserId)
		{
			// import bans
			if ($import['is_banned'])
			{
				if ($user['temp_ban'] >= 1)
				{
					// temp ban until timestamp
					$endDate = $user['temp_ban'];
				}
				else
				{
					// permanent ban
					$endDate = 0;
				}

				$this->_importModel->importBan(array(
					'user_id' => $importedUserId,
					'ban_user_id' => 0,
					'ban_date' => 0,
					'end_date' => $endDate,
				));
			}

			// import super moderators
			if ($this->_isSuperModerator($user))
			{
				$this->_session->setExtraData('superMods', $user['member_id'], $importedUserId);
			}

			if (!empty($user['members_cache']['friends']) && is_array($user['members_cache']['friends']))
			{
				$friendIds = array_keys($user['members_cache']['friends']);
				$friendIds = $this->_importModel->getImportContentMap('user', $friendIds);
				$this->_importModel->importFollowing($importedUserId, $friendIds);
			}
		}
		else if ($failedKey)
		{
			$this->_session->setExtraData('userFailed', $user['member_id'], $failedKey);
		}

		return $importedUserId;
	}

	protected function _handleProfileFieldGender($gender)
	{
		switch ($gender)
		{
			case 'm':
			case 'Male':
				return 'male';
			case 'f':
			case 'Female':
				return 'female';
			default:
				return '';
		}
	}

	/**
	 * Check if the specified user is an administrator, by looking at all of their
	 * user group memberships and checking if any of them have cp access privs.
	 *
	 * @param array $user
	 * @param array $adminRestrictions
	 *
	 * @return boolean
	 */
	protected function _isAdmin(array $user, array &$adminRestrictions = null)
	{
		$groups = $this->_session->getExtraData('groups');

		if ($user['member_group_id'] == $groups['admin_group'])
		{
			if (!empty($user['admin_restrictions']))
			{
				$adminRestrictions = json_decode($user['admin_restrictions'], true);
			}

			return 1;
		}
		else
		{
			foreach ($this->_getGroupsForUser($user) AS $group)
			{
				if ($group['g_access_cp'])
				{
					if (!empty($group['admin_restrictions']))
					{
						$adminRestrictions = json_decode($group['admin_restrictions'], true);
					}

					return 1;
				}
			}
		}

		return 0;
	}

	/**
	 * Checks that the $permissions array given has the admin permission specified
	 *
	 * @param array $adminRestrictions
	 * @param string $appName
	 * @param string $moduleName
	 * @param string $permName
	 *
	 * @return boolean
	 */
	protected function _hasAdminPermission(array $adminRestrictions, $appName, $moduleName = null, $permName = null)
	{
		if (!in_array($appName, $adminRestrictions['applications']))
		{
			return false;
		}

		if (isset($moduleName))
		{
			if (!in_array($moduleName, $adminRestrictions['modules']))
			{
				return false;
			}

			if (!array_key_exists($moduleName, $adminRestrictions['items'][$appName]))
			{
				return false;
			}

			if (isset($permName) && !in_array($permName, $adminRestrictions['items'][$appName][$moduleName]))
			{
				return false;
			}

			return true;
		}

		return true;
	}

	public function stepIgnoredUsers($start, array $options)
	{
		$options = array_merge(array(
			'limit' => 100,
			'max' => false,
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		/* @var $model XenForo_Model_Import */
		$model = $this->_importModel;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(ignore_id)
				FROM ' . $prefix . 'core_ignored_users
			');
		}

		$ignored = $sDb->fetchAll($sDb->limit('
			SELECT *
			FROM ' . $prefix . 'core_ignored_users
			WHERE ignore_id > ?
		', $options['limit']), $start);
		if (!$ignored)
		{
			return true;
		}

		$ignoreOwnerUserIds = $model->getUserIdsMapFromArray($ignored, 'ignore_owner_id');
		$ignoredUserIds = $model->getUserIdsMapFromArray($ignored, 'ignore_ignore_id');

		XenForo_Db::beginTransaction();

		$next = 0;
		$total = 0;
		foreach ($ignored AS $ignore)
		{
			$next = $ignore['ignore_id'];

			if (!$ignore['ignore_messages'] && !$ignore['ignore_topics'])
			{
				continue;
			}

			$ignoreOwnerUserId = $this->_mapLookUp($ignoreOwnerUserIds, $ignore['ignore_owner_id']);
			$ignoredUserId = $this->_mapLookUp($ignoredUserIds, $ignore['ignore_ignore_id']);

			$this->_importModel->importIgnored($ignoreOwnerUserId, array($ignoredUserId));

			$total++;
		}

		XenForo_Db::commit();

		$this->_session->incrementStepImportTotal($total);

		return array($next, $options, $this->_getProgressOutput($next, $options['max']));
	}

	protected function _getSelectUserSql($where)
	{
		return '
			SELECT pfields_content.*, apr.row_perm_cache AS admin_restrictions, members.*, members.member_posts AS posts
			FROM ' . $this->_prefix . 'core_members AS members
			LEFT JOIN  ' . $this->_prefix . 'core_pfields_content AS pfields_content ON
				(pfields_content.member_id = members.member_id)
			LEFT JOIN ' . $this->_prefix .  'core_admin_permission_rows AS apr ON
				(apr.row_id = members.member_id AND apr.row_id_type = \'member\')
			WHERE '  . $where . '
			ORDER BY members.member_id
		';
	}

	public function configStepAvatars(array $options)
	{
		return false;
	}

	public function stepAvatars($start, array $options)
	{
		$options = array_merge(array(
			'path' => $this->_config['ipboard_path'] . '/uploads',
			'limit' => 50,
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		/* @var $model XenForo_Model_Import */
		$model = $this->_importModel;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(member_id)
				FROM ' . $prefix . 'core_members
				WHERE pp_photo_type = \'custom\'
			');
		}

		$avatars = $sDb->fetchAll($sDb->limit(
			'
				SELECT members.*
				FROM ' . $prefix . 'core_members AS members
				WHERE members.pp_photo_type = \'custom\'
					AND members.member_id > ' . $sDb->quote($start) . '
				ORDER BY members.member_id
			', $options['limit']
		));
		if (!$avatars)
		{
			return true;
		}

		$userIdMap = $model->getUserIdsMapFromArray($avatars, 'member_id');

		$next = 0;
		$total = 0;

		foreach ($avatars AS $avatar)
		{
			$next = $avatar['member_id'];

			$newUserId = $this->_mapLookUp($userIdMap, $avatar['member_id']);
			if (!$newUserId)
			{
				continue;
			}

			$photoPath = "$options[path]/$avatar[pp_main_photo]";
			if (!$avatar['pp_main_photo'] || !file_exists($photoPath) || !is_file($photoPath))
			{
				continue;
			}

			$avatarFile = tempnam(XenForo_Helper_File::getTempDir(), 'xf');
			copy($photoPath, $avatarFile);

			if ($this->_importModel->importAvatar($avatar['member_id'], $newUserId, $avatarFile))
			{
				$total++;
			}

			@unlink($avatarFile);
		}

		$this->_session->incrementStepImportTotal($total);

		return array($next, $options, $this->_getProgressOutput($next, $options['max']));
	}

	public function stepPrivateMessages($start, array $options)
	{
		$options = array_merge(array(
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(mt_id)
				FROM ' . $prefix . 'core_message_topics
				WHERE mt_is_draft = 0
					AND mt_is_deleted = 0
					AND mt_is_system = 0
			');
		}

		return parent::stepPrivateMessages($start, $options);
	}

	protected function _getPrivateMessages($start, array $options)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT mtopics.*,
					members.name AS mt_starter_name
				FROM ' . $prefix . 'core_message_topics AS mtopics
				INNER JOIN  ' . $prefix . 'core_members AS members ON
					(mtopics.mt_starter_id = members.member_id)
				WHERE mtopics.mt_id > ' . $sDb->quote($start) . '
					AND mt_is_draft = 0
					AND mt_is_deleted = 0
					AND mt_is_system = 0
				ORDER BY mtopics.mt_id
			', $options['limit']
		));
	}

	protected function _getTopicUserMap(array $topic)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll('
			SELECT topicUserMap.*,
				members.name AS map_user_name
			FROM ' . $prefix . 'core_message_topic_user_map AS topicUserMap
			INNER JOIN ' . $prefix . 'core_members AS members ON
				(topicUserMap.map_user_id = members.member_id)
			WHERE topicUserMap.map_topic_id = ' . $sDb->quote($topic['mt_id'])
		);
	}

	protected function _getMessagePosts(array $topic)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll('
			SELECT messagePosts.*,
				members.name AS msg_author_name
			FROM ' . $prefix . 'core_message_posts AS messagePosts
			INNER JOIN ' . $prefix . 'core_members AS members ON
				(messagePosts.msg_author_id = members.member_id)
			WHERE messagePosts.msg_topic_id = ' . $sDb->quote($topic['mt_id']) . '
			ORDER BY messagePosts.msg_date
		');
	}

	/**
	 * Forums must have been imported already for this to function.
	 */
	protected function _guessForumPermissions()
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		$groupIds = $this->_session->getExtraData('groups');

		$forumPermissions = array();

		$ipbForumPerms = $sDb->fetchPairs('
			SELECT forums.id, perms.perm_view
			FROM ' . $prefix . 'forums_forums AS forums
			LEFT JOIN ' . $prefix . 'core_permission_index AS perms ON
				(perms.perm_type_id = forums.id AND perms.perm_type = \'forum\')
		');
		foreach ($ipbForumPerms AS $forumId => $viewPermSets)
		{
			if ($viewPermSets == '*')
			{
				$state = 'public';
			}
			else
			{
				$viewPermSets = $this->_ipbExplode($viewPermSets);

				if (!in_array($groupIds['guest_group'], $viewPermSets))
				{
					// forum is not viewable by guests
					$state = 'memberOnly';
					if (!in_array($groupIds['member_group'], $viewPermSets))
					{
						// forum is not viewable by registered members
						$state = 'staffOnly';
					}
				}
				else
				{
					$state = 'public';
				}
			}

			$forumPermissions[$this->_importModel->mapNodeId($forumId)] = $state;
		}

		return $forumPermissions;
	}

	public function stepStatusUpdates($start, array $options)
	{
		$options = array_merge(array(
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(status_id)
				FROM ' . $prefix . 'core_member_status_updates
			');
		}

		return parent::stepStatusUpdates($start, $options);
	}

	protected function _getStatusUpdates($start, $limit)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT msus.*, members.name AS status_author_name
				FROM ' . $prefix . 'core_member_status_updates AS msus
				INNER JOIN ' . $prefix . 'core_members AS members ON
					(msus.status_author_id = members.member_id)
				WHERE msus.status_id > ' . $sDb->quote($start) . '
				ORDER BY msus.status_id
			', $limit
		));
	}

	protected function _getStatusReplies(array $statusUpdate)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll('
			SELECT replies.*, members.name
			FROM ' . $prefix . 'core_member_status_replies AS replies
			INNER JOIN  ' . $prefix . 'core_members AS members ON
				(replies.reply_member_id = members.member_id)
			WHERE replies.reply_status_id = ' . $sDb->quote($statusUpdate['status_id']) . '
			ORDER BY replies.reply_date
		');
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::_importStatusUpdateExtra($statusUpdate, $profilePostId, $profilePost)
	 */
	protected function _importStatusUpdateExtra(array $statusUpdate, $profilePostId, array $profilePost)
	{
		$this->_importStatusUpdateLikes($statusUpdate, $profilePostId, $profilePost);
		return array();
	}

	protected function _importStatusUpdateLikes(array $statusUpdate, $profilePostId, array $profilePost)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;
		$model = $this->_importModel;

		$likes = $sDb->fetchAll('
			SELECT member_id, rep_date
			FROM ' . $prefix . 'core_reputation_index
			WHERE type_id = ' . $sDb->quote($statusUpdate['status_id']) . '
				AND app = \'core\'
				AND type = \'status_id\'
		');

		if ($likes)
		{
			$userIdMap = $model->getUserIdsMapFromArray($likes, 'member_id');

			foreach ($likes AS $like)
			{
				$model->importLike(
					'profile_post', $profilePostId,
					$profilePost['user_id'],
					$this->_mapLookUp($userIdMap, $like['member_id']),
					$like['rep_date']
				);
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see XenForo_Importer_IPBoard::_importStatusReplyExtra($statusReply, $commentId, $comment)
	 */
	protected function _importStatusReplyExtra(array $statusReply, $commentId, array $comment)
	{
		$this->_importStatusReplyLikes($statusReply, $commentId, $comment);

		parent::_importStatusReplyExtra($statusReply, $commentId, $comment);
	}

	protected function _importStatusReplyLikes(array $statusReply, $commentId, array $comment)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;
		$model = $this->_importModel;

		$likes = $sDb->fetchAll('
			SELECT member_id, rep_date
			FROM ' . $prefix . 'core_reputation_index
			WHERE type_id = ' . $sDb->quote($statusReply['reply_id']) . '
				AND app = \'core\'
				AND type = \'status_reply_id\'
		');

		if ($likes)
		{
			$userIdMap = $model->getUserIdsMapFromArray($likes, 'member_id');

			foreach ($likes AS $like)
			{
				$model->importLike(
					'profile_post_comment', $commentId,
					$comment['user_id'],
					$this->_mapLookUp($userIdMap, $like['member_id']),
					$like['rep_date']
				);
			}
		}
	}

	protected function _prepareStatusMessageText($text)
	{
		return $this->_parseIPBoardBbCode($text);
	}

	protected function _getForums()
	{
		$prefix = $this->_prefix;

		return $this->_sourceDb->fetchAll('
			SELECT forum.*, lang_title.word_custom AS name, lang_desc.word_custom AS description
			FROM ' . $prefix . 'forums_forums AS forum
			LEFT JOIN ' . $prefix . 'core_sys_lang_words AS lang_title ON
				(lang_title.lang_id = 1 AND lang_title.word_key = CONCAT(\'forums_forum_\', forum.id))
			LEFT JOIN ' . $prefix . 'core_sys_lang_words AS lang_desc ON
				(lang_desc.lang_id = 1 AND lang_desc.word_key = CONCAT(\'forums_forum_\', forum.id, \'_desc\'))
		');
	}

	public function stepModerators($start, array $options)
	{
		/* @var $model XenForo_Model_Import */
		$model = $this->_importModel;

		$moderators = array();

		$forumMods = $this->_getModerators();
		foreach ($forumMods AS $forumMod)
		{
			$moderators[$forumMod['member_id']] = $forumMod;
		}

		// get the full list of super moderator permissions
		$superModPerms = XenForo_Model::create('XenForo_Model_Moderator')->getFullPermissionSet();

		if ($superMods = $this->_session->getExtraData('superMods'))
		{
			foreach ($superMods AS $oldUserId => $newUserId)
			{
				$moderators[$oldUserId]['superMod'] = $newUserId;
			}
		}

		if (!$moderators)
		{
			return true;
		}

		$nodeMap = $model->getImportContentMap('node');
		$userIdMap = $model->getImportContentMap('user', array_keys($moderators));

		$total = 0;

		XenForo_Db::beginTransaction();

		foreach ($moderators AS $userId => $moderator)
		{
			$newUserId = $this->_mapLookUp($userIdMap, $userId);

			if (!$newUserId)
			{
				continue;
			}

			if (!empty($moderator['superMod']))
			{
				$globalModPermissions = $superModPerms;
				$superMod = true;
			}
			else
			{
				$globalModPermissions = array();
				$superMod = false;
			}

			if (isset($moderator['perms']))
			{
				if ($moderator['perms'] == '*') // has all permissions for all forums, treat as a super moderator
				{
					$globalModPermissions = $superModPerms;
					$superMod = true;
				}
				else
				{
					$forumIds = array();
					$perms = json_decode($moderator['perms'], true);
					if (isset($perms['forums']))
					{
						$forumIds = $perms['forums'];
					}

					if ($forumIds && is_array($forumIds))
					{
						$forumPerms = $this->_calculateModeratorPermissions($moderator);

						foreach ($forumIds AS $forumId)
						{
							$newNodeId = $this->_mapLookUp($nodeMap, $forumId);
							if (!$newNodeId)
							{
								continue;
							}

							$mod = array(
								'content_id' => $newNodeId,
								'user_id' => $newUserId,
								'moderator_permissions' => array('forum' => $forumPerms['forum'])
							);

							$model->importNodeModerator($forumId, $newUserId, $mod);

							$total++;
						}
					}
					else
					{
						if (!$superMod)
						{
							continue;
						}
					}
				}
			}

			$mod = array(
				'user_id' => $newUserId,
				'is_super_moderator' => $superMod,
				'moderator_permissions' => $globalModPermissions
			);
			$model->importGlobalModerator($userId, $mod);
		}

		$this->_session->incrementStepImportTotal($total);

		XenForo_Db::commit();

		return true;
	}

	protected function _getModerators()
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll('
			SELECT moderators.*, members.member_id
			FROM ' . $prefix . 'core_moderators AS moderators
			INNER JOIN ' . $prefix . 'core_members AS members ON
				(moderators.id = members.member_id AND moderators.type = \'m\')
		');
	}

	protected function _calculateModeratorPermissions(array $mod)
	{
		$modPerms = json_decode($mod['perms'], true);

		$general = array();

		if (!empty($modPerms['can_use_ip_tools']))
		{
			$general['viewIps'] = true;
		}

		if (!empty($modPerms['can_flag_as_spammer']))
		{
			$general['cleanSpam'] = true;
		}

		$forum = array
		(
			'viewModerated' => true,
			'approveUnapprove' => true
		);

		if (!empty($modPerms['can_edit_content']) || !empty($modPerms['can_edit_post']))
		{
			$forum['editAnyPost'] = true;
		}

		if (!empty($modPerms['can_edit_content']) || !empty($modPerms['can_edit_topic']))
		{
			$forum['manageAnyThread'] = true;
		}

		if (!empty($modPerms['can_pin_content'])
			|| !empty($modPerms['can_unpin_content'])
			|| !empty($modPerms['can_pin_topic'])
			|| !empty($modPerms['can_unpin_topic']))
		{
			$forum['stickUnstickThread'] = true;
		}

		if (!empty($modPerms['can_lock_content'])
			|| !empty($modPerms['can_unlock_content'])
			|| !empty($modPerms['can_lock_topic'])
			|| !empty($modPerms['can_unlock_topic'])
		)
		{
			$forum['lockUnlockThread'] = true;
		}

		if (!empty($modPerms['can_hide_content']) || !empty($modPerms['can_hide_post']))
		{
			$forum['deleteAnyPost'] = true;
		}

		if (!empty($modPerms['can_delete_content']) || !empty($modPerms['can_delete_post']))
		{
			$forum['hardDeleteAnyPost'] = true;
		}

		if (!empty($modPerms['can_hide_content']) || !empty($modPerms['can_hide_topic']))
		{
			$forum['deleteAnyThread'] = true;
		}

		if (!empty($modPerms['can_delete_content']) || !empty($modPerms['can_delete_topic']))
		{
			$forum['hardDeleteAnyThread'] = true;
		}

		if (!empty($modPerms['can_unhide_content'])
			|| !empty($modPerms['can_unhide_topic'])
			|| !empty($modPerms['can_unhide_post'])
		)
		{
			$forum['undelete'] = true;
		}

		if (!empty($modPerms['can_view_hidden_content'])
			|| !empty($mod['can_view_hidden_topic'])
			|| !empty($modPerms['can_view_hidden_post'])
		)
		{
			$forum['viewDeleted'] = true;
		}

		return array(
			'general' => $general,
			'forum' => $forum
		);
	}

	public function stepThreads($start, array $options)
	{
		$options = array_merge(array(
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(tid)
				FROM ' . $prefix . 'forums_topics
			');
		}

		return parent::stepThreads($start, $options);
	}

	protected function _getThreads($start, array $options)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT
					topics.*,
					IF (members.name IS NULL, topics.starter_name, members.name) AS starter_name,
					IF (lastposters.name IS NULL, topics.last_poster_name, lastposters.name) AS last_poster_name
				FROM ' . $prefix . 'forums_topics AS topics FORCE INDEX (PRIMARY)
				LEFT JOIN ' . $prefix . 'core_members AS members ON
					(topics.starter_id = members.member_id)
				LEFT JOIN ' . $prefix . 'core_members AS lastposters ON
					(topics.last_poster_id = lastposters.member_id)
				INNER JOIN ' . $prefix . 'forums_forums AS forums ON
					(topics.forum_id = forums.id AND forums.redirect_on = 0 AND forums.sub_can_post = 1)
				WHERE topics.tid >= ' . $sDb->quote($start) . '
					AND topics.state <> \'link\'
				ORDER BY topics.tid
			', $options['limit']
		));
	}

	protected function _getPosts(array $thread, $postDateStart, $maxPosts)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT posts.*, posts.edit_time AS edit_date,
					IF (members.name IS NULL, posts.author_name, members.name) AS author_name
				FROM ' . $prefix . 'forums_posts AS posts
				LEFT JOIN ' . $prefix . 'core_members AS members ON
					(posts.author_id = members.member_id)
				WHERE posts.topic_id = ' . $sDb->quote($thread['tid']) . '
					AND posts.post_date > ' . $sDb->quote($postDateStart) . '
				ORDER BY posts.post_date
			', $maxPosts
		));
	}

	/**
	 * Imports thread watch records for the given thread
	 *
	 * @param integer $threadId Imported XenForo thread ID
	 * @param array $sourceThread IPB source thread data
	 */
	protected function _importThreadWatch($threadId, array $sourceThread)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;
		$model = $this->_importModel;

		$subs = $sDb->fetchPairs('
			SELECT follow_member_id, follow_notify_freq
			FROM ' . $prefix . 'core_follow
			WHERE follow_rel_id = ' . $sDb->quote($sourceThread['tid']) . '
				AND follow_app = \'forums\'
				AND follow_area LIKE(\'topic%\')
				AND follow_notify_do = 1
		');
		if ($subs)
		{
			$userIdMap = $model->getImportContentMap('user', array_keys($subs));
			foreach ($subs AS $userId => $emailUpdate)
			{
				$newUserId = $this->_mapLookUp($userIdMap, $userId);
				if (!$newUserId)
				{
					continue;
				}

				$model->importThreadWatch($newUserId, $threadId, ($emailUpdate == 'none' || empty($emailUpdate) ? 0 : 1));
			}
		}
	}

	protected function _getMessageStateAndPosition(array $post, array $import, &$position)
	{
		switch ($post['queued'])
		{
			case -1: $import['message_state'] = 'deleted'; $import['position'] = $position; break; // post hidden
			case 1: $import['message_state'] = 'moderated'; $import['position'] = $position; break; // post moderated
			case 2: $import['message_state'] = 'deleted'; $import['position'] = $position; break; // thread hidden
			default: $import['message_state'] = 'visible'; $import['position'] = ++$position; break; // post and thread visible
		}

		return $import;
	}

	public function stepPolls($start, array $options)
	{
		$options = array_merge(array(
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(pid)
				FROM ' . $prefix . 'core_polls
			');
		}

		return parent::stepPolls($start, $options);
	}

	protected function _getPolls($start, array $options)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		$tidColumn = $sDb->fetchRow('
			SHOW COLUMNS
			FROM ' . $prefix . 'core_polls
			WHERE Field = ?
		', 'tid');

		if ($tidColumn)
		{
			$pollCol = 'tid';
			$topicCol = 'tid';
		}
		else
		{
			$pollCol = 'pid';
			$topicCol = 'poll_state';
		}

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT polls.*, topics.tid
				FROM ' . $prefix . 'core_polls AS polls
				INNER JOIN ' . $prefix . 'forums_topics AS topics ON
					(polls.' . $pollCol . ' = topics.' . $topicCol . ' AND topics.state <> \'link\')
				WHERE polls.pid > ' . $sDb->quote($start) . '
				ORDER BY polls.pid
			', $options['limit']
		));
	}

	protected function _getVoters(array $poll)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll('
			SELECT member_id, vote_date, member_choices
			FROM ' . $prefix . 'core_voters
			WHERE tid = ' . $sDb->quote($poll['tid']) . '
			AND member_choices IS NOT NULL
		');
	}

	protected function _prepareQuestions($choices)
	{
		return json_decode($choices, true);
	}

	protected function _prepareAnswers($choices)
	{
		return json_decode($choices, true);
	}

	public function stepAttachments($start, array $options)
	{
		$options = array_merge(array(
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(attach_id)
				FROM ' . $prefix . 'core_attachments
			');
		}

		return parent::stepAttachments($start, $options);
	}

	protected function _getAttachments($start, array $options)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT
					attach.attach_id, attach.attach_date, attach.attach_hits,
					attach.attach_file, attach.attach_location,
					attach.attach_member_id AS member_id,
					map.id1 AS thread_id,
					map.id2 AS post_id
				FROM ' . $prefix . 'core_attachments AS attach
				INNER JOIN ' . $prefix . 'core_attachments_map AS map ON
					(attach.attach_id = map.attachment_id)
				WHERE attach.attach_id > ' . $sDb->quote($start) . '
					AND map.location_key = \'forums_Forums\'
				ORDER BY attach.attach_id
			', $options['limit']
		));
	}

	public static function processAttachmentTags($oldAttachmentId, $newAttachmentId, $messageText)
	{
		if (stripos($messageText, '[attach') !== false)
		{
			$messageText = preg_replace("/\[attach]{$oldAttachmentId}\.IPB\[\/attach]/siU", "[ATTACH]{$newAttachmentId}[/ATTACH]", $messageText);
		}

		return $messageText;
	}

	public function stepReputation($start, array $options)
	{
		$options = array_merge(array(
			'max' => false
		), $options);

		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		if ($options['max'] === false)
		{
			$options['max'] = $sDb->fetchOne('
				SELECT MAX(id)
				FROM ' . $prefix . 'core_reputation_index
				WHERE rep_rating > 0
					AND app = \'forums\'
					AND type = \'pid\'
			');
		}

		return parent::stepReputation($start, $options);
	}

	protected function _getReputations($start, array $options)
	{
		$sDb = $this->_sourceDb;
		$prefix = $this->_prefix;

		return $sDb->fetchAll($sDb->limit(
			'
				SELECT rep.*,
					posts.author_id
				FROM ' . $prefix . 'core_reputation_index AS rep
				INNER JOIN ' . $prefix . 'forums_posts AS posts ON
					(posts.pid = rep.type_id AND rep.app = \'forums\' AND rep.type = \'pid\')
				WHERE id > ' . $sDb->quote($start) . '
					AND rep.rep_rating > 0
				ORDER BY rep.id
			', $options['limit']
		));
	}

	/**
	 * Fetches an array representing all the source user groups
	 *
	 * @return array [userGroupId => userGroup, userGroupId => userGroup...]
	 */
	protected function _getGroupCache()
	{
		if ($this->_groupCache === null)
		{
			$this->_groupCache = array();

			$groups = $this->_sourceDb->fetchAll('
				SELECT groups.*, apr.row_perm_cache AS admin_restrictions
				FROM ' . $this->_prefix . 'core_groups AS groups
				LEFT JOIN ' . $this->_prefix . 'core_admin_permission_rows AS apr ON
					(apr.row_id = groups.g_id AND apr.row_id_type = \'group\')
			');

			foreach ($groups AS $group)
			{
				$this->_groupCache[$group['g_id']] = $group;
			}
		}

		return $this->_groupCache;
	}

	protected function _parseIPBoardBbCode($message, $autoLink = true)
	{
		$message = preg_replace('/<br( \/)?>(\r?\n)?/si', "\n", $message);
		$message = str_replace('&nbsp;' , ' ', $message);

		// handle the IPB media format
		if (stripos($message, 'ipsEmbeddedVideo') !== false)
		{
			$message = $this->_parseIPBoardMediaCode($message);
		}

		if (stripos($message, 'ipsQuote') !== false)
		{
			$message = $this->_parseIPBoardQuoteCode($message);
		}

		$search = $this->_getIPBoardBBCodeReplacements();

		$message = preg_replace(array_keys($search), $search, $message);
		$message = strip_tags($message);

		return $this->_convertToUtf8($message, true);
	}

	protected function _getIPBoardBBCodeReplacements()
	{
		return array(

			// this is likely the closest to correct this can be - in IPB this is replaced with the base_url as stored in settings
			// but this can be blank, so it would still leave IMG and URLs with relative URLs which will not work in XF.
			'#<___base_url___>#siU' => XenForo_Application::getOptions()->boardUrl,

			// common attachment links - attachment links containing thumbnailed images
			'#<a [^>]*href=(\'|")([^"\']+)\\1[^>]*class="ipsAttachLink\s*ipsAttachLink_image".*data-fileid="(\d+)".*</a>#siU' => '[ATTACH]\\3.IPB[/ATTACH]',
			'#<a [^>]*class="ipsAttachLink\s*ipsAttachLink_image"[^>]*href=(\'|")([^"\']+)\\1.*data-fileid="(\d+)".*</a>#siU' => '[ATTACH]\\3.IPB[/ATTACH]',

			// common attachment links - attachment links pointing to attached files
			'#<a [^>]*href=".*attachment\.php\?id=(\d+)"[^>]*class="ipsAttachLink"[^>]*>.*</a>#siU' => '[ATTACH]\\1.IPB[/ATTACH]',
			'#<a [^>]*class="ipsAttachLink"[^>]*href=".*attachment\.php\?id=(\d+)"[^>]*>.*</a>#siU' => '[ATTACH]\\1.IPB[/ATTACH]',

			// less common attachment links - attached image no link
			'#<img [^>]*class="ipsImage\s*ipsImage_thumbnailed"[^>]*data-fileid="(\d+)"[^>]*src="[^"]*"[^>]*>#siU' => '[ATTACH]\\1.IPB[/ATTACH]',

			// code block - handle it specifically
			'#<pre [^>]*class="ipsCode"[^>]*>(.*)</pre>(\r?\n)??#siU' => '[CODE]\\1[/CODE]',

			// emoticons
			'#<img [^>]*src="<fileStore\.core_Emoticons>[^>]*"[^>]*alt="([^"]+)" srcset=".*"[^>]*>#siU' => ' \\1 ',
			'#<img [^>]*alt="([^"]+)"[^>]*src="<fileStore\.core_Emoticons>[^>]*" srcset=".*"[^>]*>#siU' => ' \\1 ',
			'#<img [^>]*src="<fileStore\.core_Emoticons>[^>]*"[^>]*alt="([^"]+)"[^>]*>#siU' => ' \\1 ',
			'#<img [^>]*alt="([^"]+)"[^>]*src="<fileStore\.core_Emoticons>[^>]*"[^>]*>#siU' => ' \\1 ',

			// IPB 4.0 spoiler
			'#<blockquote [^>]*class="ipsStyle_spoiler"[^>]*>(.*)</blockquote>(\r?\n)??#siU' => '[SPOILER]\\1[/SPOILER]',

			// IPB 4.1 spoiler
			'#<div [^>]*class="ipsSpoiler"[^>]*>.*<div [^>]*class="ipsSpoiler_contents"[^>]*>(.*)</div>\s*</div>(\r?\n)??#siU' => '[SPOILER]\\1[/SPOILER]',

			'#<span [^>]*style="color:\s*([^";\\]]+?)[^"]*"[^>]*>(.*)</span>#siU' => '[COLOR=\\1]\\2[/COLOR]',
			'#<span [^>]*style="font-family:\s*([^";\\],]+?)[^"]*"[^>]*>(.*)</span>#siU' => '[FONT=\\1]\\2[/FONT]',
			'#<span [^>]*style="font-size:\s*([^";\\]]+?)[^"]*"[^>]*>(.*)</span>#siU' => '[SIZE=\\1]\\2[/SIZE]',
			'#<span[^>]*>(.*)</span>#siU' => '\\1',
			'#<(strong|b)>(.*)</\\1>#siU' => '[B]\\2[/B]',
			'#<(em|i)>(.*)</\\1>#siU' => '[I]\\2[/I]',
			'#<(u)>(.*)</\\1>#siU' => '[U]\\2[/U]',
			'#<(strike|s)>(.*)</\\1>#siU' => '[S]\\2[/S]',
			'#<a [^>]*href=(\'|")([^"\']+)\\1[^>]*>(.*)</a>#siU' => '[URL="\\2"]\\3[/URL]',
			'#<img [^>]*src="([^"]+)"[^>]*>#' => '[IMG]\\1[/IMG]',
			'#<img [^>]*src=\'([^\']+)\'[^>]*>#' => '[IMG]\\1[/IMG]',

			'#<(p|div) [^>]*style="text-align:\s*left;?">(.*)</\\1>(\r?\n)??#siU' => "[LEFT]\\2[/LEFT]\n",
			'#<(p|div) [^>]*style="text-align:\s*center;?">(.*)</\\1>(\r?\n)??#siU' => "[CENTER]\\2[/CENTER]\n",
			'#<(p|div) [^>]*style="text-align:\s*right;?">(.*)</\\1>(\r?\n)??#siU' => "[RIGHT]\\2[/RIGHT]\n",
			'#<(p|div) [^>]*class="bbc_left"[^>]*>(.*)</\\1>(\r?\n)??#siU' => "[LEFT]\\2[/LEFT]\n",
			'#<(p|div) [^>]*class="bbc_center"[^>]*>(.*)</\\1>(\r?\n)??#siU' => "[CENTER]\\2[/CENTER]\n",
			'#<(p|div) [^>]*class="bbc_right"[^>]*>(.*)</\\1>(\r?\n)??#siU' => "[RIGHT]\\2[/RIGHT]\n",

			// lists
			'#<ul[^>]*>(.*)</ul>(\r?\n)??#siU' => "[LIST]\\1[/LIST]\n",
			'#<ol[^>]*>(.*)</ol>(\r?\n)??#siU' => "[LIST=1]\\1[/LIST]\n",
			'#<li[^>]*>(.*)</li>(\r?\n)??#siU' => "[*]\\1\n",


			// strip the unnecessary whitespace between start of bullet point and text
			'#(\[\*\])\s*?#siU' => '\\1',

			'#<(p|pre)[^>]*>(&nbsp;|' . chr(0xC2) . chr(0xA0) .'|\s)*</\\1>(\r?\n)??#siU' => "\n",
			'#<p[^>]*>\s*(.*)\s*</p>\s*?#siU' => "\\1\n\n",
			'#<div[^>]*>\s*(.*)\s*</div>\s*?#siU' => "\\1\n\n",

			'#<pre[^>]*>(.*)</pre>(\r?\n)??#siU' => "[CODE]\\1[/CODE]\n",

			'#<!--.*-->#siU' => ''
		);
	}

	protected function _parseIPBoardMediaCode($message)
	{
		return preg_replace_callback(
			'#<div [^>]*class="ipsEmbeddedVideo\s?"[^>]*>.*?<div>.*?<iframe [^>]*src="(.*)"[^>]*></iframe>.*?</div>.*?</div>#siU',
			array($this, '_convertIPBoardMediaTag'),
			$message
		);
	}

	protected function _getIPBoardQuoteReplacements()
	{
		return array(
			// IPB 4.1 quotes
			'#<blockquote [^>]*class="ipsQuote"[^>]*data-ipsquote-username="([^"]+)"[^>]*data-ipsquote-contentcommentid="(\d+)"[^>]*>.*<div [^>]*class="ipsQuote_contents[^"]*"[^>]*>(.*)</div>\s*</blockquote>(\r?\n)??#siU' => '[QUOTE="\\1, post: \\2"]\\3[/QUOTE]',
			'#<blockquote [^>]*class="ipsQuote"[^>]*data-ipsquote-contentcommentid="(\d+)"[^>]*data-ipsquote-username="([^"]+)"[^>]*>.*<div [^>]*class="ipsQuote_contents[^"]*"[^>]*>(.*)</div>\s*</blockquote>(\r?\n)??#siU' => '[QUOTE="\\2, post: \\1"]\\3[/QUOTE]',

			'#<blockquote [^>]*class="ipsQuote"[^>]*data-ipsquote-username="([^"]+)"[^>]*>.*<div [^>]*class="ipsQuote_contents[^"]*"[^>]*>(.*)</div>\s*</blockquote>(\r?\n)??#siU' => '[QUOTE=\\1]\\2[/QUOTE]',

			'#<blockquote [^>]*class="ipsQuote"[^>]*>.*<div [^>]*class="ipsQuote_contents[^"]*"[^>]*>(.*)</div>\s*</blockquote>(\r?\n)??#siU' => '[QUOTE]\\1[/QUOTE]',

			// IPB 4.0 quotes
			'#<blockquote [^>]*class="ipsQuote"[^>]*data-cite="([^"]+)"[^>]*data-ipsquote-contentcommentid="(\d+)"[^>]*>(.*)</blockquote>(\r?\n)??#siU' => '[QUOTE="\\1, post: \\2"]\\3[/QUOTE]',
			'#<blockquote [^>]*class="ipsQuote"[^>]*data-ipsquote-contentcommentid="(\d+)"[^>]*data-cite="([^"]+)"[^>]*>(.*)</blockquote>(\r?\n)??#siU' => '[QUOTE="\\2, post: \\1"]\\3[/QUOTE]',

			'#<blockquote [^>]*class="ipsQuote"[^>]*data-cite="([^"]+)"[^>]*>(.*)</blockquote>(\r?\n)??#siU' => '[QUOTE=\\1]\\2[/QUOTE]',

			'#<blockquote [^>]*class="ipsQuote"[^>]*>(.*)</blockquote>(\r?\n)??#siU' => '[QUOTE]\\1[/QUOTE]'
		);
	}

	protected function _parseIPBoardQuoteCode($message)
	{
		foreach ($this->_getIPBoardQuoteReplacements() AS $pattern => $replacement)
		{
			do
			{
				$newMessage = preg_replace($pattern, $replacement, $message);
				if ($newMessage === $message)
				{
					break;
				}

				$message = $newMessage;
			}
			while (true);
		}

		return $message;
	}
}