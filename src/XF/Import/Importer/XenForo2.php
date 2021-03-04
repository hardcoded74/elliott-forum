<?php

namespace XF\Import\Importer;

use XF\Import\StepState;

class XenForo2 extends AbstractForumImporter
{
	/**
	 * @var \XF\Db\Mysqli\Adapter
	 */
	protected $sourceDb;

	public static function getListInfo()
	{
		return [
			'target' => 'XenForo',
			'source' => 'XenForo 2.0',
			'beta' => true
		];
	}

	protected function getBaseConfigDefault()
	{
		return [
			'db' => [
				'host' => '',
				'username' => '',
				'password' => '',
				'dbname' => '',
				'port' => 3306
			],
			'data_dir' => '',
			'internal_data_dir' => ''
		];
	}

	public function validateBaseConfig(array &$baseConfig, array &$errors)
	{
		$fullConfig = array_replace_recursive($this->getBaseConfigDefault(), $baseConfig);
		$missingFields = false;

		if ($fullConfig['db']['host'])
		{
			$validDbConnection = false;

			try
			{
				$db = new \XF\Db\Mysqli\Adapter($fullConfig['db'], false);
				$db->getConnection();
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
					$versionId = $db->fetchOne("SELECT option_value FROM xf_option WHERE option_id = 'currentVersionId'");
					if (!$versionId || intval($versionId) < 2000031)
					{
						$errors[] = \XF::phrase('you_may_only_import_from_xenforo_20');
					}
				}
				catch (\XF\Db\Exception $e)
				{
					if ($fullConfig['db']['dbname'] === '')
					{
						$errors[] = \XF::phrase('please_enter_database_name');
					}
					else
					{
						$errors[] = \XF::phrase('table_prefix_or_database_name_is_not_correct');
					}
				}
			}
		}
		else
		{
			$missingFields = true;
		}

		if ($fullConfig['data_dir'])
		{
			$data = rtrim($fullConfig['data_dir'], '/\\ ');

			if (!file_exists($data) || !is_dir($data))
			{
				$errors[] = \XF::phrase('directory_x_not_found_is_not_readable', ['dir' => $data]);
			}
			else if (!file_exists("$data/avatars") || !file_exists("$data/attachments"))
			{
				$errors[] = \XF::phrase('directory_x_does_not_contain_expected_contents', ['dir' => $data]);
			}

			$baseConfig['data_dir'] = $data; // to make sure it takes the format we expect
		}
		else
		{
			$missingFields = true;
		}

		if ($fullConfig['internal_data_dir'])
		{
			$internalData = rtrim($fullConfig['internal_data_dir'], '/\\ ');

			if (!file_exists($internalData) || !is_dir($internalData))
			{
				$errors[] = \XF::phrase('directory_x_not_found_is_not_readable', ['dir' => $internalData]);
			}
			else if (!file_exists("$internalData/install-lock.php"))
			{
				$errors[] = \XF::phrase('directory_x_does_not_contain_expected_contents', ['dir' => $internalData]);
			}

			$baseConfig['internal_data_dir'] = $internalData; // to make sure it takes the format we expect
		}
		else
		{
			$missingFields = true;
		}

		if ($missingFields)
		{
			$errors[] = \XF::phrase('please_complete_required_fields');
		}

		return $errors ? false : true;
	}

	public function renderBaseConfigOptions(array $vars)
	{
		return $this->app->templater()->renderTemplate('admin:import_config_xenforo2', $vars);
	}

	protected function getStepConfigDefault()
	{
		return [
			'users' => [
				'merge_email' => false,
				'merge_name' => false
			]
		];
	}

	public function renderStepConfigOptions(array $vars)
	{
		return $this->app->templater()->renderTemplate('admin:import_step_config_xenforo2', $vars);
	}

	public function validateStepConfig(array $steps, array &$stepConfig, array &$errors)
	{
		return true;
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
			'followIgnore' => [
				'title' => \XF::phrase('following_and_ignored_users'),
				'depends' => ['users']
			],
			'conversations' => [
				'title' => \XF::phrase('conversations'),
				'depends' => ['users']
			],
			'profilePosts' => [
				'title' => \XF::phrase('profile_posts'),
				'depends' => ['users']
			],
			'nodes' => [
				'title' => \XF::phrase('nodes')
			],
			'nodePermissions' => [
				'title' => \XF::phrase('node_permissions'),
				'depends' => ['nodes', 'users']
			],
			'moderators' => [
				'title' => \XF::phrase('moderators'),
				'depends' => ['nodes', 'users']
			],
			'watchedForums' => [
				'title' => \XF::phrase('watched_forums'),
				'depends' => ['nodes', 'users']
			],
			'threadPrefixes' => [
				'title' => \XF::phrase('thread_prefixes'),
				'depends' => ['nodes']
			],
			'threadFields' => [
				'title' => \XF::phrase('custom_thread_fields'),
				'depends' => ['nodes']
			],
			'threads' => [
				'title' => \XF::phrase('threads'),
				'depends' => ['nodes', 'threadPrefixes', 'threadFields'],
				'force' => ['posts']
			],
			'posts' => [
				'title' => \XF::phrase('posts'),
				'depends' => ['threads']
			],
			'postEditHistory' => [
				'title' => \XF::phrase('post_edit_history'),
				'depends' => ['posts']
			],
			'threadPolls' => [
				'title' => \XF::phrase('thread_polls'),
				'depends' => ['posts']
			],
			'attachments' => [
				'title' => \XF::phrase('attachments'),
				'depends' => ['posts'] // can come from conversations as well, though not required
			],
			'likes' => [
				'title' => \XF::phrase('likes'),
				'depends' => ['posts'] // can come from conversation and profile posts as well, though not required
			],
			'tags' => [
				'title' => \XF::phrase('tags'),
				'depends' => ['threads']
			],
			'warnings' => [
				'title' => \XF::phrase('warnings'),
				'depends' => ['users']
			],
		];

		// TODO: warnings: actions? change temp?, userUpgrades, reports?, tfa?, change log?, connected accounts?
		// TODO: RSS feed importer? thread prompts? forum/thread read? mod log? reports? group promotions? notices?
	}

	protected function doInitializeSource()
	{
		$this->sourceDb = new \XF\Db\Mysqli\Adapter(
			$this->baseConfig['db'],
			$this->app->config('fullUnicode')
		);
	}

	// ############################## STEP: USER GROUPS #########################

	public function stepUserGroups(StepState $state)
	{
		$groups = $this->sourceDb->fetchAll("
			SELECT *
			FROM xf_user_group
			ORDER BY user_group_id
		");

		foreach ($groups AS $group)
		{
			$oldId = $group['user_group_id'];

			$permissions = $this->loadSourcePermissions($oldId, 0);

			if ($oldId <= 4)
			{
				// Groups 1 to 4 are always mapped to existing default groups
				$this->logHandler('XF:UserGroup', $oldId, $oldId);

				if ($this->dataManager->getRetainIds())
				{
					// if we're retaining IDs, we can just copy these
					/** @var \XF\Import\DataHelper\Permission $permissionHelper */
					$permissionHelper = $this->dataManager->helper('XF:Permission');
					$permissionHelper->insertUserGroupPermissions($oldId, $permissions, true);
				}
			}
			else
			{
				$data = $this->mapKeys($group, [
					'title',
					'display_style_priority',
					'username_css',
					'user_title',
					'banner_css_class',
					'banner_text'
				]);

				/** @var \XF\Import\Data\UserGroup $import */
				$import = $this->newHandler('XF:UserGroup');
				$import->bulkSet($data);
				$import->setPermissions($permissions);
				$import->save($oldId);
			}

			$state->imported++;
		}

		return $state->complete();
	}

	// ####################### STEP: CUSTOM USER FIELDS #####################

	public function stepUserFields(StepState $state)
	{
		$userFields = $this->sourceDb->fetchAll("
			SELECT field.*,
				ptitle.phrase_text AS title,
				pdesc.phrase_text AS description
			FROM xf_user_field AS field
			INNER JOIN xf_phrase AS ptitle ON
				(ptitle.language_id = 0 AND ptitle.title = CONCAT('user_field_title.', field.field_id))
			INNER JOIN xf_phrase AS pdesc ON
				(pdesc.language_id = 0 AND pdesc.title = CONCAT('user_field_desc.', field.field_id))
		");

		$existingFields = $this->db()->fetchPairs("SELECT field_id, field_id FROM xf_user_field");

		foreach ($userFields AS $userField)
		{
			$oldId = $userField['field_id'];

			if (!empty($existingFields[$oldId]))
			{
				// don't import a field if we already have one called that - this assumes the same structure
				$this->logHandler('XF:UserField', $oldId, $oldId);
			}
			else
			{
				/** @var \XF\Import\Data\UserField $import */
				$import = $this->getHelper()->setupXfCustomFieldImport('XF:UserField', $userField);
				$import->bulkSet($this->mapKeys($userField, [
					'show_registration',
					'viewable_profile',
					'viewable_message',
				]));

				$import->save($oldId);
			}

			$state->imported++;
		}

		return $state->complete();
	}

	// ############################## STEP: USERS #############################

	public function getStepEndUsers()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(user_id) FROM xf_user") ?: 0;
	}

	public function stepUsers(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 500;
		$timer = new \XF\Timer($maxTime);

		$users = $this->sourceDb->fetchAll("
			SELECT
				user.*,
				user_option.*,
				user_privacy.*,
				user_profile.*,
				user_authenticate.scheme_class AS auth_scheme,
				user_authenticate.data AS auth_data,
				user_reject.reject_date,
				user_reject.reject_user_id,
				user_reject.reject_reason
			FROM xf_user AS user
			INNER JOIN xf_user_option AS user_option ON (user_option.user_id = user.user_id)
			INNER JOIN xf_user_privacy AS user_privacy ON (user_privacy.user_id = user.user_id)
			INNER JOIN xf_user_profile AS user_profile ON (user_profile.user_id = user.user_id)
			INNER JOIN xf_user_authenticate AS user_authenticate ON (user_authenticate.user_id = user.user_id)
			LEFT JOIN xf_user_reject AS user_reject ON (user_reject.user_id = user.user_id)
			WHERE user.user_id > ? AND user.user_id <= ?
			ORDER BY user.user_id
			LIMIT {$limit}
		", [$state->startAfter, $state->end]);
		if (!$users)
		{
			return $state->complete();
		}

		foreach ($users AS $user)
		{
			$oldId = $user['user_id'];
			$state->startAfter = $oldId;

			$import = $this->setupImportUser($user);
			if ($this->importUser($oldId, $import, $stepConfig))
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

	/**
	 * @param array $user
	 *
	 * @return \XF\Import\Data\User
	 */
	protected function setupImportUser(array $user)
	{
		/** @var \XF\Import\Data\User $import */
		$import = $this->newHandler('XF:User');

		$this->typeMap('user_group');

		$userData = $this->mapKeys($user, [
			'username',
			'email',
			'custom_title',
			'timezone',
			'visible',
			'activity_visible',
			'message_count',
			'register_date',
			'last_activity',
			'gravatar',
			'user_state',
			'is_staff',
			'secret_key'
		]);
		$import->bulkSetDirect('user', $userData);

		$import->user_group_id = $this->lookupId('user_group', $user['user_group_id'], 2);
		if ($user['secondary_group_ids'])
		{
			$import->secondary_group_ids = $this->mapUserGroupList($user['secondary_group_ids']);
		}
		$import->display_style_group_id = $this->lookupId('user_group', $user['display_style_group_id'], 2);

		if ($user['user_state'] == 'rejected')
		{
			$import->setRejectionDetails([
				'date' => $user['reject_date'],
				'user_id' => $this->lookupId('user', $user['reject_user_id'], 0),
				'reason' => $user['reject_reason']
			]);
		}

		if ($user['is_admin'])
		{
			$admin = $this->sourceDb->fetchRow("
				SELECT *
				FROM xf_admin
				WHERE user_id = ?
			", $user['user_id']);
			if ($admin)
			{
				$adminData = $this->mapKeys($admin, [
					'last_login',
					'is_super_admin'
				]);
				$adminData['extra_user_group_ids'] = $this->mapUserGroupList($admin['extra_user_group_ids']);

				$adminPerms = @unserialize($admin['permission_cache']);
				if ($adminPerms)
				{
					$adminData['permission_cache'] = $adminPerms;
				}

				$import->setAdmin($adminData);
			}
		}

		if ($user['is_banned'])
		{
			$ban = $this->sourceDb->fetchRow("
				SELECT *
				FROM xf_user_ban
				WHERE user_id = ?
			", $user['user_id']);
			if ($ban)
			{
				$banData = $this->mapKeys($ban, [
					'ban_date',
					'end_date',
					'user_reason',
					'triggered'
				]);
				$banData['ban_user_id'] = $this->lookupId('user', $ban['ban_user_id'], 0);
				$import->setBan($banData);
			}
		}

		$profileData = $this->mapKeys($user, [
			'dob_day',
			'dob_month',
			'dob_year',
			'signature',
			'website',
			'location',
			'about',
			'password_date'
		]);
		$import->bulkSetDirect('profile', $profileData);

		$customFields = $this->decodeValue($user['custom_fields'], 'serialized-array');
		if ($customFields)
		{
			$import->setCustomFields($this->mapCustomFields('user_field', $customFields));
		}

		$optionData = $this->mapKeys($user, [
			'show_dob_year',
			'show_dob_date',
			'content_show_signature',
			'receive_admin_email',
			'email_on_conversation',
			'is_discouraged',
			'creation_watch_state',
			'interaction_watch_state'
		]);
		$import->bulkSetDirect('option', $optionData);
		$import->setDirect('option', 'alert_optout', $this->decodeValue($user['alert_optout'], 'list-comma'));

		$privacyData = $this->mapKeys($user, [
			'allow_view_profile',
			'allow_post_profile',
			'allow_send_personal_conversation',
			'allow_view_identities',
			'allow_receive_news_feed'
		]);
		$import->bulkSetDirect('privacy', $privacyData);

		$import->setPasswordData($user['auth_scheme'], $this->decodeValue($user['auth_data'], 'serialized-array'));

		$userPermissions = $this->loadSourcePermissions(0, $user['user_id']);
		$import->setPermissions($userPermissions);

		return $import;
	}

	// ########################### STEP: AVATARS ###############################

	public function getStepEndAvatars()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(user_id) FROM xf_user WHERE avatar_date > 0") ?: 0;
	}

	public function stepAvatars(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 500;
		$timer = new \XF\Timer($maxTime);

		$users = $this->sourceDb->fetchAllKeyed("
			SELECT
				user.*,
				user_profile.*
			FROM xf_user AS user
			INNER JOIN xf_user_profile AS user_profile ON (user_profile.user_id = user.user_id)
			WHERE user.user_id > ? AND user.user_id <= ? AND user.avatar_date > 0
			ORDER BY user.user_id
			LIMIT {$limit}
		", 'user_id', [$state->startAfter, $state->end]);
		if (!$users)
		{
			return $state->complete();
		}

		$avatarSizeKeys = array_keys($this->app->container('avatarSizeMap'));

		$requiredKeys = $this->app->container('avatarSizeMap');
		unset($requiredKeys['h']);
		$requiredKeys = array_keys($requiredKeys);

		/** @var \XF\Import\DataHelper\Avatar $avatarHelper */
		$avatarHelper = $this->dataManager->helper('XF:Avatar');

		$mappedUserIds = $this->lookup('user', array_keys($users));

		foreach ($users AS $user)
		{
			$oldId = $user['user_id'];
			$state->startAfter = $oldId;

			$mappedUserId = $mappedUserIds[$oldId];
			if (!$mappedUserId)
			{
				continue;
			}

			$baseSourceFile = sprintf('%s/avatars/{size}/%d/%d.jpg',
				$this->baseConfig['data_dir'],
				floor($oldId / 1000),
				$oldId
			);
			$sourceFiles = [];
			foreach ($avatarSizeKeys AS $size)
			{
				$sourceFile = str_replace('{size}', $size, $baseSourceFile);
				if (file_exists($sourceFile) && is_readable($sourceFile))
				{
					$sourceFiles[$size] = str_replace('{size}', $size, $sourceFile);
				}
			}

			$isValid = true;
			foreach ($requiredKeys AS $sizeKey)
			{
				if (!isset($sourceFiles[$sizeKey]))
				{
					$isValid = false;
					break;
				}
			}

			if (!$isValid)
			{
				continue;
			}

			/** @var \XF\Entity\User|null $targetUser */
			$targetUser = $this->em()->find('XF:User', $mappedUserId, ['Profile']);
			if (!$targetUser)
			{
				continue;
			}

			if (!$avatarHelper->copyFinalAvatarFiles($sourceFiles, $targetUser))
			{
				continue;
			}

			$targetUser->fastUpdate([
				'avatar_date' => $user['avatar_date'],
				'avatar_width' => $user['avatar_width'],
				'avatar_height' => $user['avatar_height'],
				'avatar_highdpi' => $user['avatar_highdpi'],
			]);
			if ($targetUser->Profile)
			{
				$targetUser->Profile->fastUpdate([
					'avatar_crop_x' => $user['avatar_crop_x'],
					'avatar_crop_y' => $user['avatar_crop_y']
				]);
			}

			$state->imported++;

			$this->em()->detachEntity($targetUser);

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	// ###################### STEP: FOLLOW/IGNORE ####################

	public function getStepEndFollowIgnore()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(user_id) FROM xf_user") ?: 0;
	}

	public function stepFollowIgnore(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 500;
		$timer = new \XF\Timer($maxTime);

		$sourceDb = $this->sourceDb;
		$users = $sourceDb->fetchAllKeyed("
			SELECT user_id
			FROM xf_user_profile
			WHERE user_id > ? AND user_id <= ? 
				AND (following <> '' OR (ignored <> '' AND ignored <> 'a:0:{}'))
			ORDER BY user_id
			LIMIT {$limit}
		", 'user_id', [$state->startAfter, $state->end]);
		if (!$users)
		{
			return $state->complete();
		}

		$sourceUserIds = array_keys($users);
		$mapUserIds = $sourceUserIds;

		$follows = $sourceDb->fetchAll("
			SELECT user_id, follow_user_id
			FROM xf_user_follow
			WHERE user_id IN (" . $sourceDb->quote($sourceUserIds) . ")
		");
		$followMap = [];
		foreach ($follows AS $f)
		{
			$followMap[$f['user_id']][] = $f['follow_user_id'];
			$mappedUserIds[] = $f['follow_user_id'];
		}

		$ignores = $sourceDb->fetchAll("
			SELECT user_id, ignored_user_id
			FROM xf_user_ignored
			WHERE user_id IN (" . $sourceDb->quote($sourceUserIds) . ")
		");
		$ignoreMap = [];
		foreach ($ignores AS $i)
		{
			$ignoreMap[$i['user_id']][] = $i['ignored_user_id'];
			$mappedUserIds[] = $i['ignored_user_id'];
		}

		$mappedUserIds = $this->lookup('user', array_unique($mapUserIds));

		/** @var \XF\Import\DataHelper\User $userHelper */
		$userHelper = $this->dataManager->helper('XF:User');

		foreach ($users AS $user)
		{
			$oldId = $user['user_id'];
			$state->startAfter = $oldId;

			$newUserId = $mappedUserIds[$oldId];
			if (!$newUserId)
			{
				continue;
			}

			$hasData = false;

			if (!empty($followMap[$oldId]))
			{
				$newFollowIds = $this->mapIdsFromArray($followMap[$oldId], $mappedUserIds);
				$userHelper->importFollowing($newUserId, $newFollowIds);
				$hasData = true;
			}

			if (!empty($ignoreMap[$oldId]))
			{
				$newIgnoreIds = $this->mapIdsFromArray($ignoreMap[$oldId], $mappedUserIds);
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

	// ########################### STEP: CONVERSATIONS ###############################

	public function getStepEndConversations()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(conversation_id) FROM xf_conversation_master") ?: 0;
	}

	public function stepConversations(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 500;
		$timer = new \XF\Timer($maxTime);

		$conversations = $this->sourceDb->fetchAllKeyed("
			SELECT *
			FROM xf_conversation_master
			WHERE conversation_id > ? AND conversation_id <= ?
			ORDER BY conversation_id
			LIMIT {$limit}
		", 'conversation_id', [$state->startAfter, $state->end]);
		if (!$conversations)
		{
			return $state->complete();
		}

		foreach ($conversations AS $oldId => $conversation)
		{
			$state->startAfter = $oldId;

			$recipientData = $this->sourceDb->fetchAll("
				SELECT cr.*, cu.is_starred
				FROM xf_conversation_recipient AS cr
				LEFT JOIN xf_conversation_user AS cu ON (cu.owner_user_id = cr.user_id AND cu.conversation_id = cr.conversation_id)
				WHERE cr.conversation_id = ?
			", $oldId);

			$mapUserIds = [$conversation['user_id']];
			foreach ($recipientData AS $recipient)
			{
				$mapUserIds[] = $recipient['user_id'];
			}

			$this->lookup('user', $mapUserIds);

			/** @var \XF\Import\Data\ConversationMaster $import */
			$import = $this->newHandler('XF:ConversationMaster');
			$import->bulkSet($this->mapKeys($conversation, [
				'title',
				'open_invite',
				'conversation_open',
			]));

			foreach ($recipientData AS $recipient)
			{
				$recipientUserId = $this->lookupId('user', $recipient['user_id']);
				if ($recipientUserId)
				{
					$import->addRecipient($recipientUserId, $recipient['recipient_state'], [
						'last_read_date' => $recipient['last_read_date'],
						'is_starred' => $recipient['is_starred'] ? true : false
					]);
				}
			}

			$messageData = $this->sourceDb->fetchAll("
				SELECT m.*,
					ip.ip
				FROM xf_conversation_message AS m
				LEFT JOIN xf_ip AS ip ON (ip.ip_id = m.ip_id)
				WHERE m.conversation_id = ?
			", $oldId);
			foreach ($messageData AS $message)
			{
				/** @var \XF\Import\Data\ConversationMessage $importMessage */
				$importMessage = $this->newHandler('XF:ConversationMessage');
				$importMessage->bulkSet($this->mapKeys($message, [
					'message_date',
					'username',
					'message',
				]));
				$importMessage->user_id = $this->lookupId('user', $message['user_id'], 0);
				$importMessage->setLoggedIp($message['ip']);
				// attachments will come later

				$import->addMessage($message['message_id'], $importMessage);
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

	// ########################### STEP: PROFILE POSTS ###############################

	public function getStepEndProfilePosts()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(profile_post_id) FROM xf_profile_post") ?: 0;
	}

	public function stepProfilePosts(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;
		$timer = new \XF\Timer($maxTime);

		$profilePosts = $this->sourceDb->fetchAllKeyed("
			SELECT pp.*, 
				ip.ip,
				d.delete_date, d.delete_user_id, d.delete_username, d.delete_reason
			FROM xf_profile_post AS pp
			LEFT JOIN xf_ip AS ip ON (ip.ip_id = pp.ip_id)
			LEFT JOIN xf_deletion_log AS d ON (d.content_type = 'profile_post' AND d.content_id = pp.profile_post_id)
			WHERE pp.profile_post_id > ? AND pp.profile_post_id <= ?
			ORDER BY pp.profile_post_id
			LIMIT {$limit}
		", 'profile_post_id', [$state->startAfter, $state->end]);
		if (!$profilePosts)
		{
			return $state->complete();
		}

		foreach ($profilePosts AS $oldId => $profilePost)
		{
			$state->startAfter = $oldId;

			$mapUserIds = [$profilePost['user_id'], $profilePost['profile_user_id']];

			$latestCommentIds = $this->decodeValue($profilePost['latest_comment_ids'], 'serialized-array');
			if ($latestCommentIds)
			{
				$comments = $this->sourceDb->fetchAllKeyed("
					SELECT c.*, 
						ip.ip
					FROM xf_profile_post_comment AS c
					LEFT JOIN xf_ip AS ip ON (ip.ip_id = c.ip_id)
					WHERE c.profile_post_id = ?
				", 'profile_post_id', $oldId);
				foreach ($comments AS $comment)
				{
					$mapUserIds[] = $comment['user_id'];
				}
			}
			else
			{
				$comments = [];
			}

			$this->lookup('user', array_unique($mapUserIds));

			$profileUserId = $this->lookupId('user', $profilePost['profile_user_id']);
			if (!$profileUserId)
			{
				continue;
			}

			/** @var \XF\Import\Data\ProfilePost $import */
			$import = $this->newHandler('XF:ProfilePost');
			$import->bulkSet($this->mapKeys($profilePost, [
				'username',
				'post_date',
				'message',
				'message_state',
				'comment_count',
				'warning_message'
			]));
			$import->profile_user_id = $profileUserId;
			$import->user_id = $this->lookupId('user', $profilePost['user_id'], 0);
			$import->setDeletionLogData($this->extractDeletionLogData($profilePost));

			foreach ($comments AS $oldCommentId => $comment)
			{
				/** @var \XF\Import\Data\ProfilePostComment $importComment */
				$importComment = $this->newHandler('XF:ProfilePostComment');
				$importComment->bulkSet($this->mapKeys($comment, [
					'username',
					'comment_date',
					'message',
					'message_state',
					'warning_message'
				]));
				$importComment->user_id = $this->lookupId('user', $comment['user_id'], 0);
				$importComment->setLoggedIp($comment['ip']);
				$importComment->setDeletionLogData($this->extractDeletionLogData($comments));

				$import->addComment($oldCommentId, $importComment);
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

	// ########################### STEP: NODES ###############################

	public function stepNodes(StepState $state)
	{
		$sourceDb = $this->sourceDb;

		$nodes = $sourceDb->fetchAllKeyed("
			SELECT *
			FROM xf_node
			WHERE node_type_id IN ('Category', 'Forum', 'LinkForum', 'Page')
		", 'node_id');

		$pages = $sourceDb->fetchAllKeyed("
			SELECT p.* , t.template
			FROM xf_page AS p
			INNER JOIN xf_template AS t ON (t.type = 'public' AND t.title = CONCAT('_page_node.', p.node_id) AND t.style_id = 0)
			", 'node_id');

		$typeData = [
			'Category' => $sourceDb->fetchAllKeyed("SELECT * FROM xf_category", 'node_id'),
			'Forum' => $sourceDb->fetchAllKeyed("SELECT * FROM xf_forum", 'node_id'),
			'LinkForum' => $sourceDb->fetchAllKeyed("SELECT * FROM xf_link_forum", 'node_id'),
			'Page' => $pages
		];

		$nodeTreeMap = [];
		foreach ($nodes AS $nodeId => $node)
		{
			$nodeTreeMap[$node['parent_node_id']][] = $nodeId;
		}

		$state->imported = $this->importNodeTree($nodes, $typeData, $nodeTreeMap);

		return $state->complete();
	}

	protected function importNodeTree(array $nodes, array $typeData, array $tree, $oldParentId = 0, $newParentId = 0)
	{
		if (!isset($tree[$oldParentId]))
		{
			return 0;
		}

		$total = 0;

		foreach ($tree[$oldParentId] AS $oldNodeId)
		{
			$node = $nodes[$oldNodeId];
			if (!isset($typeData[$node['node_type_id']][$oldNodeId]))
			{
				continue;
			}

			$importNode = $this->setupNodeImport($node, $typeData[$node['node_type_id']][$oldNodeId], $newParentId);
			if (!$importNode)
			{
				continue;
			}

			$newNodeId = $importNode->save($oldNodeId);
			if ($newNodeId)
			{
				$total++;
				$total += $this->importNodeTree($nodes, $typeData, $tree, $oldNodeId, $newNodeId);
			}
		}

		return $total;
	}

	protected function setupNodeImport(array $node, array $typeData, $newParentId)
	{
		/** @var \XF\Import\Data\Node $importNode */
		$importNode = $this->newHandler('XF:Node');
		$importNode->bulkSet($this->mapKeys($node, [
			'title',
			'description',
			'node_name',
			'display_order',
			'display_in_list',
		]));
		$importNode->parent_node_id = $newParentId;

		switch ($node['node_type_id'])
		{
			case 'Category': $importType = $this->setupNodeCategoryImport($typeData); break;
			case 'Forum': $importType = $this->setupNodeForumImport($typeData); break;
			case 'LinkForum': $importType = $this->setupNodeLinkForumImport($typeData); break;
			case 'Page': $importType = $this->setupNodePageImport($typeData); break;
			default: $importType = null;
		}

		if (!$importType)
		{
			return null;
		}

		$importNode->setType($node['node_type_id'], $importType);

		return $importNode;
	}

	protected function setupNodeCategoryImport(array $data)
	{
		return $this->newHandler('XF:Category');
	}

	protected function setupNodeForumImport(array $data)
	{
		$handler = $this->newHandler('XF:Forum');
		$handler->bulkSet($this->mapKeys($data, [
			'discussion_count',
			'message_count',
			'moderate_threads',
			'moderate_replies',
			'allow_posting',
			'allow_poll',
			'count_messages',
			'find_new',
			// note: can't import default_prefix_id. Chicken and egg problem (need prefixes but prefixes need nodes)
			'default_sort_order',
			'default_sort_direction',
			'list_date_limit_days',
			'require_prefix',
			'allowed_watch_notifications',
			'min_tags'
		]));
		return $handler;
	}

	protected function setupNodeLinkForumImport(array $data)
	{
		$handler = $this->newHandler('XF:LinkForum');
		$handler->bulkSet($this->mapKeys($data, [
			'link_url',
			'redirect_count'
		]));
		return $handler;
	}

	protected function setupNodePageImport(array $data)
	{
		/** @var \XF\Import\Data\Page $handler */
		$handler = $this->newHandler('XF:Page');
		$handler->bulkSet($this->mapKeys($data, [
			'publish_date',
			'modified_date',
			'view_count',
			'log_visits',
			'list_siblings',
			'list_children',
			'callback_class',
			'callback_method',
			'advanced_mode'
		]));
		$handler->setContent($data['template']);
		return $handler;
	}

	// ########################### STEP: NODE PERMISSIONS ###############################

	public function stepNodePermissions(StepState $state)
	{
		$this->typeMap('user_group');
		$this->typeMap('node');

		$entries = $this->sourceDb->fetchAll("
			SELECT *
			FROM xf_permission_entry_content
			WHERE content_type = 'node'
		");

		$mapUserIds = [];
		foreach ($entries AS $entry)
		{
			if ($entry['user_id'])
			{
				$mapUserIds[] = $entry['user_id'];
			}
		}

		$mappedUserIds = $this->lookup('user', array_unique($mapUserIds));

		$groupedEntries = [];
		foreach ($entries AS $entry)
		{
			$newNodeId = $this->lookupId('node', $entry['content_id']);
			if (!$newNodeId)
			{
				continue;
			}

			if ($entry['user_id'])
			{
				$type = 'user';
				$newInsertId = $mappedUserIds[$entry['user_id']];
				if (!$newInsertId)
				{
					continue;
				}
			}
			else if ($entry['user_group_id'])
			{
				$type = 'group';
				$newInsertId = $this->lookupId('user_group', $entry['user_group_id']);
				if (!$newInsertId)
				{
					continue;
				}
			}
			else
			{
				$type = 'global';
				$newInsertId = 0;
			}

			if ($entry['permission_value'] == 'use_int')
			{
				$permValue = $entry['permission_value_int'];
			}
			else
			{
				$permValue = $entry['permission_value'];
			}

			$groupedEntries[$newNodeId][$type][$newInsertId][$entry['permission_group_id']][$entry['permission_id']] = $permValue;
		}

		/** @var \XF\Import\DataHelper\Permission $permHelper */
		$permHelper = $this->dataManager->helper('XF:Permission');
		foreach ($groupedEntries AS $nodeId => $groupedTypeEntries)
		{
			foreach ($groupedTypeEntries AS $type => $typeEntries)
			{
				foreach ($typeEntries AS $typeId => $permsGrouped)
				{
					if ($type == 'user')
					{
						$permHelper->insertContentUserPermissions('node', $nodeId, $typeId, $permsGrouped);
					}
					else if ($type == 'group')
					{
						$permHelper->insertContentUserGroupPermissions('node', $nodeId, $typeId, $permsGrouped);
					}
					else
					{
						$permHelper->insertContentGlobalPermissions('node', $nodeId, $permsGrouped);
					}

					$state->imported++;
				}
			}
		}

		return $state->complete();
	}

	// ########################### STEP: MODERATORS ###############################

	public function stepModerators(StepState $state)
	{
		$this->typeMap('node');
		$this->typeMap('user_group');

		$moderators = $this->sourceDb->fetchAllKeyed("
			SELECT *
			FROM xf_moderator
		", 'user_id');

		$nodeModerators = $this->sourceDb->fetchAll("
			SELECT *
			FROM xf_moderator_content
			WHERE content_type = 'node'
		");
		$nodeModsByUser = [];
		foreach ($nodeModerators AS $nodeModerator)
		{
			$newNodeId = $this->lookupId('node', $nodeModerator['content_id']);
			if ($newNodeId)
			{
				$nodeModsByUser[$nodeModerator['user_id']] = $newNodeId;
			}
		}

		$mappedUserIds = $this->lookup('user', array_keys($moderators));

		/** @var \XF\Import\DataHelper\Moderator $modHelper */
		$modHelper = $this->getDataHelper('XF:Moderator');

		foreach ($moderators AS $oldUserId => $moderator)
		{
			$newUserId = $mappedUserIds[$oldUserId];
			if (!$newUserId)
			{
				continue;
			}

			$extraUserGroups = $this->mapUserGroupList($moderator['extra_user_group_ids']);
			$modHelper->importModerator($newUserId, $moderator['is_super_moderator'], $extraUserGroups);
			// permissions already imported

			if (!empty($nodeModsByUser[$oldUserId]))
			{
				// node IDs were mapped above
				$modHelper->importContentModeratorsRaw($newUserId, 'node', $nodeModsByUser[$oldUserId]);
			}

			$state->imported++;
		}

		return $state->complete();
	}

	// ########################### STEP: WATCHED FORUMS ###############################

	public function getStepEndWatchedForums()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(node_id) FROM xf_node") ?: 0;
	}

	public function stepWatchedForums(StepState $state, array $stepConfig, $maxTime)
	{
		$timer = new \XF\Timer($maxTime);

		$this->typeMap('node');

		$nodeIds = $this->sourceDb->fetchAllColumn("
			SELECT node_id
			FROM xf_node
			WHERE node_id > ?
			ORDER BY node_id
		", $state->startAfter);
		if (!$nodeIds)
		{
			return $state->complete();
		}

		/** @var \XF\Import\DataHelper\Forum $forumHelper */
		$forumHelper = $this->getDataHelper('XF:Forum');

		foreach ($nodeIds AS $oldNodeId)
		{
			$state->startAfter = $oldNodeId;

			$newNodeId = $this->lookupId('node', $oldNodeId);
			if (!$newNodeId)
			{
				continue;
			}

			$watches = $this->sourceDb->fetchAllKeyed("
				SELECT *
				FROM xf_forum_watch
				WHERE node_id = ?
			", 'user_id', $oldNodeId);

			$mappedUserIds = $this->lookup('user', array_keys($watches));

			$watchData = [];
			foreach ($watches AS $oldUserId => $watch)
			{
				$newUserId = $mappedUserIds[$oldUserId];
				if (!$newUserId)
				{
					continue;
				}

				$watchData[$newUserId] = [
					'notify_on' => $watch['notify_on'],
					'send_alert' => $watch['send_alert'],
					'send_email' => $watch['send_email']
				];

				$state->imported++;
			}

			$forumHelper->importForumWatchBulk($newNodeId, $watchData);

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		return $state->resumeIfNeeded();
	}

	// ########################### STEP: THREAD PREFIXES ###############################

	public function stepThreadPrefixes(StepState $state)
	{
		$this->typeMap('node');
		$this->typeMap('user_group');

		$prefixGroups = $this->sourceDb->fetchAllKeyed("
			SELECT tpg.*,
				p.phrase_text
			FROM xf_thread_prefix_group AS tpg
			INNER JOIN xf_phrase AS p ON (p.language_id = 0 AND p.title = CONCAT('thread_prefix_group.', tpg.prefix_group_id))
		", 'prefix_group_id');
		$mappedGroupIds = [];

		foreach ($prefixGroups AS $oldGroupId => $group)
		{
			/** @var \XF\Import\Data\ThreadPrefixGroup $importGroup */
			$importGroup = $this->newHandler('XF:ThreadPrefixGroup');
			$importGroup->display_order = $group['display_order'];
			$importGroup->setTitle($group['phrase_text']);

			$newGroupId = $importGroup->save($oldGroupId);
			if ($newGroupId)
			{
				$mappedGroupIds[$oldGroupId] = $newGroupId;
			}
		}

		$prefixes = $this->sourceDb->fetchAllKeyed("
			SELECT tp.*,
				p.phrase_text
			FROM xf_thread_prefix AS tp
			INNER JOIN xf_phrase AS p ON (p.language_id = 0 AND p.title = CONCAT('thread_prefix.', tp.prefix_id))
		", 'prefix_id');

		$prefixNodeMap = [];
		$nodePrefixes = $this->sourceDb->fetchAll("
			SELECT *
			FROM xf_forum_prefix
		");
		foreach ($nodePrefixes AS $nodePrefix)
		{
			$newNodeId = $this->lookupId('node', $nodePrefix['node_id']);
			if ($newNodeId)
			{
				$prefixNodeMap[$nodePrefix['prefix_id']][] = $newNodeId;
			}
		}

		foreach ($prefixes AS $oldPrefixId => $prefix)
		{
			/** @var \XF\Import\Data\ThreadPrefix $importPrefix */
			$importPrefix = $this->newHandler('XF:ThreadPrefix');
			$importPrefix->bulkSet($this->mapKeys($prefix, [
				'display_order',
				'css_class'
			]));
			$importPrefix->prefix_group_id = isset($mappedGroupIds[$prefix['prefix_group_id']])
				? $mappedGroupIds[$prefix['prefix_group_id']]
				: 0;
			if ($prefix['allowed_user_group_ids'] == '-1')
			{
				$importPrefix->allowed_user_group_ids = [-1];
			}
			else
			{
				$importPrefix->allowed_user_group_ids = $this->mapUserGroupList($prefix['allowed_user_group_ids']);
			}
			$importPrefix->setTitle($prefix['phrase_text']);

			if (!empty($prefixNodeMap[$oldPrefixId]))
			{
				$importPrefix->setNodes($prefixNodeMap[$oldPrefixId]);
			}

			if ($importPrefix->save($oldPrefixId))
			{
				$state->imported++;
			}
		}

		return $state->complete();
	}

	// ########################### STEP: THREAD PREFIXES ###############################

	public function stepThreadFields(StepState $state)
	{
		$this->typeMap('node');
		$this->typeMap('user_group');

		$fields = $this->sourceDb->fetchAllKeyed("
			SELECT field.*,
				ptitle.phrase_text AS title,
				pdesc.phrase_text AS description
			FROM xf_thread_field AS field
			INNER JOIN xf_phrase AS ptitle ON
				(ptitle.language_id = 0 AND ptitle.title = CONCAT('thread_field_title.', field.field_id))
			INNER JOIN xf_phrase AS pdesc ON
				(pdesc.language_id = 0 AND pdesc.title = CONCAT('thread_field_desc.', field.field_id))
		", 'field_id');

		$existingFields = $this->db()->fetchPairs("SELECT field_id, field_id FROM xf_thread_field");

		$fieldNodeMap = [];
		$nodeFields = $this->sourceDb->fetchAll("
			SELECT *
			FROM xf_forum_field
		");
		foreach ($nodeFields AS $nodeField)
		{
			$newNodeId = $this->lookupId('node', $nodeField['node_id']);
			if ($newNodeId)
			{
				$fieldNodeMap[$nodeField['field_id']][] = $newNodeId;
			}
		}

		foreach ($fields AS $oldId => $field)
		{
			if (!empty($existingFields[$oldId]))
			{
				// don't import a field if we already have one called that - this assumes the same structure
				$this->logHandler('XF:ThreadField', $oldId, $oldId);
			}
			else
			{
				/** @var \XF\Import\Data\ThreadField $import */
				$import = $this->getHelper()->setupXfCustomFieldImport('XF:ThreadField', $field);

				if (!empty($fieldNodeMap[$oldId]))
				{
					$import->setNodes($fieldNodeMap[$oldId]);
				}

				$import->save($oldId);
			}

			$state->imported++;
		}

		return $state->complete();
	}

	// ########################### STEP: THREADS ###############################

	public function getStepEndThreads()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(thread_id) FROM xf_thread") ?: 0;
	}

	public function stepThreads(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;
		$timer = new \XF\Timer($maxTime);

		$threads = $this->sourceDb->fetchAllKeyed("
			SELECT t.*,
				d.delete_date, d.delete_user_id, d.delete_username, d.delete_reason
			FROM xf_thread AS t
			LEFT JOIN xf_deletion_log AS d ON (d.content_type = 'thread' AND d.content_id = t.thread_id)
			WHERE t.thread_id > ? AND t.thread_id <= ?
				AND t.discussion_type <> 'redirect'
			ORDER BY t.thread_id
			LIMIT {$limit}
		", 'thread_id', [$state->startAfter, $state->end]);
		if (!$threads)
		{
			return $state->complete();
		}

		$mapUserIds = [];
		foreach ($threads AS $thread)
		{
			$mapUserIds[] = $thread['user_id'];
			$mapUserIds[] = $thread['last_post_user_id'];
		}

		$this->lookup('user', array_unique($mapUserIds));
		$this->typeMap('node');
		$this->typeMap('thread_prefix');

		foreach ($threads AS $oldId => $thread)
		{
			$state->startAfter = $oldId;

			$nodeId = $this->lookupId('node', $thread['node_id']);
			if (!$nodeId)
			{
				continue;
			}

			/** @var \XF\Import\Data\Thread $import */
			$import = $this->newHandler('XF:Thread');
			$import->bulkSet($this->mapKeys($thread, [
				'title',
				'reply_count',
				'view_count',
				'username',
				'post_date',
				'sticky',
				'discussion_state',
				'discussion_open',
				'discussion_type',
				'first_post_likes',
				'last_post_date',
				'last_post_username'
			]));
			$import->bulkSet([
				'node_id' => $nodeId,
				'prefix_id' => $thread['prefix_id'] ? $this->lookupId('thread_prefix', $thread['prefix_id'], 0) : 0,
				'user_id' => $this->lookupId('user', $thread['user_id'], 0),
				'last_post_user_id' => $this->lookupId('user', $thread['last_post_user_id'], 0)
			]);
			$import->setDeletionLogData($this->extractDeletionLogData($thread));

			$customFields = $this->decodeValue($thread['custom_fields'], 'serialized-array');
			if ($customFields)
			{
				$import->setCustomFields($this->mapCustomFields('thread_field', $customFields));
			}

			$watchers = $this->sourceDb->fetchPairs("
				SELECT user_id, email_subscribe
				FROM xf_thread_watch
				WHERE thread_id = ?
			", $thread['thread_id']);
			if ($watchers)
			{
				$watcherUserIdMap = $this->lookup('user', array_keys($watchers));
				foreach ($watchers AS $watcherUserId => $emailSubscribe)
				{
					if (!empty($watcherUserIdMap[$watcherUserId]))
					{
						$import->addThreadWatcher($watcherUserIdMap[$watcherUserId], $emailSubscribe ? true : false);
					}
				}
			}

			$replyBans = $this->sourceDb->fetchAllKeyed("
				SELECT *
				FROM xf_thread_reply_ban
				WHERE thread_id = ?
			", 'thread_reply_ban_id', $thread['thread_id']);
			foreach ($replyBans AS $oldReplyBanId => $replyBan)
			{
				$replyBanUserId = $this->lookupId('user', $replyBan['user_id']);
				if (!$replyBanUserId)
				{
					continue;
				}

				/** @var \XF\Import\Data\ThreadReplyBan $importReplyBan */
				$importReplyBan = $this->newHandler('XF:ThreadReplyBan');
				$importReplyBan->bulkSet($this->mapKeys($replyBan, [
					'ban_date',
					'expiry_date',
					'reason'
				]));
				$importReplyBan->user_id = $replyBanUserId;
				$importReplyBan->ban_user_id = $this->lookupId('user', $replyBan['ban_user_id'], 0);
				$import->addReplyBan($oldReplyBanId, $importReplyBan);
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

	// ########################### STEP: POSTS ###############################

	public function getStepEndPosts()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(post_id) FROM xf_post") ?: 0;
	}

	public function stepPosts(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;
		$timer = new \XF\Timer($maxTime);

		$posts = $this->sourceDb->fetchAllKeyed("
			SELECT p.*,
				ip.ip,
				d.delete_date, d.delete_user_id, d.delete_username, d.delete_reason
			FROM xf_post AS p
			LEFT JOIN xf_ip AS ip ON (ip.ip_id = p.ip_id)
			LEFT JOIN xf_deletion_log AS d ON (d.content_type = 'post' AND d.content_id = p.post_id)
			WHERE p.post_id > ? AND p.post_id <= ?
			ORDER BY p.post_id
			LIMIT {$limit}
		", 'post_id', [$state->startAfter, $state->end]);
		if (!$posts)
		{
			return $state->complete();
		}

		$mapUserIds = [];
		$mapThreadIds = [];
		foreach ($posts AS $post)
		{
			$mapUserIds[] = $post['user_id'];
			$mapThreadIds[] = $post['thread_id'];
		}

		$this->lookup('user', array_unique($mapUserIds));
		$this->lookup('thread', array_unique($mapThreadIds));

		foreach ($posts AS $oldId => $post)
		{
			$state->startAfter = $oldId;

			$threadId = $this->lookupId('thread', $post['thread_id']);
			if (!$threadId)
			{
				continue;
			}

			/** @var \XF\Import\Data\Post $import */
			$import = $this->newHandler('XF:Post');
			$import->bulkSet($this->mapKeys($post, [
				'username',
				'post_date',
				'message_state',
				'position',
				'warning_message',
				'last_edit_date',
				'edit_count'
			]));
			$import->thread_id = $threadId;
			$import->user_id = $this->lookupId('user', $post['user_id'], 0);
			$import->last_edit_user_id = $post['last_edit_user_id'] ? $this->lookupId('user', $post['last_edit_user_id'], 0) : 0;
			$import->message = $this->rewriteQuotes($post['message']);
			$import->setLoggedIp($post['ip']);

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

	protected function rewriteQuotes($text)
	{
		if (stripos($text, '[quote=') === false)
		{
			return $text;
		}

		return preg_replace_callback(
			'/\[quote=("|\'|)(?P<username>[^,]*)\s*,\s*post:\s*(?P<post_id>\d+)\s*,\s*member:\s*(?P<user_id>\d+)\s*\1\]/siU',
			function ($match)
			{
				return sprintf('[QUOTE="%s, post: %d, member: %d"]',
					$match['username'],
					$this->lookupId('post', $match['post_id'], 0),
					$this->lookupId('user', $match['user_id'], 0)
				);
			},
			$text
		);
	}

	// ########################### STEP: POST EDIT HISTORY ###############################

	public function getStepEndPostEditHistory()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(edit_history_id) FROM xf_edit_history WHERE content_type = 'post'") ?: 0;
	}

	public function stepPostEditHistory(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;
		$timer = new \XF\Timer($maxTime);

		$edits = $this->sourceDb->fetchAllKeyed("
			SELECT *
			FROM xf_edit_history
			WHERE edit_history_id > ? AND edit_history_id <= ?
				AND content_type = 'post'
			ORDER BY edit_history_id
			LIMIT {$limit}
		", 'edit_history_id', [$state->startAfter, $state->end]);
		if (!$edits)
		{
			return $state->complete();
		}

		$mapUserIds = [];
		$mapPostIds = [];
		foreach ($edits AS $edit)
		{
			$mapUserIds[] = $edit['edit_user_id'];
			$mapPostIds[] = $edit['content_id'];
		}

		$this->lookup('user', array_unique($mapUserIds));
		$this->lookup('post', array_unique($mapPostIds));

		foreach ($edits AS $oldId => $edit)
		{
			$state->startAfter = $oldId;

			$postId = $this->lookupId('post', $edit['content_id']);
			if (!$postId)
			{
				continue;
			}

			/** @var \XF\Import\Data\EditHistory $import */
			$import = $this->newHandler('XF:EditHistory');
			$import->bulkSet($this->mapKeys($edit, [
				'edit_date',
				'old_text'
			]));
			$import->content_type = 'post';
			$import->content_id = $postId;
			$import->edit_user_id = $this->lookupId('user', $edit['edit_user_id'], 0);

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

	// ########################### STEP: THREAD POLLS ###############################

	public function getStepEndThreadPolls()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(poll_id) FROM xf_poll WHERE content_type = 'thread'") ?: 0;
	}

	public function stepThreadPolls(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;
		$timer = new \XF\Timer($maxTime);

		$polls = $this->sourceDb->fetchAllKeyed("
			SELECT *
			FROM xf_poll
			WHERE poll_id > ? AND poll_id <= ?
				AND content_type = 'thread'
			ORDER BY poll_id
			LIMIT {$limit}
		", 'poll_id', [$state->startAfter, $state->end]);
		if (!$polls)
		{
			return $state->complete();
		}

		$mapThreadIds = [];
		foreach ($polls AS $poll)
		{
			$mapThreadIds[] = $poll['content_id'];
		}

		$this->lookup('thread', array_unique($mapThreadIds));

		foreach ($polls AS $oldId => $poll)
		{
			$state->startAfter = $oldId;

			$threadId = $this->lookupId('thread', $poll['content_id']);
			if (!$threadId)
			{
				continue;
			}

			/** @var \XF\Import\Data\Poll $import */
			$import = $this->newHandler('XF:Poll');
			$import->bulkSet($this->mapKeys($poll, [
				'question',
				'public_votes',
				'max_votes',
				'close_date',
				'change_vote',
				'view_results_unvoted'
			]));
			$import->content_type = 'thread';
			$import->content_id = $threadId;

			$responses = $this->sourceDb->fetchAllKeyed("
				SELECT *
				FROM xf_poll_response
				WHERE poll_id = ?
				ORDER BY poll_response_id
			", 'poll_response_id', $oldId);
			$importResponses = [];
			foreach ($responses AS $oldResponseId => $response)
			{
				/** @var \XF\Import\Data\PollResponse $importResponse */
				$importResponse = $this->newHandler('XF:PollResponse');
				$importResponse->response = $response['response'];
				$importResponses[$oldResponseId] = $importResponse;

				$import->addResponse($oldResponseId, $importResponse);
			}

			$votes = $this->sourceDb->fetchAll("
				SELECT *
				FROM xf_poll_vote
				WHERE poll_id = ?
			", $oldId);
			foreach ($votes AS $vote)
			{
				if (!isset($importResponses[$vote['poll_response_id']]))
				{
					continue;
				}

				$voteUserId = $this->lookupId('user', $vote['user_id']);
				if (!$voteUserId)
				{
					continue;
				}

				$importResponses[$vote['poll_response_id']]->addVote($voteUserId, $vote['vote_date']);
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

	// ########################### STEP: ATTACHMENTS ###############################

	public function getStepEndAttachments()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(attachment_id) FROM xf_attachment") ?: 0;
	}

	public function stepAttachments(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 500;
		$timer = new \XF\Timer($maxTime);

		$attachments = $this->sourceDb->fetchAllKeyed("
			SELECT a.*,
				ad.*
			FROM xf_attachment AS a
			INNER JOIN xf_attachment_data AS ad ON (a.data_id = ad.data_id)
			WHERE a.attachment_id > ? AND a.attachment_id <= ?
				AND a.content_type IN ('post', 'conversation_message')
			ORDER BY a.attachment_id
			LIMIT {$limit}
		", 'attachment_id', [$state->startAfter, $state->end]);
		if (!$attachments)
		{
			return $state->complete();
		}

		$mapUserIds = [];
		$mapContentIds = [];
		foreach ($attachments AS $attachment)
		{
			$mapUserIds[] = $attachment['user_id'];
			$mapContentIds[$attachment['content_type']][] = $attachment['content_id'];
		}

		$this->lookup('user', array_unique($mapUserIds));

		foreach ($mapContentIds AS $contentType => $contentIds)
		{
			$this->lookup($contentType, array_unique($contentIds));
		}

		foreach ($attachments AS $oldId => $attachment)
		{
			$state->startAfter = $oldId;

			$contentId = $this->lookupId($attachment['content_type'], $attachment['content_id']);
			if (!$contentId)
			{
				continue;
			}

			$sourceFile = $this->getSourceAttachmentDataPath(
				$attachment['data_id'], $attachment['file_path'], $attachment['file_hash']
			);
			if (!file_exists($sourceFile) || !is_readable($sourceFile))
			{
				continue;
			}

			/** @var \XF\Import\Data\Attachment $import */
			$import = $this->newHandler('XF:Attachment');
			$import->bulkSet($this->mapKeys($attachment, [
				'content_type',
				'attach_date',
				'temp_hash',
				'unassociated',
				'view_count'
			]));
			$import->content_id = $contentId;
			$import->setDataExtra('upload_date', $attachment['upload_date']);
			$import->setDataUserId($this->lookupId('user', $attachment['user_id'], 0));
			$import->setSourceFile($sourceFile, $attachment['filename']);
			$import->setContainerCallback([$this, 'rewriteEmbeddedAttachments']);

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

					return $match[1] . $id . $match[2];
				},
				$message
			);

			$container->message = $message;
		}
	}

	// ########################### STEP: LIKES ###############################

	public function getStepEndLikes()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(like_id) FROM xf_liked_content") ?: 0;
	}

	public function stepLikes(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;
		$timer = new \XF\Timer($maxTime);

		$likes = $this->sourceDb->fetchAllKeyed("
			SELECT *
			FROM xf_liked_content
			WHERE like_id > ? AND like_id <= ?
				AND content_type IN ('conversation_message', 'post', 'profile_post', 'profile_post_comment')
			ORDER BY like_id
			LIMIT {$limit}
		", 'like_id', [$state->startAfter, $state->end]);
		if (!$likes)
		{
			return $state->complete();
		}

		$mapUserIds = [];
		$mapContentIds = [];
		foreach ($likes AS $like)
		{
			$mapUserIds[] = $like['like_user_id'];
			$mapUserIds[] = $like['content_user_id'];
			$mapContentIds[$like['content_type']][] = $like['content_id'];
		}

		$this->lookup('user', array_unique($mapUserIds));

		foreach ($mapContentIds AS $contentType => $contentIds)
		{
			$this->lookup($contentType, array_unique($contentIds));
		}

		foreach ($likes AS $oldId => $like)
		{
			$state->startAfter = $oldId;

			$contentId = $this->lookupId($like['content_type'], $like['content_id']);
			if (!$contentId)
			{
				continue;
			}

			$likeUserId = $this->lookupId('user', $like['like_user_id']);
			if (!$likeUserId)
			{
				continue;
			}

			/** @var \XF\Import\Data\LikedContent $import */
			$import = $this->newHandler('XF:LikedContent');
			$import->bulkSet($this->mapKeys($like, [
				'content_type',
				'like_date',
				'is_counted'
			]));
			$import->content_id = $contentId;
			$import->like_user_id = $likeUserId;
			$import->content_user_id = $this->lookupId('user', $like['content_user_id'], 0);

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

	// ########################### STEP: TAGS ###############################

	public function getStepEndTags()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(tag_content_id) FROM xf_tag_content") ?: 0;
	}

	public function stepTags(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;
		$timer = new \XF\Timer($maxTime);

		$tags = $this->sourceDb->fetchAllKeyed("
			SELECT tc.*,
				t.*
			FROM xf_tag_content AS tc
			INNER JOIN xf_tag AS t ON (tc.tag_id = t.tag_id)
			WHERE tc.tag_content_id > ? AND tc.tag_content_id <= ?
				AND tc.content_type IN ('thread')
			ORDER BY tc.tag_content_id
			LIMIT {$limit}
		", 'tag_content_id', [$state->startAfter, $state->end]);
		if (!$tags)
		{
			return $state->complete();
		}

		$mapUserIds = [];
		$mapContentIds = [];
		foreach ($tags AS $tag)
		{
			$mapUserIds[] = $tag['add_user_id'];
			$mapContentIds[$tag['content_type']][] = $tag['content_id'];
		}

		$this->lookup('user', array_unique($mapUserIds));

		foreach ($mapContentIds AS $contentType => $contentIds)
		{
			$this->lookup($contentType, array_unique($contentIds));
		}

		/** @var \XF\Import\DataHelper\Tag $tagHelper */
		$tagHelper = $this->getDataHelper('XF:Tag');

		foreach ($tags AS $oldId => $tag)
		{
			$state->startAfter = $oldId;

			$contentId = $this->lookupId($tag['content_type'], $tag['content_id']);
			if (!$contentId)
			{
				continue;
			}

			$contentExtra = [
				'add_user_id' => $this->lookupId('user', $tag['add_user_id'], 0),
				'add_date' => $tag['add_date'],
				'visible' => $tag['visible'],
				'content_date' => $tag['content_date']
			];
			$tagExtra = [
				'tag_url' => $tag['tag_url'],
				'permanent' => $tag['permanent']
			];

			$newId = $tagHelper->importTag($tag['tag'], $tag['content_type'], $contentId, $contentExtra, $tagExtra);
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

	// ########################### STEP: WARNINGS ###############################

	public function getStepEndWarnings()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(warning_id) FROM xf_warning") ?: 0;
	}

	public function stepWarnings(StepState $state, array $stepConfig, $maxTime)
	{
		$limit = 1000;
		$timer = new \XF\Timer($maxTime);

		$warnings = $this->sourceDb->fetchAllKeyed("
			SELECT *
			FROM xf_warning
			WHERE warning_id > ? AND warning_id <= ?
			ORDER BY warning_id
			LIMIT {$limit}
		", 'warning_id', [$state->startAfter, $state->end]);
		if (!$warnings)
		{
			return $state->complete();
		}

		$mapUserIds = [];
		$mapContentIds = [];
		foreach ($warnings AS $warning)
		{
			$mapUserIds[] = $warning['user_id'];
			$mapUserIds[] = $warning['warning_user_id'];
			$mapContentIds[$warning['content_type']][] = $warning['content_id'];
		}

		$this->lookup('user', array_unique($mapUserIds));

		foreach ($mapContentIds AS $contentType => $contentIds)
		{
			$this->lookup($contentType, array_unique($contentIds));
		}

		foreach ($warnings AS $oldId => $warning)
		{
			$state->startAfter = $oldId;

			$userId = $this->lookupId('user', $warning['user_id']);
			if (!$userId)
			{
				continue;
			}

			$contentType = $warning['content_type'];
			$contentId = $this->lookupId($contentType, $warning['content_id']);
			if (!$contentId)
			{
				// content wasn't imported, but we should keep this warning
				$contentType = 'user';
				$contentId = $userId;
			}

			/** @var \XF\Import\Data\Warning $import */
			$import = $this->newHandler('XF:Warning');
			$import->bulkSet($this->mapKeys($warning, [
				'content_title',
				'warning_date',
				'title',
				'notes',
				'points',
				'expiry_date',
				'is_expired'
			]));
			$import->content_type = $contentType;
			$import->content_id = $contentId;
			$import->user_id = $userId;
			$import->warning_user_id = $this->lookupId('user', $warning['warning_user_id'], 0);
			$import->extra_user_group_ids = $this->mapUserGroupList($warning['extra_user_group_ids']);

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




	// ############### UTILITY FUNCTIONS ##########################

	protected function mapUserGroupList($userGroups)
	{
		return $this->getHelper()->mapUserGroupList($userGroups);
	}

	protected function mapCustomFields($importType, array $fieldValues)
	{
		$this->typeMap($importType);

		$importFields = [];
		foreach ($fieldValues AS $oldFieldId => $fieldValue)
		{
			$newFieldId = $this->lookupId($importType, $oldFieldId);
			if ($newFieldId)
			{
				$importFields[$newFieldId] = $fieldValue;
			}
		}

		return $importFields;
	}

	protected function loadSourcePermissions($userGroupId, $userId)
	{
		$output = [];
		$results = $this->sourceDb->fetchAll("
			SELECT *
			FROM xf_permission_entry
			WHERE user_group_id = ?
				AND user_id = ?
		", [$userGroupId, $userId]);
		foreach ($results AS $result)
		{
			$value = $result['permission_value'];
			if ($value == 'use_int')
			{
				$value = $result['permission_value_int'];
			}

			$output[$result['permission_group_id']][$result['permission_id']] = $value;
		}

		return $output;
	}

	protected function extractDeletionLogData(array $data)
	{
		$deletionLog = [];
		foreach ($data AS $k => $v)
		{
			if ($v === null)
			{
				continue;
			}

			switch ($k)
			{
				case 'delete_date': $deletionLog['date'] = $v; break;
				case 'delete_user_id': $deletionLog['user_id'] = $v ? $this->lookupId('user', $v, 0) : 0; break;
				case 'delete_username': $deletionLog['username'] = $v; break;
				case 'delete_reason': $deletionLog['reason'] = $v; break;
			}
		}

		return $deletionLog;
	}

	protected function getSourceAttachmentDataPath($dataId, $filePath, $fileHash)
	{
		$group = floor($dataId / 1000);

		if ($filePath)
		{
			$placeholders = [
				'%INTERNAL%' => 'internal-data://', // for legacy
				'%DATA%' => 'data://', // for legacy
				'%DATA_ID%' => $dataId,
				'%FLOOR%' => $group,
				'%HASH%' => $fileHash
			];
			$path = strtr($filePath, $placeholders);
			$path = str_replace(':///', '://', $path); // writing %INTERNAL%/path would cause this
		}
		else
		{
			$path = sprintf('internal-data://attachments/%d/%d-%s.data',
				$group,
				$dataId,
				$fileHash
			);
		}

		return strtr($path, [
			'internal-data://' => $this->baseConfig['internal_data_dir'] . '/',
			'data://' => $this->baseConfig['data_dir'] . '/'
		]);
	}

	// TODO: notices?
}