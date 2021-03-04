<?php

namespace XF\Import\Importer;

use XF\Import\StepState;

class vBulletin extends AbstractForumImporter
{
	/**
	 * @var \XF\Db\Mysqli\Adapter
	 */
	protected $sourceDb;

	/**
	 * @var array
	 */
	protected $userFields;

	/**
	 * @var int The parentid of root-level forums
	 */
	protected $forumRootId = -1;

	public static function getListInfo()
	{
		return [
			'target' => 'XenForo',
			'source' => 'vBulletin 3.7, 3.8',
			'beta' => true
		];
	}

	protected function getBaseConfigDefault()
	{
		return [
			'db'         => [
				'host'     => '',
				'username' => '',
				'password' => '',
				'dbname'   => '',
				'port'     => 3306,
				'prefix'   => '',
				'charset'  => '', // used for the DB connection
			],
			'attachpath'     => null,
			'avatarpath'     => null,
			'blogattachpath' => null,
			'bburl'          => null,
			'charset'        => '', // used for UTF8 conversion
			'super_admins'   => [],
		];
	}

	public function renderBaseConfigOptions(array $vars)
	{
		if (empty($vars['fullConfig']['db']['host']))
		{
			$configPath = getcwd() . '/includes/config.php';
			if (file_exists($configPath) && is_readable($configPath))
			{
				$config = [];
				include($configPath);

				$vars['db'] = [
					'host'        => $config['MasterServer']['servername'],
					'port'        => $config['MasterServer']['port'],
					'username'    => $config['MasterServer']['username'],
					'password'    => $config['MasterServer']['password'],
					'dbname'      => $config['Database']['dbname'],
					'prefix'      => $config['Database']['tableprefix'],
					'charset'     => $config['Mysqli']['charset']
				];
				$vars['super_admins'] = $config['SpecialUsers']['superadministrators'];
			}
			else
			{
				$vars['db'] = [
					'host' => $this->app->config['db']['host'],
					'port' => $this->app->config['db']['port'],
					'username' => $this->app->config['db']['username']
				];
			}
		}

		return $this->app->templater()->renderTemplate('admin:import_config_vbulletin', $vars);
	}

	public function validateBaseConfig(array &$baseConfig, array &$errors)
	{
		static $guessedPrefixes = null;

		$baseConfig['db']['prefix'] = preg_replace('/[^a-z0-9_]/i', '', $baseConfig['db']['prefix']);

		$fullConfig = array_replace_recursive($this->getBaseConfigDefault(), $baseConfig);
		$missingFields = false;

		if ($fullConfig['db']['host'])
		{
			$validDbConnection = false;

			try
			{
				$sourceDb = new \XF\Db\Mysqli\Adapter($fullConfig['db'], false);
				$sourceDb->getConnection();
				$validDbConnection = true;
			}
			catch (\XF\Db\Exception $e)
			{
				$errors[] = \XF::phrase('source_database_connection_details_not_correct_x', ['message' => $e->getMessage()]);
			}

			if ($validDbConnection)
			{
				try
				{
					$options = $sourceDb->fetchPairs("
						SELECT varname, value
						FROM " . $fullConfig['db']['prefix'] . "setting
						WHERE varname IN('attachpath', 'attachfile', 'avatarpath', 'usefileavatar', 'languageid', 'bburl', 'blogattachfile', 'blogattachpath')
					");
				}
				catch (\XF\Db\Exception $e)
				{
					if ($fullConfig['db']['dbname'] === '')
					{
						$errors[] = \XF::phrase('please_enter_database_name');
					}
					else
					{
						if ($guessedPrefixes === null && $guessedPrefixes = $this->guessTablePrefix($sourceDb, $fullConfig['db']['dbname']))
						{
							if (count($guessedPrefixes) == 1)
							{
								$baseConfig['db']['prefix'] = $guessedPrefixes[0];
								return $this->validateBaseConfig($baseConfig, $errors);
							}
							else
							{
								$errors['foundPrefixes'] = \XF::phrase('table_prefix_incorrect_try_x', [
									'prefixlist' => implode('   ', $guessedPrefixes)]);
							}
						}
						else
						{
							$errors[] = \XF::phrase('table_prefix_or_database_name_is_not_correct');
						}
					}
				}

				if (!empty($options))
				{
					if (!empty($options['usefileavatar']) && !empty($options['avatarpath']))
					{
						$baseConfig['avatarpath'] = $this->getBaseConfigAvatarPath($options['avatarpath']);
					}

					if (!empty($options['attachfile']) && !empty($options['attachpath']))
					{
						$baseConfig['attachpath'] = trim($options['attachpath']);
					}

					if (!empty($options['blogattachfile']) && !empty($options['blogattachpath']))
					{
						$baseConfig['blogattachpath'] = trim($options['blogattachpath']);
					}

					if (!empty($options['bburl']))
					{
						$baseConfig['bburl'] = $options['bburl'];
					}
				}

				if (!empty($options['languageid']))
				{
					$defaultCharset = $sourceDb->fetchOne("
						SELECT charset
						FROM " . $fullConfig['db']['prefix'] . "language
						WHERE languageid = ?
					", $options['languageid']);

					if (!$defaultCharset || str_replace('-', '', strtolower($defaultCharset)) == 'iso88591')
					{
						$baseConfig['charset'] = 'windows-1252';
					}
					else
					{
						$baseConfig['charset'] = strtolower($defaultCharset);
					}
				}
			}
			else
			{
				$missingFields = true;
			}

			$baseConfig['super_admins'] = trim($fullConfig['super_admins']);

		}

		if ($missingFields)
		{
			$errors[] = \XF::phrase('please_complete_required_fields');
		}

		return $errors ? false : true;
	}

	protected function getBaseConfigAvatarPath($pathFromDb)
	{
		return trim($pathFromDb);
	}

	protected function getStepConfigDefault()
	{
		return [
			'users' => [
				'merge_email' => true,
				'merge_name'  => false,
				'super_admins' => []
			],
			'avatars' => [
				'path' => $this->baseConfig['avatarpath']
			],
			'forums' => [
				'parent_node_id' => 0,
				'import_everything' => 1
			],
			'attachments' => [
				'path' => $this->baseConfig['attachpath']
			],
			'faq' => [
				'import_as' => 'page',
				'parent_node_id' => 0
			],
			'notices' => [
				'notice_type' => 'scrolling',
				'persistent_notice_type' => 'block'
			],
			'smilies' => [
				'filename' => 'smilies.' . $this->baseConfig['db']['dbname'] . '.xml'
			]
		];
	}

	protected function getStepConfigOptions(array $vars)
	{
		$vars['stepConfig'] = $this->getStepConfigDefault();

		if (in_array('forums', $vars['steps']) || in_array('faq', $vars['steps']))
		{
			/** @var \XF\Repository\Node $nodeRepo */
			$nodeRepo = $this->app->repository('XF:Node');
			$vars['nodeTree'] = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());
		}

		if (in_array('notices', $vars['steps']))
		{
			/** @var \XF\Repository\Notice $noticeRepo */
			$noticeRepo = $this->app->repository('XF:Notice');
			$vars['noticeTypes'] = $noticeRepo->getNoticeTypes();
		}

		return $vars;
	}

	public function renderStepConfigOptions(array $vars)
	{
		return $this->app->templater()->renderTemplate('admin:import_step_config_vbulletin', $this->getStepConfigOptions($vars));
	}

	public function validateStepConfig(array $steps, array &$stepConfig, array &$errors)
	{
		// super admin IDs
		if (array_key_exists('users', $stepConfig))
		{
			$stepConfig['users']['super_admins'] = isset($stepConfig['users']['super_admins'])
				? preg_split('/\s*,\s*/s', trim($stepConfig['users']['super_admins']), -1, PREG_SPLIT_NO_EMPTY)
				: [];
		}

		// avatars as files - path
		if (array_key_exists('avatars', $stepConfig) && !empty($this->baseConfig['avatarpath']))
		{
			if (!empty($stepConfig['avatars']['path']))
			{
				$path = realpath(trim($stepConfig['avatars']['path']));

				if (!file_exists($path) || !is_dir($path))
				{
					$errors[] = \XF::phrase('directory_specified_as_x_y_not_found_is_not_readable', [
						'type' => 'avatarpath',
						'dir'  => $stepConfig['avatars']['path']
					]);
				}

				$stepConfig['avatars']['path'] = $path;
			}
			else
			{
				// we have no path, this will cause the step to skip
				$stepConfig['avatars']['skip'] = true;

			}
		}

		// attachments as files - path
		if (array_key_exists('attachments', $stepConfig) && !empty($this->baseConfig['attachpath']))
		{
			if (!empty($stepConfig['attachments']['path']))
			{
				$path = realpath(trim($stepConfig['attachments']['path']));

				if (!file_exists($path) || !is_dir($path))
				{
					$errors[] = \XF::phrase('directory_specified_as_x_y_not_found_is_not_readable', [
						'type' => 'attachpath',
						'dir'  => $stepConfig['attachments']['path']
					]);
				}

				$stepConfig['attachments']['path'] = $path;
			}
			else
			{
				// we have no path, this will cause the step to skip
				$stepConfig['attachments']['skip'] = true;
			}
		}

		return $errors ? false : true;
	}

	protected function guessTablePrefix(\XF\Db\Mysqli\Adapter $sourceDb, $dbName)
	{
		// attempt to guess the table prefix by fetching some distinctive vB table names
		$referenceTables = [
			'infractionban',
			'reputationlevel',
			'tachyforumpost',
			'usertextfield',
		];

		$dbTables = $sourceDb->fetchAllColumn("
			SHOW TABLES WHERE
			tables_in_{$dbName}
			REGEXP '^.*(" . implode('|', $referenceTables) . ")$'
		");

		if ($dbTables)
		{
			$foundTables = false;
			$prefixes = [];

			foreach ($dbTables AS $dbTable)
			{
				foreach ($referenceTables AS $referenceTable)
				{
					$index = strpos($dbTable, $referenceTable);
					if ($index !== false)
					{
						$foundTables = true;
						$prefixes[] = substr($dbTable, 0, $index);
					}
				}
			}

			if ($foundTables)
			{
				return array_unique($prefixes);
			}
		}

		return false;
	}

	protected function doInitializeSource()
	{
		$dbConfig = $this->baseConfig['db'];

		$this->sourceDb = new \XF\Db\Mysqli\Adapter($dbConfig, false);

		$this->dataManager->setSourceCharset($this->baseConfig['charset'], true);
	}

	public function __get($name)
	{
		switch ($name)
		{
			case 'prefix':
				return $this->baseConfig['db']['prefix'];

			default:
				throw new \LogicException("Undefined index $name");
		}
	}

	public function getSteps()
	{
		return [
			'userGroups' => [
				'title' => \XF::phrase('user_groups')
			],
			'userFields' => [
				'title' => \XF::phrase('custom_user_fields')
			],
			'users' => [
				'title' => \XF::phrase('users'),
				'depends' => ['userGroups', 'userFields']
			],
			'avatars' => [
				'title' => \XF::phrase('avatars'),
				'depends' => ['users']
			],
			'buddyIgnore' => [
				'title' => \XF::phrase('import_buddy_ignore_lists'),
				'depends' => ['users']
			],
			'paidSubscriptions' => [
				'title' => \XF::phrase('import_paid_subscriptions'),
				'depends' => ['users']
			],
			'customBbCodes' => [
				'title' => \XF::phrase('custom_bb_codes')
			],
			'privateMessages' => [
				'title' => \XF::phrase('import_private_messages'),
				'depends' => ['users']
			],
			'visitorMessages' => [
				'title' => \XF::phrase('import_profile_comments'),
				'depends' => ['users']
			],
			'forums' => [
				'title' => \XF::phrase('forums'),
				'depends' => ['userGroups']
			],
			'moderators' => [
				'title' => \XF::phrase('moderators'),
				'depends' => ['forums', 'users']
			],
			'threadPrefixes' => [
				'title' => \XF::phrase('thread_prefixes'),
				'depends' => ['forums']
			],
			'feeds' => [
				'title' => \XF::phrase('import_rss_feed_sources'),
				'depends' => ['forums', 'threadPrefixes', 'users']
			],
			'threads' => [
				'title' => \XF::phrase('threads'),
				'depends' => ['forums', 'threadPrefixes'],
				'force' => ['posts']
			],
			'contentTags' => [
				'title' => \XF::phrase('tags'),
				'depends' => ['threads']
			],
			'posts' => [
				'title' => \XF::phrase('posts'),
				'depends' => ['threads']
			],
			'postEditHistory' => [
				'title' => \XF::phrase('edit_history'),
				'depends' => ['posts']
			],
			'polls' => [
				'title' => \XF::phrase('polls'),
				'depends' => ['posts']
			],
			'attachments' => [
				'title' => \XF::phrase('attachments'),
				'depends' => ['posts']
			],
			'reputation' => [
				'title' => \XF::phrase('import_positive_reputation'),
				'depends' => ['posts']
			],
			'infractions' => [
				'title' => \XF::phrase('import_infractions'),
				'depends' => ['posts']
			],
			'captchaQuestions' => [
				'title' => \XF::phrase('import_captcha_questions')
			],
			'faq' => [
				'title' => \XF::phrase('import_faq_pages')
			],
			'announcements' => [
				'title' => \XF::phrase('import_announcements'),
				'depends' => ['forums', 'users']
			],
			'notices' => [
				'title' => \XF::phrase('import_notices'),
				'depends' => ['userGroups', 'forums']
			],
			'smilies' => [
				'title' => \XF::phrase('smilies')
			]
		];
	}

	// ############################## STEP: USER GROUPS #########################

	public function stepUserGroups(StepState $state, array $stepConfig)
	{
		$groups = $this->sourceDb->fetchAllKeyed("
			SELECT *
			FROM {$this->prefix}usergroup
			ORDER BY usergroupid
		", 'usergroupid');

		foreach ($groups AS $oldId => $group)
		{
			$permissions = $this->mapUserGroupPermissions($group);
			$titlePriority = 5;

			$groupMap = [
				1 => 1, // guest

				2 => 2, // registered
				3 => 2, // email confirmation
				4 => 2, // moderated

				6 => 3, // admin

				7 => 4, // moderator

			];

			if (array_key_exists($oldId, $groupMap))
			{
				// don't import the group, just map it to one of our defaults
				$this->logHandler('XF:UserGroup', $oldId, $groupMap[$oldId]);
			}
			else
			{
				$data = [
					'title' => strip_tags($group['title']), // don't allow HTML for titles
					'user_title' => $group['usertitle']
				];

				if ($oldId == 5) // super mods
				{
					$titlePriority = 910;
				}

				/** @var \XF\Import\Data\UserGroup $import */
				$import = $this->newHandler('XF:UserGroup');
				$import->bulkSet($data);
				$import->set('display_style_priority', $titlePriority);
				$import->setPermissions($permissions);
				$import->save($oldId);
			}

			$state->imported++;
		}

		return $state->complete();
	}

	protected function bitwise($permissionsInteger, $power)
	{
		return ((int)$permissionsInteger & pow(2, $power));
	}

	protected function mapUserGroupPermissions($group)
	{
		$p = [];

		$forumPerms = intval($group['forumpermissions']);
		$genericPerms = intval($group['genericpermissions']);

		// general and forum permissions
		$this->setPermissionFromBitsMap($p, [
			0  => [
				['general', 'view'],
				['general', 'viewNode'],
				['forum', 'like']
			],
			1  => ['forum', 'viewOthers'],
			2  => ['general', 'search'],
			4  => ['forum', 'postThread'],
			5  => ['forum', 'postReply'],
			7  => ['forum', 'editOwnPost'],
			8  => ['forum', 'deleteOwnPost'],
			9  => ['forum', 'deleteOwnThread'],
			12 => ['forum', 'viewAttachment'],
			13 => ['forum', 'uploadAttachment'],
			15 => ['forum', 'votePoll'],
			17 => ['forum', 'followModerationRules'],
			19 => ['forum', 'viewContent'],
		], $forumPerms);

		// flood check permissions
		$adminPerms = intval($group['adminpermissions']);
		if ($this->bitwise($adminPerms, 0) || $this->bitwise($adminPerms, 1))
		{
			$this->setPermission($p, 'general', 'bypassFloodCheck');
		}

		// avatar permissions
		if ($this->bitwise($genericPerms, 9))
		{
			$this->setPermission($p, 'avatar', 'allowed');
			$this->setPermission($p, 'avatar', 'maxFileSize',
				($group['avatarmaxsize'] > 0 && $group['avatarmaxsize'] < pow(2, 31)) ? $group['avatarmaxsize'] : -1);
		}

		// conversation permissions
		if ($group['pmsendmax'])
		{
			$this->setPermission($p, 'conversation', 'start');
			$this->setPermission($p, 'conversation', 'receive');
			$this->setPermission($p, 'conversation', 'maxRecipients',
				($group['pmsendmax'] > 0 && $group['pmsendmax'] < pow(2, 31)) ? $group['pmsendmax'] : -1);
		}

		// profile post permissions
		if (array_key_exists('visitormessagepermissions', $group))
		{
			// vBulletin with visitormessages

			$vmPerms = intval($group['visitormessagepermissions']);

			$this->setPermissionFromBitsMap($p, [
				0 => [
					['profilePost', 'view'],
					['profilePost', 'like']
				],
				1 => [
					['profilePost', 'post'],
					['profilePost', 'comment']
				],
				2 => ['profilePost', 'editOwn'],
				3 => ['profilePost', 'deleteOwn'],
				5 => ['profilePost', 'manageOwn']
			], $vmPerms);
		}
		else
		{
			// vBulletin before visitormessages

			if (isset($p['general']['view']))
			{
				$this->setPermission($p, 'profilePost', 'view', $p['general']['view']);
			}
			if (isset($p['forum']['like']))
			{
				$this->setPermission($p, 'profilePost', 'like', $p['forum']['like']);
			}
			if (isset($p['forum']['postReply']))
			{
				$this->setPermission($p, 'profilePost', 'post', $p['forum']['postReply']);
			}
			if (isset($p['forum']['editOwnPost']))
			{
				$this->setPermission($p, 'profilePost', 'editOwn', $p['forum']['editOwnPost']);
			}
			if (isset($p['forum']['deleteOwnPost']))
			{
				$this->setPermission($p, 'profilePost', 'deleteOwn', $p['forum']['deleteOwnPost']);
			}
		}

		return $p;
	}

	protected function setPermissionFromBitsMap(array &$permissions, array $map, $permissionsInteger)
	{
		foreach ($map AS $power => $definitions)
		{
			if (!is_array($definitions[0]))
			{
				$definitions = [$definitions];
			}

			foreach ($definitions AS $definition)
			{
				if ($permissionsInteger & 2 ^ $power)
				{
					$this->setPermission($permissions, $definition[0], $definition[1]);
				}
			}
		}
	}

	// ####################### STEP: CUSTOM USER FIELDS #####################

	public function stepUserFields(StepState $state)
	{
		$choiceLookUps = [];

		$profileFields = $this->sourceDb->fetchAllKeyed("
			SELECT pf.*,
				phr1.text AS title,
				phr2.text AS description
			FROM {$this->prefix}profilefield AS pf
			INNER JOIN {$this->prefix}phrase AS phr1
				ON(phr1.varname = CONCAT('field', pf.profilefieldid, '_title'))
			INNER JOIN {$this->prefix}phrase AS phr2
				ON(phr2.varname = CONCAT('field', pf.profilefieldid, '_desc'))
			WHERE pf.profilefieldid > 3
		", 'profilefieldid');

		$existingFields = $this->getExistingUserFieldIds();

		foreach ($profileFields AS $oldId => $field)
		{
			$data = $this->mapXfKeys($field, [
				'display_order' => 'displayorder',
				'max_length'    => 'maxlength',
			]);

			$data['field_id'] = $this->convertToUniqueId($field['title'], $existingFields, 25);

			$data['viewable_profile'] = !$field['hidden'];

			switch ($field['type'])
			{
				case 'select':
				case 'radio':
				case 'checkbox':
				case 'select_multiple':
					$data['field_type'] = $field['type'] == 'select_multiple' ? 'multiselect' : $field['type'];

					if (!$data['field_choices'] = $this->getProfileFieldChoices($field, $choiceLookUps))
					{
						continue;
					}
					break;

				case 'textarea':
					$data['field_type'] = 'textarea';
					break;

				case 'input':
				default:
					$data['field_type'] = 'textbox';
					break;
			}

			switch ($field['required'])
			{
				case 3: // always
				case 1: // at registration and update
					$data['required'] = 1;
					$data['show_registration'] = 1;
					break;

				case 2: // only at registration
					$data['required'] = 0;
					$data['show_registration'] = 1;
					break;

				case 0: // nope
				default:
					$data['required'] = 0;
					$data['show_registration'] = 0;
					break;
			}

			switch ($field['editable'])
			{
				case 0: // nope
					$data['display_group'] = 'personal';
					break;

				case 1: // options: login / privacy
				case 2: // options: messaging
				case 3: // options: thread viewing
				case 4: // options: date / time
				case 5: // options: other
				default:
					$data['display_group'] = 'preferences';
					break;
			}

			if ($field['regex'])
			{
				$data['match_type'] = 'regex';
				$data['match_params']['regex'] = $field['regex'];
			}
			else
			{
				$data['match_type'] = 'none';
			}

			/** @var \XF\Import\Data\UserField $import */
			$import = $this->newHandler('XF:UserField');
			$import->setTitle($this->convertToUtf8($field['title']), $this->convertToUtf8($field['description']));
			$import->bulkSet($data);

			$import->save($oldId);

			$state->imported++;
		}

		$this->session->extra['profileFieldChoices'] = $choiceLookUps;

		return $state->complete();
	}

	/**
	 * Fetches a [field_id => 1] array of all custom user fields in the XF database
	 *
	 * @return array
	 */
	protected function getExistingUserFieldIds()
	{
		return $this->db()->fetchPairs("SELECT field_id, 1 FROM xf_user_field");
	}

	protected function getProfileFieldChoices(array $field, array &$choiceLookUps)
	{
		$choiceData = $this->decodeValue($field['data'], 'serialized-array');

		if (empty($choiceData))
		{
			// data could be corrupted, or an invalid character set
			return $choiceData;
		}

		$choices = [];

		foreach ($choiceData AS $key => $choice)
		{
			if ($choiceId = $this->convertToId($choice, 23))
			{
				$i = 1;
				$choiceIdBase = $choiceId;
				while (isset($choices[$choiceId]))
				{
					$choiceId = $choiceIdBase . '_' . ++$i;
				}
			}
			else
			{
				$choiceId = $key;
			}

			$choices[$choiceId] = $choice;
		}

		$lookUps = [];
		$multiple = false;

		switch ($field['type'])
		{
			case 'checkbox':
			case 'select_multiple':
				$multiple = true;
				$i = 1;
				foreach ($choices AS $key => $value)
				{
					$lookUps[$i] = $key;
					$i = $i * 2;
				}
				break;

			case 'select':
			case 'radio':
				$multiple = false;
				foreach ($choices AS $key => $value)
				{
					$lookUps[$value] = $key;
				}
				break;
		}

		$choiceLookUps[$field['profilefieldid']] = [
			'multiple' => $multiple,
			'choices' => $lookUps
		];

		return $choices;
	}

	// ############################## STEP: USERS #############################

	public function getStepEndUsers()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(userid) FROM {$this->prefix}user") ?: 0;
	}

	public function stepUsers(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		$users = $this->sourceDb->fetchAll("		
			SELECT user.*, userfield.*, usertextfield.*,
				IF(admin.userid IS NULL, 0, 1) AS is_admin,
				admin.adminpermissions AS admin_permissions,
				IF(userban.userid IS NULL, 0, 1) AS is_banned,
				userban.bandate AS ban_date,
				userban.liftdate AS ban_end_date,
				userban.reason AS ban_reason,
				userban.adminid AS ban_user_id,
				IF(usergroup.adminpermissions & 1, 1, 0) AS is_super_moderator,
				IF(customavatar.userid, 1, 0) AS has_custom_avatar
			FROM {$this->prefix}user AS
				user
			STRAIGHT_JOIN {$this->prefix}userfield AS
				userfield ON (user.userid = userfield.userid)
			STRAIGHT_JOIN {$this->prefix}usertextfield AS
				usertextfield ON (user.userid = usertextfield.userid)
			LEFT JOIN {$this->prefix}administrator AS
				admin FORCE INDEX (PRIMARY) ON (user.userid = admin.userid)
			LEFT JOIN {$this->prefix}userban AS
				userban ON (user.userid = userban.userid)
			LEFT JOIN {$this->prefix}usergroup AS
				usergroup ON (user.usergroupid = usergroup.usergroupid)
			LEFT JOIN {$this->prefix}customavatar AS
				customavatar ON (user.userid = customavatar.userid)
			WHERE user.userid > ? AND user.userid <= ?
			ORDER BY user.userid
			LIMIT {$limit}
		", [$state->startAfter, $state->end]);

		if (!$users)
		{
			return $state->complete();
		}

		foreach ($users AS $user)
		{
			$oldId = $user['userid'];
			$state->startAfter = $oldId;

			if (!$import = $this->setupImportUser($user, $state, $stepConfig))
			{
				continue;
			}

			if ($newUserId = $this->importUser($oldId, $import, $stepConfig))
			{
				$state->imported++;

				if ($user['is_super_moderator'])
				{
					$this->session->extra['superModerators'][$oldId] = $newUserId;
				}
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	protected function setupImportUser(array $user, StepState $state, array $stepConfig)
	{
		/** \XF\Import\Data\User $import */
		$import = $this->newHandler('XF:User');

		$this->typeMap('user_group');

		// straight import
		$userData = $this->mapXfKeys($user, [
			'email',
			'last_activity' => 'lastactivity',
			'register_date' => 'joindate',
			'message_count' => 'posts',
			'is_admin',
			'is_banned',
			'custom_title' => 'usertitle',
		]);
		$import->bulkSetDirect('user', $userData);

		// username
		$import->username = $user['username'];

		// authentication
		if (!$import = $this->setUserAuthData($import, $user))
		{
			// failed for unknown reason, skip this user.
			return false;
		}

		// user groups
		if ($user['usergroupid'] == 6)
		{
			// if user is an admin, make them a member of registered users with additional group admins
			$import->user_group_id = 2;

			$user['membergroupids'] = $user['membergroupids'] ? "{$user['membergroupids']},6" : 6;
		}
		else
		{
			$import->user_group_id = $this->lookupId('user_group', $user['usergroupid'], 2);
		}

		$import->display_style_group_id = $this->lookupId('user_group', $user['displaygroupid'], 2);
		if ($user['membergroupids'])
		{
			$import->secondary_group_ids = $this->mapUserGroupList($user['membergroupids']);
		}

		switch ($user['usergroupid'])
		{
			case 3:
				$import->user_state = 'email_confirm';
				break;
			case 4:
				$import->user_state = 'moderated';
				break;
			default:
				$import->user_state = 'valid';
		}

		// custom titles set by admin
		if ($user['customtitle'] == 1)
		{
			$import->custom_title = strip_tags(
				preg_replace('#<br\s*/?>#i', ', ', $this->convertToUtf8($user['usertitle']))
			);
		}

		// birthday stuff
		if ($user['birthday'])
		{
			if (sscanf($user['birthday'], '%d-%d-%d', $mm, $dd, $yyyy) == 3)
			{
				$import->dob_day = $dd;
				$import->dob_month = $mm;
				$import->dob_year = $yyyy;
			}
		}
		switch ($user['showbirthday'])
		{
			case 0:
				$import->show_dob_year = 0;
				$import->show_dob_date = 0;
				break;
			case 1:
				$import->show_dob_year = 1;
				$import->show_dob_date = 0;
				break;
			case 2:
				$import->show_dob_year = 1;
				$import->show_dob_date = 1;
				break;
			case 3:
				$import->show_dob_year = 0;
				$import->show_dob_date = 1;
				break;
		}

		$optionBits = intval($user['options']);

		// misc conversions
		$import->warning_points = isset($user['ipoints']) ? $user['ipoints'] : 0;

		$import->timezone = $this->getTimezoneFromOffset($user['timezoneoffset'], $this->bitwise($optionBits, 6));

		$import->content_show_signature = $this->bitwise($optionBits, 0) ? 1 : 0; // showsignatures
		$import->receive_admin_email = $this->bitwise($optionBits, 4) ? 1 : 0; // adminemail

		$import->location = isset($user['field2']) ? $user['field2'] : '';

		$import->website = $user['homepage'];
		$import->signature = trim($user['signature']);

		$import->about = trim((isset($user['field1']) ? $user['field1'] . "\n\n" : '') . (isset($user['field3']) ? $user['field3'] . "\n\n" : ''));

		if (!($this->bitwise($optionBits, 11))) // receivepm
		{
			$import->allow_send_personal_conversation = 'none';
		}
		else if ($this->bitwise($optionBits, 17)) // receivepmbuddies
		{
			$import->allow_send_personal_conversation = 'followed';
		}

		if (!($this->bitwise($optionBits, 23))) // vm_enable
		{
			$import->allow_post_profile = 'none';
		}
		else if ($this->bitwise($optionBits, 24)) // vm_contactonly
		{
			$import->allow_post_profile = 'followed';
		}

		switch ($user['autosubscribe'])
		{
			case -1:
				$import->creation_watch_state = '';
				$import->interaction_watch_state = '';
				break;
			case 0:
				$import->creation_watch_state = 'watch_no_email';
				$import->interaction_watch_state = 'watch_no_email';
				break;
			default:
				$import->creation_watch_state = 'watch_email';
				$import->interaction_watch_state = 'watch_email';
		}

		// custom user fields
		$fieldValues = $this->mapUserFields($user, $this->session->extra['profileFieldChoices']);

		// get XF user fields and map identity types to custom fields if they exist
		$userFields = $this->getExistingUserFieldIds();
		foreach (['icq', 'aim', 'yahoo', 'msn', 'skype'] AS $idType)
		{
			if (isset($userFields[$idType]))
			{
				$fieldValues[$idType] = $user[$idType];
			}
		}
		$import->setCustomFields($fieldValues);

		// admin permissions
		if ($user['is_admin'] && $user['admin_permissions'])
		{
			$isSuperAdmin = in_array($user['userid'], $stepConfig['super_admins']) ? 1 : 0;
			$permissionCache = $this->mapAdminPermissions($user['admin_permissions']);

			$adminData = [
				'last_login' => $user['lastvisit'],
				'is_super_admin' => $isSuperAdmin,
				'permission_cache' => $permissionCache
			];

			$import->setAdmin($adminData);
		}

		// banned user
		if ($user['is_banned'])
		{
			$banData = $this->mapXfKeys($user, [
				'ban_date',
				'end_date' => 'ban_end_date',
			]);
			$banData['user_reason'] = $user['ban_reason'] ?: '';
			$banData['ban_user_id'] = $this->lookupId('user', $user['ban_user_id'], 0);

			$import->setBan($banData);
		}

		return $import;

		// TODO: Gravatar assigment for users who have posted but have no avatar, maybe do it as a clean-up job after import?
		// TODO: super moderators?
	}

	/**
	 * @param \XF\Import\Data\User $import
	 * @param array                $user
	 *
	 * @return \XF\Import\Data\User
	 */
	protected function setUserAuthData(\XF\Import\Data\User $import, array $user)
	{
		$import->setPasswordData('XF:vBulletin', [
			'hash' => $user['password'],
			'salt' => $user['salt']
		]);

		return $import;
	}

	protected function mapAdminPermissions($permissionBits)
	{
		$permissionBits = intval($permissionBits);

		$adminPermissions = [];

		$map = [
			2 => 'option',
			3 => 'style',
			4 => 'language',
			5 => 'node',
			8 => ['user', 'ban', 'userField', 'trophy', 'userUpgrade'],
			9 => 'userGroup',
			12 => 'bbCodeSmilie',
			13 => 'cron',
			14 => ['import', 'upgradeXenForo'],
			16 => 'addOn'
		];

		foreach ($map AS $power => $options)
		{
			if ($this->bitwise($permissionBits, $power))
			{
				if (!is_array($options))
				{
					$options = [$options];
				}

				foreach ($options AS $option)
				{
					$adminPermissions[] = $option;
				}
			}
		}

		return $adminPermissions;
	}

	protected function mapUserGroupList($memberGroupIds)
	{
		return $this->getHelper()->mapUserGroupList($memberGroupIds);
	}

	protected function mapUserFields(array $user, array $profileFieldChoices)
	{
		$fieldValues = [];

		foreach ($this->typeMap('user_field') AS $fieldId => $newFieldId)
		{
			$fieldName = 'field' . $fieldId;

			if ($user[$fieldName] !== '')
			{
				if (array_key_exists($fieldId, $profileFieldChoices))
				{
					// choices

					$fieldInfo = $profileFieldChoices[$fieldId];

					if ($fieldInfo['multiple'])
					{
						// multiple choice
						$fieldValue = [];

						foreach ($fieldInfo['choices'] AS $bitValue => $stringValue)
						{
							if ($user[$fieldName] & $bitValue)
							{
								$fieldValue[$stringValue] = $stringValue;
							}
						}
					}
					else if (array_key_exists($user[$fieldName], $fieldInfo['choices']))
					{
						$fieldValue = $fieldInfo['choices'][$user[$fieldName]];
					}
				}
				else
				{
					// freeform input

					$fieldValue = $user[$fieldName];
				}

				if (!empty($fieldValue))
				{
					$fieldValues[$newFieldId] = $fieldValue;
				}
			}
		}

		return $fieldValues;
	}

	// ########################### STEP: AVATARS ###############################

	public function getStepEndAvatars()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(userid) FROM {$this->prefix}customavatar") ?: 0;
	}

	public function stepAvatars(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		if (isset($stepConfig['skip']))
		{
			return $state->complete();
		}

		$timer = new \XF\Timer($maxTime);

		$users = $this->sourceDb->fetchAllKeyed("
			SELECT u.userid, u.avatarrevision, c.filename
			FROM {$this->prefix}customavatar AS c
			INNER JOIN {$this->prefix}user AS u ON(u.userid = c.userid)
			WHERE c.userid > ? AND c.userid <= ?
			ORDER BY c.userid
			LIMIT {$limit}
		", 'userid', [$state->startAfter, $state->end]);

		if (!$users)
		{
			return $state->complete();
		}

		/** @var \XF\Import\DataHelper\Avatar $avatarHelper */
		$avatarHelper = $this->dataManager->helper('XF:Avatar');

		$this->lookup('user', array_keys($users));

		foreach ($users AS $userId => $avatar)
		{
			$state->startAfter = $userId;

			if (!$avatar['avatarrevision'])
			{
			//	continue; // TODO: work out why on earth I added this??
			}

			if (!$mappedUserId = $this->lookupId('user', $userId))
			{
				continue;
			}


			if ($stepConfig['path'])
			{
				// avatars stored as files
				$avatarTempFile = $this->getAvatarFilePath($stepConfig['path'], $avatar);

				if (!file_exists($avatarTempFile))
				{
					continue;
				}
			}
			else
			{
				// avatars stored in DB
				$fileData = $this->sourceDb->fetchOne("
					SELECT filedata
					FROM {$this->prefix}customavatar
					WHERE userid = ?
				", $userId);

				if ($fileData === '')
				{
					continue;
				}

				$avatarTempFile = \XF\Util\File::getTempFile();
				\XF\Util\File::writeFile($avatarTempFile, $fileData);
			}

			$targetUser = $this->em()->find('XF:User', $mappedUserId, ['Profile']);
			if (!$targetUser)
			{
				continue;
			}

			$avatarHelper->setAvatarFromFile($avatarTempFile, $targetUser);

			$state->imported++;

			$this->em()->detachEntity($targetUser);

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		\XF\Util\File::cleanUpTempFiles();

		return $state->resumeIfNeeded();
	}

	protected function getAvatarFilePath($path, array $avatar)
	{
		return "{$path}/avatar{$avatar['userid']}_{$avatar['avatarrevision']}.gif";
	}

	// ########################### STEP: BUDDY / IGNORE LISTS ###############################

	public function getStepEndBuddyIgnore()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(userid) FROM {$this->prefix}user") ?: 0;
	}

	public function stepBuddyIgnore(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		$users = $this->sourceDb->fetchAllKeyed("
			SELECT user.userid,
				usertextfield.buddylist,
				usertextfield.ignorelist
			FROM {$this->prefix}user AS 
				user
			INNER JOIN {$this->prefix}usertextfield AS
				usertextfield ON (user.userid = usertextfield.userid)
			WHERE user.userid > ? AND user.userid <= ?
			AND (usertextfield.buddylist <> '' OR usertextfield.ignorelist <> '')
			ORDER BY user.userid
			LIMIT {$limit}
		", 'userid', [$state->startAfter, $state->end]);

		if (!$users)
		{
			return $state->complete();
		}

		$sourceUserIds = array_keys($users);

		foreach ($users AS $userId => &$user)
		{
			$user['buddy_ids'] = ($user['buddylist'] === '' ? [] : explode(',', $user['buddylist']));
			$user['ignore_ids'] = ($user['ignorelist'] === '' ? [] : explode(',', $user['ignorelist']));

			$sourceUserIds = $sourceUserIds + $user['buddy_ids'] + $user['ignore_ids'];
		}

		$this->lookup('user', array_unique($sourceUserIds));

		/** @var \XF\Import\DataHelper\User $userHelper */
		$userHelper = $this->dataManager->helper('XF:User');

		foreach ($users AS $userId => $user)
		{
			$oldId = $userId;
			$state->startAfter = $oldId;

			if (!$newUserId = $this->lookupId('user', $userId))
			{
				continue;
			}

			$hasData = false;

			if (!empty($user['buddy_ids']))
			{
				$newFollowIds = $this->lookup('user', $user['buddy_ids']);
				$userHelper->importFollowing($newUserId, $newFollowIds);
				$hasData = true;
			}

			if (!empty($user['ignore_ids']))
			{
				$newIgnoreIds = $this->lookup('user', $user['ignore_ids']);
				$userHelper->importIgnored($newUserId, $newIgnoreIds);
				$hasData = true;
			}

			if ($hasData)
			{
				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	// ########################### STEP: PAID SUBSCRIPTIONS ###############################

	public function getStepEndPaidSubscriptions()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(subscriptionlogid) FROM {$this->prefix}subscriptionlog") ?: 0;
	}

	public function stepPaidSubscriptions(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		if (empty($this->session->extra['importedSubscriptionDefinitions']))
		{
			$subs = $this->sourceDb->fetchAll("
				SELECT sub.*,
					ph1.text AS title,
					ph2.text AS description
				FROM {$this->prefix}subscription AS
					sub
				INNER JOIN {$this->prefix}phrase AS
					ph1 ON(ph1.varname = CONCAT('sub', sub.subscriptionid, '_title'))
				INNER JOIN {$this->prefix}phrase AS
					ph2 ON(ph2.varname = CONCAT('sub', sub.subscriptionid, '_desc'))
			");

			foreach ($subs AS $sub)
			{
				$oldId = $sub['subscriptionid'];

				$data = $this->mapXfKeys($sub, [
						'title',
						'description',
						'display_order' => 'displayorder'
					]) + [
						'can_purchase' => false,
						'cost_amount' => 1,
						'cost_currency' => 'usd',
						'length_amount' => 0,
						'length_unit' => ''
					];

				if ($sub['membergroupids'])
				{
					$data['extra_group_ids'] = $this->mapUserGroupList($sub['membergroupids']);
				}

				$subInfo = @unserialize($sub['cost']);
				if (is_array($subInfo))
				{
					if (isset($subInfo[0]))
					{
						$subInfo = $subInfo[0];
					}

					if (!empty($subInfo['cost']) && is_array($subInfo['cost']))
					{
						foreach ($subInfo['cost'] AS $currency => $cost)
						{
							if (floatval($cost) > 0)
							{
								$data['cost_amount'] = floatval($cost);
								$data['cost_currency'] = $currency;
								break;
							}
						}
					}

					if (!empty($subInfo['units']))
					{
						$data['length_amount'] = $subInfo['length'];

						switch ($subInfo['units'])
						{
							case 'Y': $data['length_unit'] = 'year'; break;
							case 'M': $data['length_unit'] = 'month'; break;
							case 'D': $data['length_unit'] = 'day'; break;
						}
					}

					$data['recurring'] = !empty($subInfo['recurring']) ? 1 : 0;
				}

				/** \XF\Import\Data\UserUpgrade $import */
				$import = $this->newHandler('XF:UserUpgrade');
				$import->bulkSet($data);
				$import->payment_profile_ids = [1]; // TODO: read a list of actually-available options?
				$import->save($oldId);
			}

			$this->session->extra['importedSubscriptionDefinitions'] = true;
		}

		$logs = $this->sourceDb->fetchAll("
			SELECT *
			FROM {$this->prefix}subscriptionlog
			WHERE subscriptionlogid > ? AND subscriptionlogid <= ?
			AND status = 1
			ORDER BY subscriptionlogid
			LIMIT {$limit}
		", [$state->startAfter, $state->end]);

		if (!$logs)
		{
			return $state->complete();
		}

		$this->lookup('user_upgrade', $this->pluck($logs, 'subscriptionid'));
		$this->lookup('user', $this->pluck($logs, 'userid'));
		$this->lookup('user_group', $this->pluck($logs, 'pusergroupid'));

		foreach ($logs AS $log)
		{
			$oldId = $log['subscriptionlogid'];
			$state->startAfter = $oldId;

			$data = $this->mapXfKeys($log, [
				'start_date' => 'regdate',
				'end_date' => 'expirydate'
			]);

			if (!$data['user_id'] = $this->lookupId('user', $log['userid']))
			{
				continue;
			}

			if (!$data['user_upgrade_id'] = $this->lookupId('user_upgrade', $log['subscriptionid']))
			{
				continue;
			}

			/** @var \XF\Import\Data\UserUpgradeActive $import */
			$import = $this->newHandler('XF:UserUpgradeActive');
			$import->bulkSet($data);
			$import->save($oldId);

			$state->imported++;

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	// ########################### STEP: CUSTOM BB CODES ###############################

	public function stepCustomBbCodes(StepState $state)
	{
		$bbCodes = $this->sourceDb->fetchAll("
			SELECT *
			FROM {$this->prefix}bbcode
		");

		if (!$bbCodes)
		{
			return $state->complete();
		}

		$bbCodeTags = [];
		foreach ($bbCodes AS $bbCode)
		{
			$bbCodeTags[$bbCode['bbcodetag']][$bbCode['twoparams']] = $bbCode;
		}

		$existingRules = $this->app->bbCode()->rules('import');

		$find = ['%1$s', '%2$s'];
		$replace = ['{text}', '{option}'];

		foreach ($bbCodeTags AS $tag => $bbCodes)
		{
			if ($existingRules->getTag(strtolower($tag)))
			{
				// we already have a bbCode tag with that tag name, so skip it.
				continue;
			}

			/*
			 * vBulletin allows two records of the same tag, one with and one without an option.
			 * We can't automagically merge the two HTML replacements, so if we find two records for
			 * the same tag, we will import only the no-option version and set it as has_option=optional
			 * so that both [tag] and [tag=option] will match in any imported content.
			 */
			if (count($bbCodes) == 2)
			{
				$optionOptional = true;
				unset($bbCodes[1]);
			}
			else
			{
				$optionOptional = false;
			}

			$bbCode = current($bbCodes);

			$import = $this->newHandler('XF:BbCode');
			$import->preventRetainIds();

			$import->bb_code_id = $bbCode['bbcodetag'];
			$import->replace_html = str_ireplace($find, $replace, $bbCode['bbcodereplacement']);
			$import->has_option = ($optionOptional ? 'optional' : ($bbCode['twoparams'] ? 'yes' : 'no'));

			$map = [
				0 => 'allow_empty',
				1 => 'plain_children',
				2 => 'disable_smilies',
				3 => 'disable_nl2br',
				4 => 'disable_autolink'
			];
			foreach ($map AS $power => $option)
			{
				if ($this->bitwise($bbCode['options'], $power))
				{
					$import->$option = true;
				}
			}

			$import->setTitle($this->convertToUtf8($bbCode['title']));
			$import->setExample($this->convertToUtf8($bbCode['bbcodeexample']));

			if ($newId = $import->save($bbCode['bbcodeid']))
			{
				$state->imported++;
			}
		}

		return $state->complete();
	}

	// ########################### STEP: PRIVATE MESSAGES ###############################

	public function getStepEndPrivateMessages()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(pmtextid) FROM {$this->prefix}pmtext") ?: 0;
	}

	public function stepPrivateMessages(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		$messages = $this->sourceDb->fetchAll("
			SELECT *
			FROM {$this->prefix}pmtext AS
				pmtext
			WHERE pmtextid > ? AND pmtextid <= ?
			ORDER BY pmtextid
			LIMIT {$limit}
		", [$state->startAfter, $state->end]);

		if (!$messages)
		{
			return $state->complete();
		}

		foreach ($messages AS $message)
		{
			$oldId = $message['pmtextid'];
			$state->startAfter = $oldId;

			if ($message['message'] == '')
			{
				continue;
			}

			$recipients = @unserialize($message['touserarray']);
			if (!is_array($recipients))
			{
				// can't decode the touserarray, we can't work with this
				continue;
			}

			$mapUsers = [
				$message['fromuserid'] => $message['fromusername']
			];

			foreach ($recipients AS $id => $recipient)
			{
				if (!is_array($recipient))
				{
					$recipient = [$id => $recipient];
				}

				foreach ($recipient AS $userId => $username)
				{
					$mapUsers[$userId] = $username;
				}
			}

			$this->lookup('user', array_keys($mapUsers));

			if (!$fromUserId = $this->lookupId('user', $message['fromuserid']))
			{
				continue;
			}

			$fromUser = [
				'user_id' => $fromUserId,
				'username' => $message['fromusername']
			];

			$readState = $this->sourceDb->fetchPairs("
				SELECT userid, IF(folderid >= 0, messageread, 1)
				FROM {$this->prefix}pm
				WHERE pmtextid = ?
			", $message['pmtextid']);

			$conversation = $fromUser + [
					'title' => $message['title'],
					'start_date' => $message['dateline'],
				];

			/** @var \XF\Import\Data\ConversationMaster $import */
			$import = $this->newHandler('XF:ConversationMaster');
			$import->bulkSet($conversation);

			// recipients
			foreach ($mapUsers AS $userId => $username)
			{
				if ($newUserId = $this->lookupId('user', $userId))
				{
					if (isset($readState[$userId]))
					{
						$lastReadDate = ($readState[$userId] ? $message['dateline'] : 0);
						$recipientState = 'active';
					}
					else
					{
						$lastReadDate = $message['dateline'];
						$recipientState = 'deleted';
					}

					$import->addRecipient($newUserId, $recipientState, [
						'last_read_date' => $lastReadDate,
						'starred' => 0
					]);
				}
			}

			if ($message['message'] !== '')
			{
				/** @var \XF\Import\Data\ConversationMessage $importMessage */
				$importMessage = $this->newHandler('XF:ConversationMessage');
				$importMessage->bulkSet($fromUser + $this->mapXfKeys($message, [
						'message_date' => 'dateline',
						'message'
					]));

				$import->addMessage($oldId, $importMessage);
			}

			$newId = $import->save($oldId);
			if ($newId)
			{
				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	// ########################### STEP: VISITOR MESSAGES ###############################

	public function getStepEndVisitorMessages()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(vmid) FROM {$this->prefix}visitormessage") ?: 0;
	}

	public function stepVisitorMessages(StepState $state, array $stepConfig, $maxTime, $limit = 1000)
	{
		$timer = new \XF\Timer($maxTime);

		$visitorMessages = $this->sourceDb->fetchAll("
			SELECT vm.*, IF(user.username IS NULL, vm.postusername, user.username) AS
				username
			FROM {$this->prefix}visitormessage AS
				vm
			LEFT JOIN {$this->prefix}user AS
				user ON (vm.postuserid = user.userid)
			WHERE vm.vmid > ? AND vm.vmid <= ?
			LIMIT {$limit}
		", [$state->startAfter, $state->end]);

		if (!$visitorMessages)
		{
			return $state->complete();
		}

		$mapUsers = [];
		foreach ($visitorMessages AS $visitorMessage)
		{
			$mapUsers[] = $visitorMessage['userid'];
			$mapUsers[] = $visitorMessage['postuserid'];
		}
		$this->lookup('user', $this->pluck($visitorMessages, ['userid', 'postuserid']));

		$stringFormatter = $this->app->stringFormatter();

		foreach ($visitorMessages AS $visitorMessage)
		{
			$oldId = $visitorMessage['vmid'];
			$state->startAfter = $oldId;

			if (trim($visitorMessage['postusername']) === '')
			{
				continue;
			}

			if (!$profileUserId = $this->lookupId('user', $visitorMessage['userid']))
			{
				continue;
			}

			$message = $stringFormatter->stripBbCode($visitorMessage['pagetext'], [
				'stripQuote' => true,
				'hideUnviewable' => false
			]);

			switch ($visitorMessage['state'])
			{
				case 'deleted':
					$messageState = 'deleted';
					break;

				case 'moderation':
					$messageState = 'moderated';
					break;

				default:
					$messageState = 'visible';
			}

			/** @var \XF\Import\Data\ProfilePost $import */
			$import = $this->newHandler('XF:ProfilePost');
			$import->bulkSet([
				'profile_user_id' => $profileUserId,
				'user_id' => $this->lookupId('user', $visitorMessage['postuserid'], 0),
				'username' => $visitorMessage['postusername'],
				'post_date' => $visitorMessage['dateline'],
				'message' => $message,
				'message_state' => $messageState
			]);
			$import->setLoggedIp($visitorMessage['ipaddress']);

			if ($newId = $import->save($oldId))
			{
				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	// ########################### STEP: FORUMS ###############################

	public function stepForums(StepState $state, array $stepConfig)
	{
		$forums = $this->getForums($stepConfig);

		if (!$forums)
		{
			return $state->complete();
		}

		$permissions = $this->getForumPermissions();
		$this->lookup('user_group', $this->pluck($permissions, 'usergroupid'));

		$forumTree = $this->prepareForumTree($forums);
		$forumPermissions = $this->prepareForumPermissions($permissions);

		while (!empty($forumTree))
		{
			$oldParentId = array_keys($forumTree)[0];
			$state->imported += $this->importNodeTree($forumTree, $forumPermissions, $oldParentId, $stepConfig['parent_node_id']);
		}

		return $state->complete();
	}

	protected function getForums(array $stepConfig)
	{
		return $this->sourceDb->fetchAllKeyed("SELECT * FROM {$this->prefix}forum", 'forumid');
	}

	protected function getForumPermissions()
	{
		return $this->sourceDb->fetchAll("SELECT * FROM {$this->prefix}forumpermission");
	}

	/**
	 * Converts a flat array of [forum_id => forum] into a tree structure
	 * of [parent_id] => [forum_id => forum]]
	 *
	 * @param array $forums
	 *
	 * @return array
	 */
	protected function prepareForumTree(array $forums)
	{
		$forumTree = [];

		foreach ($forums AS $forumId => $forum)
		{
			$forumTree[$forum['parentid']][$forumId] = $forum;
		}

		return $forumTree;
	}

	/**
	 * Converts a flat array of [i => permissions] into a grouped structure
	 * of [forum_id => [usergroup_id => permissions]]
	 *
	 * @param array $permissions
	 *
	 * @return array
	 */
	protected function prepareForumPermissions(array $permissions)
	{
		$forumPermissions = [];

		foreach ($permissions AS $permission)
		{
			$forumPermissions[$permission['forumid']][$permission['usergroupid']] = $permission['forumpermissions'];
		}

		return $forumPermissions;
	}

	protected function importNodeTree(array &$nodeTree, array $permissions, $oldParentId = 0, $newParentId = 0)
	{
		if (!isset($nodeTree[$oldParentId]))
		{
			return 0;
		}

		$total = 0;

		foreach ($nodeTree[$oldParentId] AS $forum)
		{
			$importNode = $this->setupNodeImport($forum, $newParentId);
			if (!$importNode)
			{
				continue;
			}

			if ($newNodeId = $importNode->save($forum['forumid']))
			{
				$this->logExtraForumData($forum, $newNodeId);

				if (!empty($permissions[$forum['forumid']]))
				{
					if ($importPermissions = $this->setupNodePermissionImport($permissions[$forum['forumid']], $newNodeId))
					{
						$importPermissions->save($forum['forumid']);
					}
				}

				$total++;
				$total += $this->importNodeTree($nodeTree, $permissions, $forum['forumid'], $newNodeId);
			}
		}

		unset($nodeTree[$oldParentId]);

		return $total;
	}

	protected function logExtraForumData(array $forum, $newNodeId)
	{
	}

	/**
	 * @param array $forum
	 * @param       $newParentId
	 *
	 * @return null|\XF\Import\Data\Node
	 */
	protected function setupNodeImport(array $forum, $newParentId)
	{
		$forum['options'] = intval($forum['options']);

		/** @var \XF\Import\Data\Node $importNode */
		$importNode = $this->newHandler('XF:Node');
		$importNode->bulkSet([
			'title' => strip_tags($forum['title']),
			'description' => $forum['description'] ?: '',
			'display_order' => $forum['displayorder'],
			'parent_node_id' => $newParentId,
			'display_in_list' => ($this->bitwise($forum['options'], 0) && $forum['displayorder'] > 0)
		]);

		if ($importNode = $this->setupNodeType($importNode, $forum))
		{
			return $importNode;
		}

		return null;
	}

	protected function setupNodeType(\XF\Import\Data\Node $importNode, array $forum)
	{
		if (!empty($forum['link']))
		{
			if ($nodeData = $this->setupNodeLinkForumImport($forum))
			{
				return $importNode->setType('LinkForum', $nodeData);
			}
		}
		else if ($this->bitwise($forum['options'], 2))
		{
			if ($nodeData = $this->setupNodeForumImport($forum))
			{
				return $importNode->setType('Forum', $nodeData);
			}
		}
		else
		{
			if ($nodeData = $this->setupNodeCategoryImport($forum))
			{
				return $importNode->setType('Category', $nodeData);
			}
		}

		return null;
	}

	/**
	 * @param array $data
	 *
	 * @return \XF\Import\Data\Category
	 */
	protected function setupNodeCategoryImport(array $data)
	{
		return $this->newHandler('XF:Category');
	}

	/**
	 * @param array $data
	 *
	 * @return \XF\Import\Data\LinkForum
	 */
	protected function setupNodeLinkForumImport(array $data)
	{
		/** @var \XF\Import\Data\LinkForum $handler */
		$handler = $this->newHandler('XF:LinkForum');

		$handler->bulkSet([
			'link_url' => $data['link']
		]);

		return $handler;
	}

	/**
	 * @param array $data
	 *
	 * @return \XF\Import\Data\Forum
	 */
	protected function setupNodeForumImport(array $data)
	{
		/** @var \XF\Import\Data\Forum $handler */
		$handler = $this->newHandler('XF:Forum');

		$handler->bulkSet($this->mapXfKeys($data, [
				'discussion_count' => 'threadcount',
				'last_post_date' => 'lastpost'
			]) + [
				'message_count' => $data['replycount'] + $data['threadcount'],
				'last_post_username' => $data['lastposter']
			]);

		if (isset($data['lastthread']))
		{
			$handler->last_thread_title = $data['lastthread'];
		}

		if ($subscribers = $this->getForumSubscribers($data['forumid']))
		{
			$this->lookup('user', array_keys($subscribers));

			foreach ($subscribers AS $oldUserId => $emailUpdate)
			{
				if ($newUserId = $this->lookupId('user', $oldUserId))
				{
					$handler->addForumWatcher($newUserId, $this->setupForumSubscribeData($emailUpdate));
				}
			}
		}

		return $handler;
	}

	protected function getForumSubscribers($forumId)
	{
		return $this->sourceDb->fetchPairs("
			SELECT userid, emailupdate
			FROM {$this->prefix}subscribeforum
			WHERE forumid = ?
		", $forumId);
	}

	protected function setupForumSubscribeData($emailUpdate)
	{
		return [
			'notify_on' => 'thread',
			'send_alert' => true,
			'send_email' => ($emailUpdate > 0)
		];
	}

	protected function setupNodePermissionImport($permissionsByUserGroup, $newNodeId)
	{
		$permissionMap = [
			1  => 'viewContent',
			4  => 'viewOthers',
			5  => 'postReply',
			7  => 'editOwnPost',
			8  => 'deleteOwnPost',
			9  => 'deleteOwnThread',
			12 => 'viewAttachment',
			13 => 'uploadAttachment',
			15 => 'votePoll',
			19 => 'viewContent'
		];

		/** @var \XF\Import\DataHelper\Permission $permHelper */
		$permHelper = $this->dataManager->helper('XF:Permission');

		foreach ($permissionsByUserGroup AS $oldGroupId => $permissions)
		{
			if ($oldGroupId == 3 || $oldGroupId == 4)
			{
				// treat these as guests
				continue;
			}

			if (!$newGroupId = $this->lookupId('user_group', $oldGroupId))
			{
				continue;
			}

			$permissions = intval($permissions);

			if ($this->bitwise($permissions, 0))
			{
				// viewable
				$newPermissions = [
					'general' => ['viewNode' => 'content_allow'],
					'forum' => []
				];

				foreach ($permissionMap AS $power => $permissionName)
				{
					$newPermissions['forum'][$permissionName] = ($this->bitwise($permissions, $power) ? 'content_allow' : 'reset');
				}

				$newPermissions['general']['viewNode'] = 'content_allow';
			}
			else
			{
				$newPermissions = [
					'general' => ['viewNode' => 'reset']
				];

				$permissionsGrouped = $this->getNodePermissionDefinitionsGrouped();

				foreach ($permissionsGrouped AS $permissionGroupId => $permissionDefinitions)
				{
					foreach ($permissionDefinitions AS $permissionId => $permissionDefinition)
					{
						if ($permissionDefinition['permission_type'] == 'flag')
						{
							$newPermissions[$permissionGroupId][$permissionId] = 'reset';
						}
					}
				}
			}

			// now import
			$permHelper->insertContentUserGroupPermissions('node', $newNodeId, $newGroupId, $newPermissions);
		}
	}

	protected function getNodePermissionDefinitionsGrouped()
	{
		static $permissionsGrouped = null;

		if ($permissionsGrouped === null)
		{
			$permissions = $this->db()->fetchAll("
				SELECT *
				FROM xf_permission
				WHERE permission_group_id IN('category', 'forum', 'linkForum')
			");

			foreach ($permissions AS $p)
			{
				$permissionsGrouped[$p['permission_group_id']][$p['permission_id']] = $p;
			}
		}

		return $permissionsGrouped;
	}

	// ########################### STEP: MODERATORS ###############################

	public function stepModerators(StepState $state)
	{
		$this->typeMap('node');
		$this->typeMap('user_group');

		$moderators = $this->getModerators();

		if (!$moderators)
		{
			return $state->complete();
		}

		$moderatorsGroupedByUserIdForumId = [];

		foreach ($moderators AS $moderator)
		{
			if (!array_key_exists('permissions2', $moderator))
			{
				$moderator['permissions2'] = 0;
			}

			$moderatorsGroupedByUserIdForumId[$moderator['userid']][$moderator['forumid']] = $moderator;
		}

		if ($superMods = $this->session->extra['superModerators'])
		{
			foreach ($superMods AS $oldUserId => $newUserId)
			{
				if (!isset($moderatorsGroupedByUserIdForumId[$oldUserId]))
				{
					$moderatorsGroupedByUserIdForumId[$oldUserId][-1] = [
						'userid' => $oldUserId,
						'is_super_moderator' => true,
						'forumid' => -1,
						'permissions' => -1, // all bits true
						'permissions2' => -1 // all bits true
					];
				}
			}
		}

		$this->typeMap('node');
		$this->typeMap('user_group');

		$this->lookup('user', array_keys($moderatorsGroupedByUserIdForumId));

		/** @var \XF\Import\DataHelper\Moderator $modHelper */
		$modHelper = $this->getDataHelper('XF:Moderator');

		/** @var \XF\Import\DataHelper\Permission $permissionHelper */
		$permissionHelper = $this->dataManager->helper('XF:Permission');

		foreach ($moderatorsGroupedByUserIdForumId AS $oldUserId => $forums)
		{
			if (!$newUserId = $this->lookupId('user', $oldUserId))
			{
				continue;
			}

			$globalModeratorPermissions = [];
			$inserted = false;

			if (!empty($forums[-1]['is_super_moderator']))
			{
				$permissions = $this->mapModeratorPermissions($forums[-1]['permissions'], $forums[-1]['permissions2']);
				$globalModeratorPermissions += $permissions;

				$isSuperMod = true;
			}
			else
			{
				$isSuperMod = false;
			}

			unset($forums[-1]);

			foreach ($forums AS $oldForumId => $moderator)
			{
				if (!$newNodeId = $this->lookupId('node', $oldForumId))
				{
					continue;
				}

				$permissions = $this->mapModeratorPermissions($moderator['permissions'], $moderator['permissions2'], 'content_allow');
				$globalModeratorPermissions += $permissions;

				$modHelper->importContentModerator($newUserId, 'node', $newNodeId, $permissions['forum']);
				$inserted = true;
			}

			if ($inserted)
			{
				if ($globalModeratorPermissions)
				{
					$permissionHelper->insertUserPermissions($newUserId, $globalModeratorPermissions['global']);
				}

				$modHelper->importModerator($newUserId, $isSuperMod, [4]);

				$state->imported++;
			}
		}

		return $state->complete();
	}

	protected function getModerators()
	{
		return $this->sourceDb->fetchAll("
			SELECT moderator.*, user.username,
				IF(usergroup.adminpermissions & 1, 1, 0) AS is_super_moderator
			FROM {$this->prefix}moderator AS
				moderator
			INNER JOIN {$this->prefix}user AS
				user ON (moderator.userid = user.userid)
			LEFT JOIN {$this->prefix}usergroup AS
				usergroup ON (usergroup.usergroupid = user.usergroupid)
		");
	}

	protected function mapModeratorPermissions($forumPermissionsInteger, $globalPermissionsInteger, $forumTrueValue = 'allow')
	{
		$newGlobalPermissions = $this->applyModeratorPermissionMap($globalPermissionsInteger, [
			0 => ['profilePost' => ['editAny']],
			1 => ['profilePost' => ['deleteAny', 'undelete']],
			2 => ['profilePost' => ['hardDeleteAny']],
			3 => ['profilePost' => ['approveUnapprove', 'viewDeleted', 'viewModerated']],
		]);

		$newForumPermissions = $this->applyModeratorPermissionMap($forumPermissionsInteger, [
			0  => ['forum' => ['editAnyPost']],
			1  => ['forum' => ['deleteAnyPost', 'deleteAnyThread', 'viewModerated']],
			2  => ['forum' => ['lockUnlockThread']],
			4  => ['forum' => ['manageAnyThread', 'stickUnstickThread']],
			6  => ['forum' => ['approveUnapprove', 'viewModerated']],
			17 => ['forum' => ['hardDeleteAnyPost', 'hardDeleteAnyThread']]
		], $forumTrueValue);

		$this->addDefaultModeratorPermissions($newGlobalPermissions, $newForumPermissions);

		return [
			'forum' => $newForumPermissions,
			'global' => $newGlobalPermissions
		];
	}

	protected function addDefaultModeratorPermissions(array &$generalPermissions, array &$forumPermissions)
	{
		if (!isset($forumPermissions['forum']))
		{
			$forumPermissions['forum'] = [];
		}

		$forumPermissions['forum'] += [
			'inlineMod' => 'content_allow',
			'view_deleted' => 'content_allow'
		];

		// these don't really map, so give them to mods that can delete stuff
		if (isset($forumPermissions['forum']['deleteAnyPost']))
		{
			switch ($forumPermissions['forum']['deleteAnyPost'])
			{
				case 'allow':
				case 'content_allow':
				{
					$addGeneral = [
						'viewIps'           => 'allow',
						'cleanSpam'         => 'allow',
						'bypassUserPrivacy' => 'allow'
					];

					if (!isset($generalPermissions['general']))
					{
						$generalPermissions['general'] = [];
					}

					$generalPermissions['general'] += $addGeneral;
					break;
				}
			}
		}
	}

	protected function applyModeratorPermissionMap($permissionsInteger, array $permissionsMap, $trueValue = 'allow')
	{
		$permissionsInteger = intval($permissionsInteger);
		$newPermissions = [];

		foreach ($permissionsMap AS $power => $items)
		{
			if ($this->bitwise($permissionsInteger, $power))
			{
				foreach ($items AS $groupName => $permissionNames)
				{
					foreach ($permissionNames AS $permissionName)
					{
						$newPermissions[$groupName][$permissionName] = $trueValue;
					}
				}
			}
		}

		return $newPermissions;
	}

	// ########################### STEP: THREAD PREFIXES ###############################

	public function stepThreadPrefixes(StepState $state)
	{
		// TODO: check that prefix cache and permissions are properly rebuilt in import finalization

		$prefixes = $this->sourceDb->fetchAllKeyed("
			SELECT prefix.*,
				phrase.text AS title
			FROM {$this->prefix}prefix AS
				prefix
			INNER JOIN {$this->prefix}prefixset AS
				prefixset ON (prefixset.prefixsetid = prefix.prefixsetid)
			LEFT JOIN {$this->prefix}phrase AS
				phrase ON (phrase.languageid = 0 AND phrase.varname = CONCAT('prefix_', prefix.prefixid, '_title_plain'))
			ORDER BY prefixset.displayorder, prefix.displayorder
		", 'prefixid');

		if (!$prefixes)
		{
			return $state->complete();
		}

		$this->typeMap('node');
		$userGroupList = $this->typeMap('user_group'); // use $userGroupList later

		$prefixSets = $this->sourceDb->fetchAllKeyed("
			SELECT prefixset.*, phrase.text AS title
			FROM {$this->prefix}prefixset AS
				prefixset
			LEFT JOIN {$this->prefix}phrase AS
				phrase ON (phrase.languageid = 0 AND phrase.varname = CONCAT('prefixset_', prefixset.prefixsetid, '_title'))
			ORDER BY prefixset.displayorder
		", 'prefixsetid');

		$mappedGroupIds = [];

		foreach ($prefixSets AS $oldGroupId => $prefixSet)
		{
			/** @var \XF\Import\Data\ThreadPrefixGroup $importGroup */
			$importGroup = $this->newHandler('XF:ThreadPrefixGroup');
			$importGroup->preventRetainIds();
			$importGroup->display_order = $prefixSet['displayorder'];
			$importGroup->setTitle($this->convertToUtf8($prefixSet['title']));

			if ($newGroupId = $importGroup->save($oldGroupId))
			{
				$mappedGroupIds[$oldGroupId] = $newGroupId;
			}
		}

		// stores a list of usergroup permissions for prefixes, when set
		$prefixUserGroups = $this->getPrefixUserGroups();

		// stores a list of forums to which prefixsets belong
		$prefixSetForums = $this->getPrefixSetForums();

		// stores a list of nodes to which prefix groups belong, and is built just-in-time
		$prefixNodes = [];

		foreach ($prefixes AS $oldPrefixId => $prefix)
		{
			$prefixSetId = $prefix['prefixsetid'];

			/** @var \XF\Import\Data\ThreadPrefix $importPrefix */
			$importPrefix = $this->newHandler('XF:ThreadPrefix');
			$importPrefix->preventRetainIds();

			$importPrefix->setTitle($this->convertToUtf8($prefix['title']));
			$importPrefix->display_order = $prefix['displayorder'];
			$importPrefix->prefix_group_id = $mappedGroupIds[$prefixSetId] ?: 0;

			if (empty($prefixUserGroups[$oldPrefixId]))
			{
				$importPrefix->allowed_user_group_ids = [-1];
			}
			else
			{
				$allowedGroups = [];
				foreach ($userGroupList AS $oldUserGroupId => $newUserGroupId)
				{
					if (!isset($prefixUserGroups[$oldPrefixId][$oldUserGroupId]))
					{
						$allowedGroups[] = $oldUserGroupId;
					}
				}
				if (!empty($allowedGroups))
				{
					$importPrefix->allowed_user_group_ids = $this->mapUserGroupList($allowedGroups);
				}
				else
				{
					$importPrefix->allowed_user_group_ids = [-1];
				}
			}

			if (empty($prefixNodes[$prefixSetId]) && isset($prefixSetForums[$prefixSetId]))
			{
				foreach ($prefixSetForums[$prefixSetId] AS $oldForumId)
				{
					if ($newNodeId = $this->lookupId('node', $oldForumId))
					{
						$prefixNodes[$prefixSetId][$oldForumId] = $newNodeId;
					}
				}
			}

			if (!empty($prefixNodes[$prefixSetId]))
			{
				$importPrefix->setNodes($prefixNodes[$prefixSetId]);
			}

			if ($importPrefix->save($oldPrefixId))
			{
				$state->imported++;
			}
		}

		return $state->complete();
	}

	protected function getPrefixUserGroups()
	{
		// stores specific user group permissions for each prefix, when specified
		$prefixUserGroups = [];

		try
		{
			// vB has no prefix permissions prior to 3.8, so catch the error when it comes
			foreach ($this->sourceDb->fetchAll("SELECT * FROM {$this->prefix}prefixpermission") AS $p)
			{
				$prefixUserGroups[$p['prefixid']][$p['usergroupid']] = true;
			}
		}
		catch (Exception $e) {}

		return $prefixUserGroups;
	}

	protected function getPrefixSetForums()
	{
		// stores a list of forums to which prefixsets belong
		$prefixSetForums = [];

		foreach ($this->sourceDb->fetchAll("SELECT * FROM {$this->prefix}forumprefixset") AS $f)
		{
			$prefixSetForums[$f['prefixsetid']][$f['forumid']] = $f['forumid'];
		}

		return $prefixSetForums;
	}

	// ########################### STEP: FEEDS ###############################

	public function stepFeeds(StepState $state)
	{
		$feeds = $this->getFeeds();

		if (!$feeds)
		{
			return $state->complete();
		}

		$this->lookup('node', $this->pluck($feeds, 'forumid'));
		$this->lookup('user', $this->pluck($feeds, 'userid'));
		$this->lookup('thread_prefix', $this->pluck($feeds, 'prefixid'));

		foreach ($feeds AS $feed)
		{
			if (!$newNodeId = $this->lookupId('node', $feed['forumid']))
			{
				continue;
			}

			$newUserId = $this->lookupId('user', $feed['userid'], 0);
			if ($feed['userid'] && !$newUserId)
			{
				continue;
			}

			/** @var \XF\Import\Data\Feed $import */
			$import = $this->newHandler('XF:Feed');
			$import->preventRetainIds();

			$import->bulkSet($this->mapXfKeys($feed, [
				'title',
				'frequency' => 'ttl',
				'last_fetch' => 'lastrun'
			]));

			$import->bulkSet([
				'user_id' => $newUserId,
				'node_id' => $newNodeId,
			]);

			$import->url = $this->getFeedUrl($feed['url'], $feed['port']);
			$import->prefix_id = $this->lookupId('thread_prefix', $feed['prefixid'], 0);

			$import->active = $this->bitwise($feed['options'], 0);
			$import->discussion_sticky = $this->bitwise($feed['options'], 3);
			$import->discussion_visible = !$this->bitwise($feed['options'], 9);
			$import->discussion_open = !($this->bitwise($feed['options'], 4) && $feed['topicactiondelay']);

			$import->title_template = $this->convertFeedTemplate($feed['titletemplate']);
			$import->message_template = $this->convertFeedTemplate($feed['bodytemplate']);

			if ($import->save($feed['rssfeedid']))
			{
				$state->imported++;
			}
		}

		return $state->complete();
	}

	protected function getFeeds()
	{
		return $this->sourceDb->fetchAll("
			SELECT *
			FROM {$this->prefix}rssfeed
		");
	}

	/**
	 * Attempts to insert a port number into a URL if it's specified and necessary
	 *
	 * @param $url
	 * @param $port
	 *
	 * @return mixed
	 */
	protected function getFeedUrl($url, $port)
	{
		if (!empty($port))
		{
			if ($parts = @parse_url($url))
			{
				if ($port == 80 && $parts['scheme'] == 'http')
				{
					return $url;
				}

				if ($port == 443 && $parts['scheme'] == 'https')
				{
					return $url;
				}

				if (!isset($parts['port']))
				{
					return str_replace(
						"//{$parts['host']}",
						"//{$parts['host']}:{$port}", $url);
				}
			}
		}

		return $url;
	}

	protected function convertFeedTemplate($text)
	{
		$replace = [
			'{feed:title}' => '{title}',
			'{feed:description}' => '{content}',
			'{feed:author}' => '{author}',
			'{feed:link}' => '{link}'
		];

		return str_replace(array_keys($replace), $replace, $text);
	}

	// ########################### STEP: THREADS ###############################

	public function getStepEndThreads()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(threadid) FROM {$this->prefix}thread") ?: 0;
	}

	public function stepThreads(StepState $state, array $stepConfig, $maxTime, $limit = 1000)
	{
		$timer = new \XF\Timer($maxTime);

		$threads = $this->getThreads($state->startAfter, $state->end, $limit);

		if (!$threads)
		{
			return $state->complete();
		}

		$subs = $this->getThreadSubscriptions(array_keys($threads));
		$threadSubscriptions = [];
		foreach ($subs AS $sub)
		{
			$threadSubscriptions[$sub['threadid']][$sub['userid']] = $sub['emailupdate'];
		}

		$this->lookup('user', $this->pluck($threads, 'postuserid') + $this->pluck($subs, 'userid'));
		$this->typeMap('node');
		$this->typeMap('thread_prefix');

		foreach ($threads AS $oldThreadId => $thread)
		{
			$state->startAfter = $oldThreadId;

			if (!$nodeId = $this->lookupId('node', $thread['forumid']))
			{
				continue;
			}

			$newPrefixId = isset($thread['prefixid']) // handle 3.6 not having prefixes
				? $this->lookupId('thread_prefix', $thread['prefixid'], 0)
				: 0;

			/** @var \XF\Import\Data\Thread $import */
			$import = $this->newHandler('XF:Thread');

			$import->bulkSet($this->mapXfKeys($thread, [
				'reply_count' => 'replycount',
				'view_count' => 'views',
				'sticky',
				'last_post_date' => 'lastpost',
				'discussion_open' => 'open',
				'post_date' => 'dateline'
			]));

			$import->bulkSet([
				'title' => $thread['title'],
				'node_id' => $nodeId,
				'prefix_id' => $newPrefixId,
				'user_id' => $this->lookupId('user', $thread['postuserid'], 0),
				'username' => $thread['postusername'],
				'discussion_state' => $this->decodeVisibleState($thread['visible'])
			]);

			if (isset($threadSubscriptions[$oldThreadId]))
			{
				foreach ($threadSubscriptions[$oldThreadId] AS $oldUserId => $emailUpdate)
				{
					if ($newUserId = $this->lookupId('user', $oldUserId))
					{
						$import->addThreadWatcher($newUserId, $emailUpdate);
					}
				}
			}

			if ($newThreadId = $import->save($oldThreadId))
			{
				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	protected function getThreads($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT thread.*,
				IF(user.username IS NULL, thread.postusername, user.username) AS postusername
			FROM {$this->prefix}thread AS
				thread
			LEFT JOIN {$this->prefix}user AS
				user ON(user.userid = thread.postuserid)
			INNER JOIN {$this->prefix}forum AS
				forum ON(forum.forumid = thread.forumid AND forum.link = '' AND forum.options & 4)
			WHERE thread.threadid > ? AND thread.threadid <= ?
			AND thread.open <> 10
			ORDER BY thread.threadid
			LIMIT {$limit}
		", 'threadid', [$startAfter, $end]);
	}

	protected function getThreadSubscriptions(array $oldThreadIds)
	{
		$oldThreadIds = $this->sourceDb->quote($oldThreadIds);

		return $this->sourceDb->fetchPairs("
			SELECT threadid, userid, emailupdate
			FROM {$this->prefix}subscribethread
			WHERE threadid IN({$oldThreadIds})
		");
	}

	// ########################### STEP: POSTS ###############################

	public function getStepEndPosts()
	{
		return $this->getStepEndThreads();
	}

	public function stepPosts(StepState $state, array $stepConfig, $maxTime, $limit = 50)
	{
		if ($state->startAfter == 0)
		{
			// just in case these are lying around, get rid of them before continue...
			unset($state->extra['postDateStart'], $state->extra['postPosition']);
		}

		$timer = new \XF\Timer($maxTime);

		$threadIds = $this->getThreadIds($state->startAfter, $state->end, $limit);

		if (!$threadIds)
		{
			return $state->complete();
		}

		$this->lookup('thread', $threadIds);

		foreach ($threadIds AS $oldThreadId)
		{
			if (!$newThreadId = $this->lookupId('thread', $oldThreadId))
			{
				$state = $this->setStateNextThread($state, $oldThreadId);
				continue;
			}

			$total = 0;

			if (empty($state->extra['postDateStart']))
			{
				// starting a new thread, so initialize the variables that tell us we are mid-thread
				$state->extra['postDateStart'] = 0;
				$state->extra['postPosition'] = 0;
			}

			$posts = $this->getPosts($oldThreadId, $state->extra['postDateStart']);

			if (!$posts)
			{
				$state = $this->setStateNextThread($state, $oldThreadId);
				continue;
			}

			$this->lookup('user', $this->pluck($posts, ['userid', 'edituserid']));

			foreach ($posts AS $i => $post)
			{
				$state->extra['postDateStart'] = $post['dateline'];

				$message = $this->getPostMessage($post['title'], $post['pagetext']);

				/** @var \XF\Import\Data\Post $import */
				$import = $this->newHandler('XF:Post');

				$import->bulkSet([
					'thread_id' => $newThreadId,
					'post_date' => $post['dateline'],
					'user_id' => $this->lookupId('user', $post['userid'], 0),
					'username' => $post['username'],
					'message' => $message,
					'message_state' => $this->decodeVisibleState($post['visible']),
					'last_edit_date' => $post['editdate'] ?: 0,
					'last_edit_user_id' => $post['edituserid'] ? $this->lookupId('user', $post['edituserid'], 0) : 0,
					'edit_count' => $post['editdate'] ? 1 : 0,
					'position' => $state->extra['postPosition'],
				]);

				$import->setLoggedIp($post['ipaddress']);

				if ($import->message_state == 'visible')
				{
					$state->extra['postPosition']++;
				}

				if ($newId = $import->save($post['postid']))
				{
					$state->imported++;
					$total++;
				}

				/*
				 * Only allow the timer to break the loop if the next post in the array
				 * has a dateline different from that of the current post, because when we
				 * pick up the loop again, we will only fetch posts that have a date that
				 * is greater than the current post, so in the event that the next post
				 * has a dateline that is the same as the current one, it would otherwise
				 * be omitted.
				 */
				$nextIndex = $i + 1;
				$next = isset($posts[$nextIndex]) ? $posts[$nextIndex] : null;

				if ($next && $next['dateline'] != $post['dateline'] && $timer->limitExceeded())
				{
					break 2; // end both the post loop, AND the thread loop
				}
			}

			$state = $this->setStateNextThread($state, $oldThreadId);

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	protected function getThreadIds($startAfter, $end, $threadLimit)
	{
		return $this->sourceDb->fetchAllColumn("
			SELECT threadid
			FROM {$this->prefix}thread
			WHERE threadid > ? AND threadid <= ?
			AND open <> 10
			ORDER BY threadid
			LIMIT {$threadLimit}
		", [$startAfter, $end]);
	}

	protected function getPosts($threadId, $startDate)
	{
		return $this->sourceDb->fetchAll("
			SELECT post.*,
				IF(user.username IS NULL, post.username, user.username) AS username,
				editlog.dateline AS editdate,
				editlog.userid AS edituserid
			FROM {$this->prefix}post AS
				post
			LEFT JOIN {$this->prefix}user AS
				user ON(user.userid = post.userid)
			LEFT JOIN {$this->prefix}editlog AS
				editlog ON(editlog.postid = post.postid)
			WHERE post.threadid = ?
			AND post.dateline > ?
			ORDER BY post.dateline
		", [$threadId, $startDate]);
	}

	protected function getPostMessage($title, $message)
	{
		if ($title !== '')
		{
			$titleRegex = '/^(re:\s*)?' . preg_quote($title, '/') . '$/i';

			if (!preg_match($titleRegex, $title))
			{
				$message = "[b]{$title}[/b]\n\n" . ltrim($message);
			}
		}

		return $this->rewriteQuotes($message);
	}

	protected function setStateNextThread(StepState $state, $threadId)
	{
		// move on to the next thread
		$state->startAfter = $threadId;

		// we've reached the end of a thread, so reset the variables that tell us we are mid-thread
		$state->extra['postDateStart'] = 0;
		$state->extra['postPosition'] = 0;

		return $state;
	}

	protected function rewriteQuotes($text, $extraQuoteEqualsPatterns = [])
	{
		$quoteEqualsPatterns = [
			'/\[quote=("|\'|)(?P<username>[^;\n\]]*);\s*(?P<postid>\d+)\s*\1\]/siU' => function ($match)
			{
				return sprintf('[QUOTE="%s, post: %d"]',
					$match['username'],
					$this->lookupId('post', $match['postid'])
				);
			}
		];

		$quoteEqualsPatterns += $extraQuoteEqualsPatterns;

		if (stripos($text, '[quote=') !== false)
		{
			foreach ($quoteEqualsPatterns AS $pattern => $callback)
			{
				if (preg_match($pattern, $text))
				{
					$text = preg_replace_callback($pattern, $callback, $text);
				}
			}
		}

		if (stripos($text, '[quote][i]Originally posted by') !== false)
		{
			$text = preg_replace('/\[quote\]\[i\]Originally posted by (?P<username>.+)\s+\[\/i\]\r?\n/siU', '[quote="$1"]', $text);
		}

		return $text;
	}

	protected function decodeVisibleState($visible)
	{
		switch ($visible)
		{
			case 0:
				return 'moderated';
			case 2:
				return 'deleted';
			default:
				return 'visible';
		}
	}

	// ########################### STEP: TAGS ###############################

	public function getStepEndContentTags()
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(threadid) FROM {$this->prefix}thread
			WHERE taglist IS NOT NULL
		") ?: 0;
	}

	public function stepContentTags(StepState $state, array $stepConfig, $maxTime, $limit = 1000)
	{
		$timer = new \XF\Timer($maxTime);

		$threads = $this->getThreadIdsWithTags($state->startAfter, $state->end, $limit);

		if (!$threads)
		{
			return $state->complete();
		}

		$this->lookup('thread', array_keys($threads));

		/** @var \XF\Import\DataHelper\Tag $tagHelper */
		$tagHelper = $this->getDataHelper('XF:Tag');

		foreach ($threads AS $oldThreadId => $threadPostDate)
		{
			$state->startAfter = $oldThreadId;

			if (!$newThreadId = $this->lookupId('thread', $oldThreadId))
			{
				continue;
			}

			$tags = $this->getThreadTags($oldThreadId);

			if (!$tags)
			{
				continue;
			}

			$this->lookup('user', $this->pluck($tags, 'userid'));

			foreach ($tags AS $tag)
			{
				$newId = $tagHelper->importTag($tag['tagtext'], 'thread', $newThreadId, [
					'add_user_id' => $this->lookupId('user', $tag['userid']),
					'add_date' => $tag['dateline'],
					'visible' => 1,
					'content_date' => $threadPostDate
				]);

				if ($newId)
				{
					$state->imported++;
				}
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	/**
	 * Returns thread IDs that have a tag list
	 *
	 * @param $startAfter
	 * @param $end
	 * @param $limit
	 *
	 * @return array
	 */
	protected function getThreadIdsWithTags($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchPairs("
			SELECT threadid, dateline
			FROM {$this->prefix}thread
			WHERE threadid > ? AND threadid <= ?
			AND taglist IS NOT NULL
			ORDER BY threadid
			LIMIT {$limit} 
		", [$startAfter, $end]);
	}

	protected function getThreadTags($threadId)
	{
		return $this->sourceDb->fetchAll("
			SELECT tagthread.*, tag.tagtext
			FROM {$this->prefix}tagthread AS
				tagthread
			INNER JOIN {$this->prefix}tag AS
				tag ON(tag.tagid = tagthread.tagid)
			WHERE tagthread.threadid = ?
		", $threadId);
	}

	// ########################### STEP: POST EDIT HISTORY ###############################

	public function getStepEndPostEditHistory()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(postid) FROM {$this->prefix}postedithistory") ?: 0;
	}

	public function stepPostEditHistory(StepState $state, array $stepConfig, $maxTime, $limit = 100)
	{
		$timer = new \XF\Timer($maxTime);

		$postIds = $this->getPostEditHistoryPostIds($state->startAfter, $state->end, $limit);

		if (!$postIds)
		{
			return $state->complete();
		}

		$edits = $this->getPostEditHistoryEdits($postIds);

		$this->lookup('post', $postIds);
		$this->lookup('user', $this->pluck($edits, 'userid'));

		$orderedEdits = [];

		foreach ($edits AS $edit)
		{
			$orderedEdits[$edit['postid']][$edit['postedithistoryid']] = $edit;
		}

		foreach ($orderedEdits AS $oldPostId => $edits)
		{
			$state->startAfter = $oldPostId;

			if (!$newPostId = $this->lookupId('post', $oldPostId))
			{
				continue;
			}

			$messageText = false;

			foreach ($edits AS $editHistoryId => $edit)
			{
				if ($messageText !== false && $messageText !== '')
				{
					/** @var \XF\Import\Data\EditHistory $import */
					$import = $this->newHandler('XF:EditHistory');
					$import->bulkSet([
						'content_type' => 'post',
						'content_id' => $newPostId,
						'edit_user_id' => $this->lookupId('user', $edit['userid'], 0),
						'edit_date' => $edit['dateline'],
						'old_text' => $messageText
					]);

					if ($newId = $import->save($editHistoryId))
					{
						$state->imported++;
					}
				}

				$messageText = $edit['pagetext'];
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	protected function getPostEditHistoryPostIds($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchAllColumn("
			SELECT DISTINCT postid
			FROM {$this->prefix}postedithistory
			WHERE postid > ? AND postid <= ?
			ORDER BY postid
			LIMIT {$limit}
		", [$startAfter, $end]);
	}

	protected function getPostEditHistoryEdits(array $postIds)
	{
		$postIds = $this->sourceDb->quote($postIds);

		return $this->sourceDb->fetchAll("
			SELECT *
			FROM {$this->prefix}postedithistory
			WHERE postid IN ({$postIds})
			ORDER BY postedithistoryid
		");
	}

	// ########################### STEP: POLLS ###############################

	public function getStepEndPolls()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(pollid) FROM {$this->prefix}poll") ?: 0;
	}

	public function stepPolls(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		$polls = $this->sourceDb->fetchAllKeyed("
			SELECT poll.*, thread.threadid
			FROM {$this->prefix}poll AS
				poll
			INNER JOIN {$this->prefix}thread AS
				thread ON(thread.pollid = poll.pollid AND thread.open <> 10)
			WHERE poll.pollid > ? AND poll.pollid <= ?
			ORDER BY poll.pollid
			LIMIT {$limit}
		", 'pollid', [$state->startAfter, $state->end]);

		if (!$polls)
		{
			return $state->complete();
		}

		$pollsCompleted = [];

		$this->lookup('thread', $this->pluck($polls, 'threadid'));

		foreach ($polls AS $oldId => $poll)
		{
			$state->startAfter = $oldId;

			if (!$newThreadId = $this->lookupId('thread', $poll['threadid']))
			{
				continue;
			}

			if (array_key_exists($oldId, $pollsCompleted))
			{
				// poll id in the thread table isn't unique, so use this to avoid duplication
				continue;
			}

			$pollsCompleted[$oldId] = true;

			/** @var \XF\Import\Data\Poll $import */
			$import = $this->newHandler('XF:Poll');
			$import->bulkSet([
				'content_type' => 'thread',
				'content_id' => $newThreadId,
				'question' => $poll['question'],
				'public_votes' => $poll['public'],
				'max_votes' => $poll['multiple'] ? 0 : 1,
				'close_date' => ($poll['timeout'] ? $poll['dateline'] + 86400 * $poll['timeout'] : 0),
			]);

			$responses = explode('|||', $this->convertToUtf8($poll['options']));

			$importResponses = [];

			foreach ($responses AS $i => $responseText)
			{
				/** @var \XF\Import\Data\PollResponse $importResponse */
				$importResponse = $this->newHandler('XF:PollResponse');
				$importResponse->preventRetainIds();
				$importResponse->response = $responseText;

				$importResponses[$i] = $importResponse;

				$import->addResponse($i, $importResponse);
			}

			$votes = $this->sourceDb->fetchAll("
				SELECT userid, votedate, voteoption
				FROM {$this->prefix}pollvote
				WHERE pollid = ?
			", $oldId);

			$this->lookup('user', $this->pluck($votes, 'userid'));

			foreach ($votes AS $vote)
			{
				if (!$voteUserId = $this->lookupId('user', $vote['userid']))
				{
					continue;
				}

				$voteOption = $vote['voteoption'] - 1;

				if (!array_key_exists($voteOption, $importResponses))
				{
					continue;
				}

				$importResponses[$voteOption]->addVote($voteUserId, $vote['votedate']);
			}

			if ($newId = $import->save($oldId))
			{
				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	// ########################### STEP: ATTACHMENTS ###############################

	public function getStepEndAttachments()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(attachmentid) FROM {$this->prefix}attachment") ?: 0;
	}

	public function stepAttachments(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		if (isset($stepConfig['skip']))
		{
			return $state->complete();
		}

		$timer = new \XF\Timer($maxTime);

		$attachments = $this->getAttachments($state->startAfter, $state->end, $limit);

		if (!$attachments)
		{
			return $state->complete();
		}

		$attachments = $this->getAttachmentsGroupedByFile($attachments);

		if (!$attachments)
		{
			return $state->complete();
		}

		foreach ($attachments AS $fileDataId => $attachmentsForFile)
		{
			$state->startAfter = $fileDataId;

			foreach ($attachmentsForFile AS $i => $attachment)
			{
				if (!$newPostId = $this->lookupId('post', $attachment['postid']))
				{
					continue;
				}

				if ($stepConfig['path'])
				{
					// get the original attachment file
					$attachTempFile = $this->getAttachmentFilePath($stepConfig['path'], $attachment);

					if (!file_exists($attachTempFile))
					{
						continue;
					}
				}
				else
				{
					if (!$fileData = $this->getAttachmentFileData($fileDataId))
					{
						continue;
					}

					$attachTempFile = \XF\Util\File::getTempFile();
					\XF\Util\File::writeFile($attachTempFile, $fileData);
				}

				/** @var \XF\Import\Data\Attachment $import */
				$import = $this->newHandler('XF:Attachment');
				$import->bulkSet([
					'content_type' => 'post',
					'content_id'   => $newPostId,
					'attach_date'  => $attachment['dateline'],
					'view_count'   => $attachment['counter'],
					'unassociated' => false
				]);

				$import->setDataUserId($this->lookupId('user', $attachment['userid']));
				$import->setSourceFile($attachTempFile, $attachment['filename']);
				$import->setContainerCallback([$this, 'rewriteEmbeddedAttachments']);

				if ($newId = $import->save($attachment['attachmentid']))
				{
					$state->imported++;
				}
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		\XF\Util\File::cleanUpTempFiles();

		return $state->resumeIfNeeded();
	}

	protected function getAttachments($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchAll("
			SELECT attachmentid, userid, dateline, filename, counter, postid
			FROM {$this->prefix}attachment
			WHERE attachmentid > ? AND attachmentid <= ?
			AND visible = 1
			ORDER BY attachmentid
			LIMIT {$limit}
		", [$startAfter, $end]);
	}

	/**
	 * Returns an array of attachments grouped by the file they reference, which is only really useful for vB4+,
	 * when multiple attachments can reference a single filedata item. This prevents us from timing out the import
	 * in the middle of a group of attachments that all use the same filedataid, which is the method by which we
	 * increment our $state->startAfter property.
	 *
	 * Note: this method is also responsible for doing user and post lookups on the fetched user and post ids.
	 *
	 * @param array $attachments
	 *
	 * @return array [$fileDataId][$attachmentId] = $attachment
	 */
	protected function getAttachmentsGroupedByFile(array $attachments)
	{
		$this->lookup('user', $this->pluck($attachments, 'userid'));
		$this->lookup('post', $this->pluck($attachments, 'postid'));

		$grouped = [];

		foreach ($attachments AS $a)
		{
			$grouped[$a['attachmentid']][$a['attachmentid']] = $a;
		}

		return $grouped;
	}

	protected function getAttachmentFilePath($sourcePath, array $attachment)
	{
		return $sourcePath
			. '/' . implode('/', str_split($attachment['userid']))
			. '/' . $attachment['attachmentid'] . '.attach';
	}

	protected function getAttachmentFileData($attachmentId)
	{
		return $this->sourceDb->fetchOne("
			SELECT filedata FROM {$this->prefix}attachment
			WHERE attachmentid = ?
		", $attachmentId);
	}

	protected function getAttachmentFileName(array $attachment)
	{
		return $attachment['filename'];
	}

	public function rewriteEmbeddedAttachments(\XF\Mvc\Entity\Entity $container, \XF\Entity\Attachment $attachment, $oldId)
	{
		if (isset($container->message))
		{
			$message = $container->message;

			$message = preg_replace_callback(
				"#(\[ATTACH[^\]]*\]){$oldId}(\[/ATTACH\])#siU",
				function ($match) use ($attachment, $container)
				{
					$id = $attachment->attachment_id;

					if (isset($container->embed_metadata))
					{
						$metadata = $container->embed_metadata;
						$metadata['attachments'][$id] = $id;

						$container->embed_metadata = $metadata;
					}

					/*
					 * Note: We use '$id.vB' as the attachment id in the XenForo replacement
					 * to avoid it being replaced again if we come across an attachment whose source id
					 * is the same as this one's imported id.
					 */

					return $match[1] . $id . '.vB' . $match[2];
				},
				$message
			);

			$container->message = $message;
		}
	}

	// ########################### STEP: REPUTATION ###############################

	public function getStepEndReputation()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(reputationid) FROM {$this->prefix}reputation WHERE reputation > 0") ?: 0;
	}

	public function stepReputation(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		$reputations = $this->getReputations($state->startAfter, $state->end, $limit);

		if (!$reputations)
		{
			return $state->complete();
		}

		$this->lookup('user', $this->pluck($reputations, ['userid', 'whoadded']));
		$this->lookupReputationContent($reputations);

		foreach ($reputations AS $oldId => $reputation)
		{
			$state->startAfter = $oldId;

			if (!$newContentId = $this->lookupId($reputation['contenttype'], $reputation['contentid']))
			{
				continue;
			}

			if (!$likeUserId = $this->lookupId('user', $reputation['whoadded']))
			{
				continue;
			}

			$contentUserId = $this->lookupId('user', $reputation['userid']);

			/** @var \XF\Import\Data\LikedContent $import */
			$import = $this->newHandler('XF:LikedContent');
			$import->bulkSet([
				'content_type' => $reputation['contenttype'],
				'content_id' => $newContentId,
				'content_user_id' => $contentUserId,
				'like_user_id' => $likeUserId,
				'like_date' => $reputation['dateline'],
				'is_counted' => 1,
			]);

			if ($newId = $import->save($oldId))
			{
				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	protected function getReputations($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT *,
				postid AS contentid,
				'post' AS contenttype
			FROM {$this->prefix}reputation
			WHERE reputationid > ? AND reputationid <= ?
			AND reputation > 0
			ORDER BY reputationid
			LIMIT {$limit} 
		", 'reputationid', [$startAfter, $end]);
	}

	protected function lookupReputationContent(array $reputations)
	{
		$this->lookup('post', $this->pluck($reputations, 'contentid'));
	}

	// ########################### STEP: INFRACTIONS ###############################

	public function getStepEndInfractions()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(infractionid) FROM {$this->prefix}infraction") ?: 0;
	}

	public function stepInfractions(StepState $state, array $stepConfig, $maxTime, $limit = 100)
	{
		$timer = new \XF\Timer($maxTime);

		$infractions = $this->getInfractions($state->startAfter, $state->end, $limit);

		if (!$infractions)
		{
			return $state->complete();
		}

		$this->lookup('post', $this->pluck($infractions, 'postid'));
		$this->lookup('user', $this->pluck($infractions, ['userid', 'whoadded']));

		foreach ($infractions AS $oldId => $infraction)
		{
			$state->startAfter = $oldId;

			if ($infraction['postid'])
			{
				$contentType = 'post';

				if (!$newPostId = $this->lookupId('post', $infraction['postid']))
				{
					continue;
				}
			}
			else
			{
				$contentType = 'user';
				$newPostId = 0;
			}

			if (!$newUserId = $this->lookupId('user', $infraction['userid']))
			{
				continue;
			}

			/** @var \XF\Import\Data\Warning $import */
			$import = $this->newHandler('XF:Warning');
			$import->bulkSet($this->mapXfKeys($infraction, [
				'warning_date' => 'dateline',
				'title',
				'notes' => 'note',
				'points',
			]));
			$import->bulkSet([
				'user_id' => $newUserId,
				'warning_definition_id' => 0,
				'warning_user_id' => $this->lookupId('user', $infraction['whoadded'], 0),
				'expiry_date' => $infraction['action'] == 2 ? $infraction['dateline'] : $infraction['expires'],
				'is_expired' => !empty($infraction['action']) ? 1 : 0,
				'extra_user_group_ids' => []
			]);

			if ($infraction['postid'])
			{
				// content type: post
				$import->bulkSet([
					'content_type' => $contentType,
					'content_id' => $newPostId,
					'content_title' => $infraction['thread_title'],
				]);
			}
			else
			{
				// content type: user
				$import->bulkSet([
					'content_type' => $contentType,
					'content_id' => $newUserId,
					'content_title' => $infraction['username'],
				]);
			}

			if ($newId = $import->save($oldId))
			{
				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	protected function getInfractions($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT infraction.*,
				user.username AS username,
				COALESCE(thread.title, '') AS thread_title,
				IF (phrase.text IS NULL, infraction.customreason, COALESCE(phrase.text, '')) AS title
			FROM {$this->prefix}infraction AS
				infraction
			INNER JOIN {$this->prefix}user AS
				user ON(user.userid = infraction.userid)
			LEFT JOIN {$this->prefix}post AS
				post ON(post.postid = infraction.postid)
			LEFT JOIN {$this->prefix}thread AS
				thread ON(thread.threadid = post.postid)
			LEFT JOIN {$this->prefix}phrase AS
				phrase ON(phrase.varname = CONCAT('infractionlevel', infraction.infractionlevelid, '_title')
					AND phrase.languageid = 0)
			WHERE infraction.infractionid > ? AND infraction.infractionid <= ?
			ORDER BY infraction.infractionid
			LIMIT {$limit}
		", 'infractionid', [$startAfter, $end]);
	}

	// ########################### STEP: CAPTCHA QUESTIONS ###############################

	public function stepCaptchaQuestions(StepState $state)
	{
		$records = $this->sourceDb->fetchAll("
			SELECT
				question.questionid,
				phrase.text AS question_text,
				answer.answerid, answer.answer AS answer_text
			FROM {$this->prefix}hvanswer AS
				answer
			INNER JOIN {$this->prefix}hvquestion AS
				question ON(question.questionid = answer.questionid)
			LEFT JOIN {$this->prefix}phrase AS
				phrase ON(phrase.varname = CONCAT('question', question.questionid) AND phrase.fieldname = 'hvquestion' AND phrase.languageid = 0)
			ORDER BY question.questionid
		");

		$questions = [];
		$answers = [];

		foreach ($records AS $answer)
		{
			$questions[$answer['questionid']] = $answer['question_text'];

			$answers[$answer['questionid']][$answer['answerid']] = $this->convertToUtf8($answer['answer_text']);
		}

		foreach ($questions AS $oldQuestionId => $questionText)
		{
			/** @var \XF\Import\Data\CaptchaQuestion $import */
			$import = $this->newHandler('XF:CaptchaQuestion');
			$import->question = $questionText;
			$import->active = 1;
			$import->answers = $answers[$oldQuestionId];

			if ($newQuestionId = $import->save($oldQuestionId))
			{
				$state->imported++;
			}
		}

		return $state->complete();
	}

	// ########################### STEP: FAQ PAGES ###############################

	public function stepFaq(StepState $state, array $stepConfig)
	{
		$faqs = $this->sourceDb->fetchAll("
			SELECT faq.*,
				p1.text AS title,
				p2.text AS html
			FROM {$this->prefix}faq AS
				faq
			INNER JOIN {$this->prefix}phrase AS
				p1 ON(p1.varname = CONCAT(faq.faqname, '_gfaqtitle') AND p1.languageid = 0)
			INNER JOIN {$this->prefix}phrase AS 
				p2 ON(p2.varname = CONCAT(faq.faqname, '_gfaqtext') AND p2.languageid = 0)
			WHERE faq.volatile = 0
			ORDER BY faq.displayorder
		");

		if ($stepConfig['import_as'] == 'help')
		{
			$state->imported = $this->importFaqAsHelp($faqs);
		}
		else
		{
			$faqTree = $this->prepareFaqTree($faqs);

			while (!empty($faqTree))
			{
				$state->imported += $this->importFaqTreeAsPages($faqTree,
					array_keys($faqTree)[0],
					$stepConfig['parent_node_id']);
			}
		}

		return $state->complete();
	}

	protected function prepareFaqTree(array $faqs)
	{
		$faqTree = [];

		foreach ($faqs AS $faq)
		{
			$faqTree[$faq['faqparent']][$faq['faqname']] = $faq;
		}

		return $faqTree;
	}

	protected function importFaqTreeAsPages(array &$faqTree, $oldParentId = 'faqroot', $newParentId = 0)
	{
		static $existingNodeNames = null;

		if (!isset($faqTree[$oldParentId]))
		{
			return 0;
		}

		if ($existingNodeNames === null)
		{
			$existingNodeNames = $this->db()->fetchPairs("
				SELECT node_name, 1
				FROM xf_node
				WHERE node_name IS NOT NULL");
		}

		$total = 0;

		foreach ($faqTree[$oldParentId] AS $faqName => $faq)
		{
			/** @var \XF\Import\Data\Page $pageImport */
			$pageImport = $this->newHandler('XF:Page');
			$pageImport->setContent($this->convertToUtf8($faq['html']));
			$pageImport->list_siblings = true;
			$pageImport->list_children = true;

			/** @var \XF\Import\Data\Node $import */
			$import = $this->newHandler('XF:Node');
			$import->preventRetainIds();

			$import->bulkSet([
				'parent_node_id' => $newParentId,
				'display_order' => $faq['displayorder'],
				'title' => $faq['title'],
			]);

			// node_name maxLength from \XF\Entity\Node
			$import->node_name = $this->convertToUniqueId($faqName, $existingNodeNames, 50);

			$import->setType('Page', $pageImport);

			if ($newNodeId = $import->save($faqName))
			{
				$total++;
				$total += $this->importFaqTreeAsPages($faqTree, $faqName, $newNodeId);
			}
		}

		unset($faqTree[$oldParentId]);

		return $total;
	}

	protected function importFaqAsHelp(array $faqs)
	{
		$existingHelpIds = $this->db()->fetchPairs("
			SELECT page_id, page_name
			FROM xf_help_page
		");

		$existingHelpNames = array_flip($existingHelpIds);

		$total = 0;

		foreach ($faqs AS $faq)
		{
			$newHelpId = $this->convertToUniqueId($faq['faqname'], $existingHelpIds, 50);
			$newHelpName = $this->convertToUniqueId($newHelpId, $existingHelpNames, 50);

			/** @var \XF\Import\Data\HelpPage $import */
			$import = $this->newHandler('XF:HelpPage');
			$import->preventRetainIds();

			$import->bulkSet([
				'page_id'   => $newHelpId,
				'page_name' => $newHelpName,
				'display_order' => $faq['displayorder']
			]);

			$import->setTitle($this->convertToUtf8($faq['title']));
			$import->setContent($this->convertToUtf8($faq['html']));

			if ($newId = $import->save($faq['faqname']))
			{
				$total++;
			}
		}

		return $total;
	}

	// ########################### STEP: ANNOUNCEMENTS ###############################

	public function stepAnnouncements(StepState $state)
	{
		$announcements = $this->getAnnouncements();

		if ($announcements)
		{
			$this->lookup('node', $this->pluck($announcements, 'forumid'));
			$this->lookup('user', $this->pluck($announcements, 'userid'));

			foreach ($announcements AS $announcement)
			{
				$hasBbCode = $this->bitwise($announcement['announcementoptions'], 0);
				$hasHtml = $this->bitwise($announcement['announcementoptions'], 1);

				/** @var \XF\Import\Data\Notice $import */
				$import = $this->newHandler('XF:Notice');
				$import->preventRetainIds();

				$import->title = $announcement['title'];
				$import->message = $this->parseAnnouncementForNotice($announcement['pagetext'], $hasBbCode, $hasHtml);
				$import->notice_type = 'block';
				$import->display_style = 'accent';
				$import->active = ($announcement['enddate'] > \XF::$time);

				$pageCriteria = [
					'after'  => $this->convertToDateCriteria($announcement['startdate']),
					'before' => $this->convertToDateCriteria($announcement['enddate'])
				];

				if ($announcement['forumid'] > 0)
				{
					$pageCriteria['nodes'] = [
						'node_ids' => [$this->lookupId('node', $announcement['forumid'])],
						'node_only' => 0
					];
				}

				$import->setPageCriteria($pageCriteria);

				if ($newId = $import->save(false))
				{
					$state->imported++;
				}
			}
		}

		return $state->complete();
	}

	protected function getAnnouncements()
	{
		return $this->sourceDb->fetchAll("
			SELECT announcement.*, user.username
			FROM {$this->prefix}announcement AS
				announcement
			LEFT JOIN {$this->prefix}user AS
				user ON(user.userid = announcement.userid)
			ORDER BY announcementid
		");
	}

	protected function parseAnnouncementForNotice($message, $hasBbCode = false, $hasHtml = false)
	{
		if ($hasHtml == false)
		{
			if ($hasBbCode)
			{
				$message = $this->app->bbCode()->render($message, 'html', 'notice', null);
			}
			else
			{
				$message = nl2br($message);
			}
		}

		return $message;
	}

	protected function parseAnnouncementForPost($message, $hasBbCode = false, $hasHtml = false)
	{
		if ($hasHtml)
		{
			// this is largely taken from \XF\Service\Feed\Reader::getContent()

			$message = preg_replace('#<p#i', '<br>$0', $message);
			$message = preg_replace('#</p>(?!\s*<br)#i', '$0<br>', $message);

			$message = \XF\Html\Renderer\BbCode::renderFromHtml($message);
		}

		return $message;
	}

	// ########################### STEP: NOTICES ###############################

	public function stepNotices(StepState $state, array $stepConfig)
	{
		$notices = $this->sourceDb->fetchAll("
			SELECT
				notice.*,
				phrase.text AS message
			FROM {$this->prefix}notice AS
				notice
			INNER JOIN {$this->prefix}phrase AS
				phrase ON(phrase.varname = CONCAT('notice_', notice.noticeid, '_html') AND phrase.languageid = 0)
			WHERE notice.title <> ?
		", 'default_guest_message');

		if (!$notices)
		{
			return $state->complete();
		}

		$this->typeMap('user_group');
		$this->typeMap('node');

		$criteria = $this->getNoticeCriteria();

		foreach ($notices AS $notice)
		{
			$oldId = $notice['noticeid'];

			/** @var \XF\Import\Data\Notice $import */
			$import = $this->newHandler('XF:Notice');
			$import->preventRetainIds();

			$import->bulkSet($this->mapXfKeys($notice, [
				'title',
				'message',
				'dismissible',
				'active',
				'display_order' => 'displayorder',
			]));

			if ($notice['persistent'])
			{
				$import->notice_type = $stepConfig['persistent_notice_type'];
			}
			else
			{
				$import->notice_type = $stepConfig['notice_type'];
			}

			// TODO: handle 'persistent', set notice_type

			if (isset($criteria[$oldId]))
			{
				$processedCriteria = $this->processNoticeCriteria($criteria[$oldId]);

				$import->setPageCriteria($processedCriteria['pageCriteria']);
				$import->setUserCriteria($processedCriteria['userCriteria']);
			}

			if ($newId = $import->save($oldId))
			{
				$state->imported++;
			}
		}

		return $state->complete();
	}

	protected function getNoticeCriteria()
	{
		$criteria = $this->sourceDb->fetchAll("
			SELECT *
			FROM {$this->prefix}noticecriteria
		");

		$criteriaByNotice = [];

		foreach ($criteria AS $c)
		{
			$criteriaByNotice[$c['noticeid']][$c['criteriaid']] = [$c['condition1'], $c['condition2'], $c['condition3']];
		}

		return $criteriaByNotice;
	}

	/**
	 * @param array $criteria [criteriaId => [conditions]]
	 *
	 * @return array [pageCriteria => [], userCriteria => []]
	 */
	protected function processNoticeCriteria(array $criteria)
	{
		$pageCriteria = [];
		$userCriteria = [];

		// handle date/time first
		if (array_key_exists('is_date', $criteria) && array_key_exists('is_time', $criteria))
		{
			list($dmY, $dateUtc) = $criteria['is_date'];
			list($afterTime, $beforeTime, $timeUtc) = $criteria['is_time'];
			list($afterHh, $afterMm) = explode(':', $afterTime);
			list($beforeHh, $beforeMm) = explode(':', $beforeTime);

			// set date and time
			$pageCriteria['after']['ymd'] = $this->convertDmyToYmd($dmY);
			$pageCriteria['after']['hh'] = $afterHh;
			$pageCriteria['after']['mm'] = $afterMm;
			$this->setCriteriaTimeZone($pageCriteria['after'], $timeUtc);

			$pageCriteria['before']['ymd'] = $this->convertDmyToYmd($dmY);
			$pageCriteria['before']['hh'] = $beforeHh;
			$pageCriteria['before']['mm'] = $beforeMm;
			$this->setCriteriaTimeZone($pageCriteria['before'], $timeUtc);
		}
		else if (array_key_exists('is_date', $criteria))
		{
			list($dmY, $dateUtc) = $criteria['is_date'];

			// set date only
			$pageCriteria['after']['ymd'] = $this->convertDmyToYmd($dmY);
			$this->setCriteriaTimeZone($pageCriteria['after'], $dateUtc);

			$pageCriteria['before']['ymd'] = $this->convertDmyToYmd($dmY, 'P1D');
			$this->setCriteriaTimeZone($pageCriteria['before'], $dateUtc);
		}
		else if (array_key_exists('is_time', $criteria))
		{
			list($afterTime, $beforeTime, $timeUtc) = $criteria['is_time'];
			list($afterHh, $afterMm) = explode(':', $afterTime);
			list($beforeHh, $beforeMm) = explode(':', $beforeTime);

			// set time only
			$pageCriteria['after']['hh'] = $afterHh;
			$pageCriteria['after']['mm'] = $afterMm;
			$this->setCriteriaTimeZone($pageCriteria['after'], $timeUtc);

			$pageCriteria['before']['hh'] = $beforeHh;
			$pageCriteria['before']['mm'] = $beforeMm;
			$this->setCriteriaTimeZone($pageCriteria['before'], $timeUtc);
		}

		unset($criteria['is_date'], $criteria['is_time']);

		foreach ($criteria AS $criteriaId => $conditions)
		{
			switch ($criteriaId)
			{
				case 'in_usergroup_x':
					$userCriteria['user_groups'] = [
						'user_group_ids' => [$this->lookupId('user_group', $conditions[0])]
					];
					break;

				case 'not_in_usergroup_x':
					$userCriteria['not_user_groups'] = [
						'user_group_ids' => [$this->lookupId('user_group', $conditions[0])]
					];
					break;

				case 'browsing_forum_x':
					$pageCriteria['nodes'] = [
						'node_ids' => [$this->lookupId('node', $conditions[0])],
						'node_only' => 1
					];
					break;

				case 'browsing_forum_x_and_children':
					$pageCriteria['nodes'] = [
						'node_ids' => [$this->lookupId('node', $conditions[0])],
						'node_only' => 0
					];
					break;

				case 'no_visit_in_x_days':
					$userCriteria['inactive_days'] = [
						'days' => $conditions[0]
					];
					break;


				case 'has_x_postcount':
					$userCriteria['messages_posted'] = [
						'messages' => $conditions[0]
					];
					if (!empty($conditions[1]))
					{
						$userCriteria['messages_maximum'] = [
							'messages' => $conditions[1]
						];
					}
					break;

				case 'has_never_posted':
					$userCriteria['maximum_messages'] = [
						'messages' => 0
					];
					break;

				case 'username_is':
					$userCriteria['username'] = [
						'names' => $this->convertToUtf8($conditions[0])
					];
					break;

				case 'is_birthday':
					$userCriteria['birthday'] = true;
					break;

				case 'came_from_search_engine':
					$pageCriteria['from_search'] = true;
					break;

				// no equivalent for these, or data not imported/relevant
				case 'style_is_x':
				case 'no_posts_in_x_days':
				case 'has_x_reputation':
				case 'has_x_infraction_points':
				case 'pm_storage_x_percent_full':
				case 'in_coventry':
				case 'notice_x_not_displayed':
					break;
			}
		}

		return [
			'pageCriteria' => $pageCriteria,
			'userCriteria' => $userCriteria
		];
	}

	protected function convertDmyToYmd($dmY, $addString = '')
	{
		$dateTime = date_create_from_format('d-m-Y', $dmY);
		if ($addString)
		{
			$interval = new \DateInterval($addString);
			$dateTime->add($interval);
		}
		return $dateTime->format('Y-m-d');
	}

	protected function setCriteriaTimeZone(array &$rule, $useUtc)
	{
		if ($useUtc)
		{
			$rule['user_tz'] = 0;
			$rule['timezone'] = 'Europe/London';
		}
		else
		{
			$rule['user_tz'] = 1;
		}
	}

	// ########################### STEP: SMILIES ###############################

	public function stepSmilies(StepState $state, array $stepConfig)
	{
		$smilies = $this->sourceDb->fetchAll("
			SELECT smilie.*,
				cat.title AS category_title,
				cat.displayorder AS category_displayorder
			FROM {$this->prefix}smilie AS
				smilie
			INNER JOIN {$this->prefix}imagecategory AS
				cat ON(cat.imagecategoryid = smilie.imagecategoryid)
			ORDER BY cat.displayorder, smilie.displayorder
		");

		$categoriesImported = [];

		/** @var \XF\Import\DataHelper\Smilie $helper */
		$helper = $this->dataManager->helper('XF:Smilie');
		$helper->setImportUrl($this->baseConfig['bburl']);

		foreach ($smilies AS $smilie)
		{
			if (!isset($categoriesImported[$smilie['imagecategoryid']]))
			{
				$smilieCategory = [
					'title' => $smilie['category_title'],
					'smilie_category_id' => $smilie['imagecategoryid'],
					'display_order' => $smilie['category_displayorder']
				];

				$helper->addSmilieCategory($smilieCategory);

				$categoriesImported[$smilie['imagecategoryid']] = true;
			}

			$smilieData = [
				'title' => $smilie['title'],
				'smilie_text' => $smilie['smilietext'],
				'image_url' => $smilie['smiliepath'],
				'smilie_category_id' => $smilie['imagecategoryid'],
				'display_order' => $smilie['displayorder']
			];

			if ($helper->addSmilie($smilieData))
			{
				$state->imported++;
			}
		}

		$helper->saveXml($stepConfig['filename']);

		return $state->complete();
	}
}