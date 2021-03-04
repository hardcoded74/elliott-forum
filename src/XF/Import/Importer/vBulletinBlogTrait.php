<?php

namespace XF\Import\Importer;

use XF\Import\StepState;

trait vBulletinBlogTrait
{
	protected function getStepConfigDefault()
	{
		return parent::getStepConfigDefault() + [
				'blogUsers' => [
					'parent_node_id' => 0
				],
				'blogAttachments' => [
					'path' => $this->baseConfig['blogattachpath']
				]
			];
	}

	protected function getStepConfigOptions(array $vars)
	{
		$vars = parent::getStepConfigOptions($vars);

		if (in_array('blogUsers', $vars['steps']))
		{
			if (!isset($vars['nodeTree']))
			{
				/** @var \XF\Repository\Node $nodeRepo */
				$nodeRepo = $this->app->repository('XF:Node');
				$vars['nodeTree'] = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());
			}
		}

		return $vars;
	}

	public function validateStepConfig(array $steps, array &$stepConfig, array &$errors)
	{
		// blog attachments as files - path
		if (array_key_exists('blogAttachments', $stepConfig) && !empty($this->baseConfig['blogattachpath']))
		{
			if (!empty($stepConfig['blogAttachments']['path']))
			{
				$path = realpath(trim($stepConfig['blogAttachments']['path']));

				if (!file_exists($path) || !is_dir($path))
				{
					$errors[] = \XF::phrase('directory_specified_as_x_y_not_found_is_not_readable', [
						'type' => 'blogattachpath',
						'dir'  => $stepConfig['blogAttachments']['path']
					]);
				}

				$stepConfig['blogAttachments']['path'] = $path;
			}
			else
			{
				// we have no path, this will cause the step to skip
				$stepConfig['blogAttachments']['skip'] = true;
			}
		}

		return parent::validateStepConfig($steps, $stepConfig, $errors);
	}

	public function getSteps()
	{
		return parent::getSteps() + [
			'blogUsers' => [
				'title' => \XF::phrase('import_blogs'),
				'depends' => ['users']
			],
			'blogEntries' => [
				'title' => \XF::phrase('import_blog_entries'),
				'depends' => ['blogUsers']
			],
			'blogAttachments' => [
				'title' => \XF::phrase('import_blog_attachments'),
				'depends' => ['blogEntries']
			],
			'blogComments' => [
				'title' => \XF::phrase('import_blog_comments'),
				'depends' => ['blogEntries']
			],
			'blogTags' => [
				'title' => \XF::phrase('import_blog_tags'),
				'depends' => ['blogEntries']
			],
			'blogModerators' => [
				'title' => \XF::phrase('import_blog_moderators'),
				'depends' => ['blogUsers']
			]
		];
	}

	// ########################### STEP: BLOG USERS ###############################

	public function getStepEndBlogUsers()
	{
		//die(PHP_EOL . 'Blog users...' . PHP_EOL);
		return $this->sourceDb->fetchOne("SELECT MAX(bloguserid) FROM {$this->prefix}blog_user") ?: 0;
	}

	public function stepBlogUsers(StepState $state, array $stepConfig, $maxTime, $limit = 100)
	{
		$timer = new \XF\Timer($maxTime);

		$blogUsers = $this->sourceDb->fetchAllKeyed("
			SELECT
				bu.bloguserid,
				IF(bu.title <> '', bu.title, user.username) AS title,
				bu.description,
				user.username
			FROM {$this->prefix}blog_user AS
				bu
			INNER JOIN {$this->prefix}user AS 
				user ON(user.userid = bu.bloguserid)
			WHERE bu.bloguserid > ? AND bu.bloguserid <= ?
			ORDER BY bu.bloguserid
		", 'bloguserid', [$state->startAfter, $state->end]);

		if (!$blogUsers)
		{
			return $state->complete();
		}

		$subs = $this->sourceDb->fetchAll("
			SELECT bloguserid, userid, type
			FROM {$this->prefix}blog_subscribeuser
			WHERE bloguserid IN(" . $this->sourceDb->quote(array_keys($blogUsers)) . ")
		");
		$blogUserSubscriptions = [];
		foreach ($subs AS $sub)
		{
			$blogUserSubscriptions[$sub['bloguserid']][$sub['userid']] = ($sub['type'] == 'email');
		}

		$this->lookup('user', array_keys($blogUsers) + $this->pluck($subs, 'userid'));

		foreach ($blogUsers AS $oldUserId => $blogUser)
		{
			$state->startAfter = $oldUserId;

			if (!$newUserId = $this->lookupId('user', $oldUserId))
			{
				continue;
			}

			/** @var \XF\Import\Data\vBulletinBlogUser $import */
			$import = $this->newHandler('XF:vBulletinBlogUser');

			$import->title = $blogUser['title'];
			$import->description = $blogUser['description'];
			$import->parent_node_id = $stepConfig['parent_node_id'];

			/** @var \XF\Import\Data\Forum $forumHandler */
			$forumHandler = $this->newHandler('XF:Forum');

			if (isset($blogUserSubscriptions[$oldUserId]))
			{
				foreach ($blogUserSubscriptions[$oldUserId] AS $oldSubUserId => $emailUpdate)
				{
					if ($newSubUserId = $this->lookupId('user', $oldSubUserId))
					{
						$forumHandler->addForumWatcher($newSubUserId, $this->setupForumSubscribeData($emailUpdate));
					}
				}
			}

			$import->setType('Forum', $forumHandler);

			if ($newId = $import->save($oldUserId))
			{
				/** @var \XF\Import\DataHelper\Permission $permissionHelper */
				$permissionHelper = $this->dataManager->helper('XF:Permission');

				// deny forum.newThread permissions to registered users, and allow for the blog owner
				$permissionHelper->insertContentUserGroupPermissions('node', $newId, 2, ['forum' => ['postThread' => 'reset']]);
				$permissionHelper->insertContentUserPermissions('node', $newId, $newUserId, ['forum' => ['postThread' => 'content_allow']]);

				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				continue;
			}
		}

		return $state->resumeIfNeeded();
	}

	// ########################### STEP: BLOG ENTRIES ###############################

	public function getStepEndBlogEntries()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(blogid) FROM {$this->prefix}blog") ?: 0;
	}

	public function stepBlogEntries(StepState $state, array $stepConfig, $maxTime, $limit = 1000)
	{
		$timer = new \XF\Timer($maxTime);

		$blogEntries = $this->sourceDb->fetchAllKeyed("
			SELECT
				blog.*, blog_text.*,
				user.username
			FROM {$this->prefix}blog AS
				blog
			INNER JOIN {$this->prefix}blog_text AS
				blog_text ON(blog_text.blogtextid = blog.firstblogtextid)
			INNER JOIN {$this->prefix}user AS
				user ON(user.userid = blog.userid)
			WHERE blog.blogid > ? AND blog.blogid <= ?
			ORDER BY blog.blogid
			LIMIT {$limit}
		", 'blogid', [$state->startAfter, $state->end]);

		if (!$blogEntries)
		{
			return $state->complete();
		}

		$subs = $this->sourceDb->fetchAll("
			SELECT blogid, userid, type
			FROM {$this->prefix}blog_subscribeentry
			WHERE blogid IN(" . $this->sourceDb->quote(array_keys($blogEntries)) . ")
		");
		$blogSubscriptions = [];
		foreach ($subs AS $sub)
		{
			$blogSubscriptions[$sub['blogid']][$sub['userid']] = ($sub['type'] == 'email');
		}

		$this->lookup('user', $this->pluck($blogEntries, 'userid') + $this->pluck($subs, 'userid'));

		// this will actually get the node ids that correspond to the user id of the blogs being posted
		$this->typeMap('blog_user');

		foreach ($blogEntries AS $blogId => $blog)
		{
			$state->startAfter = $blogId;

			if (!$newNodeId = $this->lookupId('blog_user', $blog['userid']))
			{
				continue;
			}

			if (!$newUserId = $this->lookupId('user', $blog['userid']))
			{
				continue;
			}

			/** @var \XF\Import\Data\vBulletinBlogEntry $importThread */
			$importThread = $this->newHandler('XF:vBulletinBlogEntry');

			$importThread->node_id = $newNodeId;
			$importThread->user_id = $newUserId;

			$importThread->bulkSet($this->mapXfKeys($blog, [
				'username',
				'title',
				'view_count' => 'views',
				'post_date' => 'dateline',
				'last_post_date' => 'lastcomment',
			]));

			$importThread->discussion_open = true; // TODO: really?
			$importThread->discussion_state = $this->decodeBlogState($blog['state']);

			$blogText = $this->processBlogText($blog['pagetext']);

			$importThread->setBlogText($blogText, $blog['blogtextid']);
			$importThread->setLoggedIp($blog['ipaddress']);

			if (isset($blogSubscriptions[$blogId]))
			{
				foreach ($blogSubscriptions[$blogId] AS $oldSubUserId => $emailUpdate)
				{
					if ($newSubUserId = $this->lookupId('user', $oldSubUserId))
					{
						$importThread->addThreadWatcher($newSubUserId, $emailUpdate);
					}
				}
			}

			if ($newThreadId = $importThread->save($blogId))
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

	protected function decodeBlogState($state)
	{
		switch ($state)
		{
			case 'moderation':
				return 'moderated';
			case 'draft':
				return 'visible'; // TODO: a draft state?
			case 'deleted':
			case 'visible':
				return $state;
		}
	}

	// ########################### STEP: BLOG ATTACHMENTS ###############################

	public function getStepEndBlogAttachments()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(attachmentid) FROM {$this->prefix}blog_attachment") ?: 0;
	}

	public function stepBlogAttachments(StepState $state, array $stepConfig, $maxTime, $limit = 1000)
	{
		if (isset($stepConfig['skip']))
		{
			return $state->complete();
		}

		$timer = new \XF\Timer($maxTime);

		$attachments = $this->getBlogAttachments($state->startAfter, $state->end, $limit);

		if (!$attachments)
		{
			return $state->complete();
		}

		$this->lookup('blog_text', $this->pluck($attachments, 'blogtextid'));
		$this->lookup('user', $this->pluck($attachments, 'userid'));

		foreach ($attachments AS $attachmentId => $attachment)
		{
			$state->startAfter = $attachmentId;

			if (!$newBlogTextId = $this->lookupId('blog_text', $attachment['blogtextid']))
			{
				continue;
			}

			if ($stepConfig['path'])
			{
				// original attachment file location
				$attachTempFile =  $this->getAttachmentFilePath($stepConfig['path'], $attachment);

				if (!file_exists($attachTempFile))
				{
					continue;
				}
			}
			else
			{
				if (!$fileData = $this->getBlogAttachmentFileData($attachmentId))
				{
					continue;
				}

				$attachTempFile = \XF\Util\File::getTempFile();
				\XF\Util\File::writeFile($attachTempFile, $fileData);
			}

			/** @var \XF\Import\Data\vBulletinBlogAttachment $import */
			$import = $this->newHandler('XF:vBulletinBlogAttachment');
			$import->bulkSet([
				'content_type' => 'post',
				'content_id' => $newBlogTextId,
				'attach_date' => $attachment['dateline'],
				'view_count' => $attachment['counter'],
				'unassociated' => false
			]);

			$import->setDataUserId($this->lookupId('user', $attachment['userid'], 0));
			$import->setSourceFile($attachTempFile, $attachment['filename']);
			$import->setContainerCallback([$this, 'rewriteEmbeddedBlogAttachments']);

			if ($newId = $import->save($attachmentId))
			{
				$state->imported++;
			}

			if ($timer->limitExceeded())
			{
				break;
			}
		}

		\XF\Util\File::cleanUpTempFiles();

		return $state->resumeIfNeeded();
	}

	protected function getBlogAttachments($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchAllKeyed("
			SELECT
				atc.attachmentid, atc.blogid, atc.userid,
				atc.filename, atc.filesize, atc.visible,
				atc.counter, atc.dateline,
				atc.extension,
				blog.firstblogtextid AS blogtextid
			FROM {$this->prefix}blog_attachment AS
				atc
			INNER JOIN {$this->prefix}blog AS
				blog ON(blog.blogid = atc.blogid)
			WHERE atc.attachmentid > ? AND atc.attachmentid <= ?
			LIMIT {$limit}
		", 'attachmentid', [$startAfter, $end]);
	}

	protected function getBlogAttachmentFileData($attachmentId)
	{
		return $this->sourceDb->fetchOne("
			SELECT filedata
			FROM {$this->prefix}blog_attachment
			WHERE attachmentid = ?
		", $attachmentId);
	}

	public function rewriteEmbeddedBlogAttachments(\XF\Mvc\Entity\Entity $container, \XF\Entity\Attachment $attachment, $oldId)
	{
		// vB3/Blog appears to use [ATTACH]{id}[/ATTACH], where {id} refers to blog_attachment.attachmentid

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

					// use $id.vB - see vBulletin::rewriteEmbeddedAttachments()
					return $match[1] . $id . '.vB' . $match[2];
				},
				$message
			);

			$container->message = $message;
		}
	}

	// ########################### STEP: BLOG COMMENTS ###############################

	public function getStepEndBlogComments()
	{
		/*
		 * This assumes that blogs with comments will have a 'lastcomment' date later than the blog's own 'dateline'
		 */
		return $this->sourceDb->fetchOne("
			SELECT MAX(blogid)
			FROM {$this->prefix}blog
			WHERE lastcomment > dateline") ?: 0;
	}

	public function stepBlogComments(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		$blogs = $this->sourceDb->fetchPairs("
			SELECT blogid, firstblogtextid
			FROM {$this->prefix}blog
			WHERE blogid > ? AND blogid <= ?
			AND lastcomment > dateline
			ORDER BY blogid
			LIMIT {$limit}
		", [$state->startAfter, $state->end]);

		$this->lookup('blog', array_keys($blogs));

		foreach ($blogs AS $blogId => $firstBlogTextId)
		{
			$state->startAfter = $blogId;

			/*
			 * We will attempt to import ALL comments for each blog in one hit,
			 * on the assumption that we're unlikely to have thousands of comments
			 * in most cases...
			 */
			$blogComments = $this->sourceDb->fetchAll("
				SELECT
					blog_text.*,
					user.username,
					blog_editlog.userid AS edituserid,
					blog_editlog.dateline AS editdate,
					blog_editlog.reason AS editreason
				FROM {$this->prefix}blog_text AS
					blog_text
				LEFT JOIN {$this->prefix}blog_editlog AS
					blog_editlog ON(blog_editlog.blogtextid = blog_text.blogtextid)
				LEFT JOIN {$this->prefix}user AS
					user ON(user.userid = blog_text.userid)
				WHERE blog_text.blogid = ?
				AND blog_text.blogtextid <> ?
				ORDER BY blog_text.dateline
			", [$blogId, $firstBlogTextId]);

			if (!$blogComments)
			{
				continue;
			}

			$this->lookup('user', $this->pluck($blogComments, ['userid', 'edituserid']));

			$postPosition = 0;

			foreach ($blogComments AS $comment)
			{
				if (!$newThreadId = $this->lookupId('blog', $blogId))
				{
					continue;
				}

				$message = $this->processBlogCommentText($comment['pagetext']);

				if ($comment['state'] == 'visible')
				{
					$postPosition++;
				}

				/** @var \XF\Import\Data\vBulletinBlogText $import */
				$import = $this->newHandler('XF:vBulletinBlogText');

				$import->bulkSet([
					'thread_id' => $newThreadId,
					'post_date' => $comment['dateline'],
					'user_id' => $this->lookupId('user', $comment['userid'], 0),
					'username' => $comment['username'],
					'position' => $postPosition,
					'message' => $message,
					'message_state' => $this->decodeBlogState($comment['state']),
					'last_edit_date' => $comment['editdate'] ?: 0,
					'last_edit_user_id' => $this->lookup('user', $comment['edituserid'], 0),
					'edit_count' => $comment['editdate'] ? 1 : 0
				]);

				if ($newId = $import->save($comment['blogtextid']))
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

	// ########################### STEP: BLOG TAGS ###############################

	public function getStepEndBlogTags()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(blogid) FROM {$this->prefix}blog WHERE taglist IS NOT NULL") ?: 0;
	}

	public function stepBlogTags(StepState $state, array $stepConfig, $maxTime, $limit = 1000)
	{
		$timer = new \XF\Timer($maxTime);

		$blogs = $this->getBlogIdsWithTags($state->startAfter, $state->end, $limit);

		if (!$blogs)
		{
			return $state->complete();
		}

		/** @var \XF\Import\DataHelper\Tag $tagHelper */
		$tagHelper = $this->getDataHelper('XF:Tag');

		foreach ($blogs AS $oldBlogId => $blogPostDate)
		{
			$state->startAfter = $oldBlogId;

			if (!$newThreadId = $this->lookupId('blog', $oldBlogId))
			{
				continue;
			}

			$tags = $this->getBlogTags($oldBlogId);

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
					'content_date' => $blogPostDate
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

	protected function getBlogIdsWithTags($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchPairs("
			SELECT blogid, dateline
			FROM {$this->prefix}blog
			WHERE blogid > ? AND blogid <= ?
			AND taglist IS NOT NULL
			ORDER BY blogid
			LIMIT {$limit}
		", [$startAfter, $end]);
	}

	protected function getBlogTags($blogId)
	{
		return $this->sourceDb->fetchAll("
			SELECT
				blog_tagentry.*, blog_tag.tagtext
			FROM {$this->prefix}blog_tagentry AS
				blog_tagentry
			INNER JOIN {$this->prefix}blog_tag AS
				blog_tag ON(blog_tag.tagid = blog_tagentry.tagid)
			WHERE blog_tagentry.blogid = ?
		", $blogId);
	}

	// ########################### STEP: BLOG MODERATORS ###############################

	public function stepBlogModerators(StepState $state)
	{
		$moderators = $this->sourceDb->fetchAll("
			SELECT blog_moderator.*, user.username
			FROM {$this->prefix}blog_moderator AS
				blog_moderator
			INNER JOIN {$this->prefix}user AS
				user ON(user.userid = blog_moderator.userid)
		");

		if (!$moderators)
		{
			return $state->complete();
		}

		$this->lookup('users', $this->pluck($moderators, 'userid'));

		$blogsParentNodeId = $this->session->stepConfig['blogUsers']['parent_node_id'];

		/** @var \XF\Import\DataHelper\Moderator $modHelper */
		$modHelper = $this->getDataHelper('XF:Moderator');

		/** @var \XF\Import\DataHelper\Permission $permissionHelper */
		$permissionHelper = $this->dataManager->helper('XF:Permission');

		// TODO: these might not be totally ideal mappings
		$forumMap = [
			0 => ['forum' => ['editAnyThread']],
			1 => ['forum' => ['deleteAnyThread']],
			2 => ['forum' => ['hardDeleteAnyThread']],
			3 => ['forum' => ['approveUnapprove', 'viewModerated']],
			4 => ['forum' => ['editAnyPost']],
			5 => ['forum' => ['deleteAnyPost']],
			6 => ['forum' => ['hardDeleteAnyPost']],
			7 => ['forum' => ['approveUnapprove', 'viewModerated']],
		];

		$generalMap = [
			8 => ['general' => ['viewIps']]
		];

		foreach ($moderators AS $moderator)
		{
			if (!$newUserId = $this->lookupId('user', $moderator['userid']))
			{
				continue;
			}

			$globalPermissions = $this->applyModeratorPermissionMap($moderator['permissions'], $generalMap, 'allow');

			$forumPermissions = $this->applyModeratorPermissionMap($moderator['permissions'], $forumMap, 'content_allow');

			$this->addDefaultModeratorPermissions($globalPermissions, $forumPermissions);

			$permissionHelper->insertUserPermissions($newUserId, $globalPermissions);
			$modHelper->importContentModerator($newUserId, 'node', $blogsParentNodeId, $forumPermissions);

			// TODO: 'super' blog moderators? (blog_moderator.type=normal|super)
			$modHelper->importModerator($newUserId, false, [4]);

			$state->imported++;
		}

		return $state->complete();
	}

	// ########################### TEXT PROCESSING ###############################

	protected function processBlogText($text)
	{
		return $this->rewriteQuotes($text, $this->getBlogTextIdQuotePattern());
	}

	protected function processBlogCommentText($text)
	{
		return $this->rewriteQuotes($text, $this->getBlogTextIdQuotePattern());
	}

	protected function getBlogTextIdQuotePattern()
	{
		return [
			'/\[quote=("|\'|)(?P<username>[^;\n\]]*);\s*bt(?P<blogtextid>\d+)\s*\1\]/siU' => function ($match)
			{
				return sprintf('[QUOTE="%s, post: %d"]',
					$match['username'],
					$this->lookupId('blog_text', $match['blogtextid'])
				);
			}
		];
	}
}