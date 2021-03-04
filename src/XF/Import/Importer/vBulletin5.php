<?php

namespace XF\Import\Importer;

use XF\Import\StepState;

class vBulletin5 extends vBulletin4
{
	public static function getListInfo()
	{
		return [
			'target' => 'XenForo',
			'source' => 'vBulletin 5.x',
			'beta' => true
		];
	}

	protected $forumRootId = 2; // TODO: pretty sure this is fixed, but it might be variable if the data is from an upgraded vB3/4..?

	protected function getBaseConfigAvatarPath($pathFromDb)
	{
		return trim(preg_replace('#^\./(?!core/)#', './core/', $pathFromDb));
	}

	protected function getStepConfigOptions(array $vars)
	{
		return parent::getStepConfigOptions($vars) + [
			'vBulletin5' => true
		];
	}

	public function getSteps()
	{
		// TODO: notices?

		return parent::getSteps();
	}

	protected function getSpecialNodeId()
	{
		if (!isset($this->session->extra['node_ids']['Special']))
		{
			$this->session->extra['node_ids']['Special'] = $this->sourceDb->fetchOne("
				SELECT nodeid
				FROM {$this->prefix}node
				WHERE parentid = 1
				AND title = 'Special'
			");
		}

		return $this->session->extra['node_ids']['Special'];
	}

	protected function getSpecialNodeChildNodeId($title)
	{
		if (!isset($this->session->extra['node_ids'][$title]))
		{
			$this->session->extra['node_ids'][$title] = $this->sourceDb->fetchOne("
				SELECT nodeid
				FROM {$this->prefix}node
				WHERE parentid = ?
				AND contenttypeid = ?
				AND title = ?
				AND protected = 1
			", [$this->getSpecialNodeId(), $this->getContentTypeId('Channel'), $title]);
		}

		return $this->session->extra['node_ids'][$title];
	}

	// ########################### STEP: USERS ###############################

	protected function setUserAuthData(\XF\Import\Data\User $import, array $user)
	{
		if ($user['scheme'] == 'blowfish:10')
		{
			// TODO: write an XF handler for this scheme

			$import->setPasswordData('XF:vBulletin5', [
				'token'  => $user['token'],
				'secret' => $user['secret']
			]);

			return $import;
		}
		else if ($info = explode(' ', $user['token']))
		{
			$import->setPasswordData('XF:vBulletin', [
				'hash' => $info[0],
				'salt' => $info[1]
			]);

			return $import;
		}

		return false;
	}

	// ########################### STEP: AVATARS ###############################

	protected function getAvatarFilePath($path, array $avatar)
	{
		return "{$path}/{$avatar['filename']}";
	}

	// ########################### STEP: PRIVATE MESSAGES ###############################

	public function getStepEndPrivateMessages()
	{
		return $this->sourceDb->fetchOne("
			SELECT MAX(privatemessage.nodeid)
			FROM {$this->prefix}privatemessage AS
				privatemessage
			INNER JOIN {$this->prefix}node AS
				node ON(node.nodeid = privatemessage.nodeid)
			WHERE msgtype = 'message'
			AND node.parentid = ?
		", $this->getSpecialNodeChildNodeId('Private Messages')) ?: 0;
	}

	public function stepPrivateMessages(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		$nodes = $this->sourceDb->fetchAllKeyed("
			SELECT node.nodeid, node.userid, node.title, node.publishdate
			FROM {$this->prefix}privatemessage AS
				privatemessage
			INNER JOIN {$this->prefix}node AS
				node ON(node.nodeid = privatemessage.nodeid)
			WHERE privatemessage.nodeid > ? AND privatemessage.nodeid <= ?
			AND privatemessage.msgtype = 'message'
			AND node.parentid = ?
		", 'nodeid', [$state->startAfter, $state->end, $this->getSpecialNodeChildNodeId('Private Messages')]);

		if (!$nodes)
		{
			return $state->complete();
		}

		// fetch recipient info for all the requested nodes
		$recipients = $this->sourceDb->fetchAll("
			SELECT sentto.*
			FROM {$this->prefix}sentto AS
				sentto
			INNER JOIN {$this->prefix}node AS
				node ON(node.nodeid = sentto.nodeid)
			WHERE node.starter IN (" . $this->sourceDb->quote(array_keys($nodes)) . ")
		");

		$recipientsByNode = [];

		foreach ($recipients AS $rcpt)
		{
			$recipientsByNode[$rcpt['nodeid']][$rcpt['userid']] = [
				$rcpt['folderid'],
				$rcpt['deleted']
			];
		}

		$this->lookup('user', $this->pluck($recipients, 'userid'));

		foreach ($nodes AS $starterNodeId => $conversation)
		{
			$state->startAfter = $starterNodeId;

			/** @var \XF\Import\Data\ConversationMaster $import */
			$import = $this->newHandler('XF:ConversationMaster');
			$import->title = $conversation['title'];
			$import->start_date = $conversation['publishdate'];

			if (isset($recipientsByNode[$starterNodeId]))
			{
				foreach ($recipientsByNode[$starterNodeId] AS $oldUserId => $info)
				{
					if (!$recipientUserId = $this->lookupId('user', $oldUserId))
					{
						continue;
					}

					$recipientState = $info[1] ? 'deleted' : 'active';

					$import->addRecipient($recipientUserId, $recipientState, [
						'last_read_date' => 0, // TODO: see sentto.msgread, folderid etc. for a proper result
						'is_starred' => false
					]);
				}
			}
			else
			{
				continue;
			}

			$messages = $this->sourceDb->fetchAllKeyed("
				SELECT node.*, text.rawtext
				FROM {$this->prefix}node AS
					node
				INNER JOIN {$this->prefix}text AS
					text ON (text.nodeid = node.nodeid)
				WHERE node.starter = ?
				ORDER BY node.nodeid
			", 'nodeid', $starterNodeId);

			if (!$messages)
			{
				continue;
			}

			foreach ($messages AS $messageNodeId => $message)
			{
				/** @var \XF\Import\Data\ConversationMessage $importMessage */
				$importMessage = $this->newHandler('XF:ConversationMessage');
				$importMessage->bulkSet([
					'message_date' => $message['publishdate'],
					'user_id' => $this->lookupId('user', $message['userid']),
					'username' => $message['authorname'],
					'message' => $message['rawtext']
				]);
				$importMessage->user_id = $this->lookupId('user', $message['userid']);
				$importMessage->setLoggedIp($message['ipaddress']);

				$import->addMessage($messageNodeId, $importMessage);
			}

			if ($newId = $import->save($starterNodeId))
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
		return $this->sourceDb->fetchOne("
			SELECT MAX(nodeid) FROM {$this->prefix}node
			WHERE parentid = ?
		", $this->getSpecialNodeChildNodeId('Visitor Messages')) ?: 0;
	}

	public function stepVisitorMessages(StepState $state, array $stepConfig, $maxTime, $limit = 1000)
	{
		$timer = new \XF\Timer($maxTime);

		$visitorMessages = $this->sourceDb->fetchAll("
			SELECT node.nodeid, node.userid, node.authorname, node.setfor, node.publishdate, node.ipaddress,
				node.deleteuserid, node.deletereason, node.unpublishdate, node.totalcount, node.approved, text.rawtext
			FROM {$this->prefix}node AS
				node
			INNER JOIN {$this->prefix}text AS
				text ON(text.nodeid = node.nodeid)
			WHERE node.nodeid > ? AND node.nodeid <= ?
			AND node.parentid = ?
		", [$state->startAfter, $state->end, $this->getSpecialNodeChildNodeId('Visitor Messages')]);

		if (!$visitorMessages)
		{
			return $state->complete();
		}

		$this->lookup('user', $this->pluck($visitorMessages, ['userid', 'setfor', 'deleteuserid']));

		$stringFormatter = $this->app->stringFormatter();

		foreach ($visitorMessages AS $visitorMessage)
		{
			$oldId = $visitorMessage['nodeid'];
			$state->startAfter = $oldId;

			if (trim($visitorMessage['authorname']) === '')
			{
				continue;
			}

			if (!$profileUserId = $this->lookupId('user', $visitorMessage['setfor']))
			{
				continue;
			}

			$message = $stringFormatter->stripBbCode($visitorMessage['rawtext'], [
				'stripquote' => true,
				'hideUnviewable' => false
			]);

			if ($message === '')
			{
				continue;
			}

			/** @var \XF\Import\Data\ProfilePost $import */
			$import = $this->newHandler('XF:ProfilePost');
			$import->bulkSet([
				'profile_user_id' => $profileUserId,
				'user_id' => $this->lookupId('user', $visitorMessage['userid'], 0),
				'username' => $visitorMessage['authorname'],
				'post_date' => $visitorMessage['publishdate'],
				'message' => $message
			]);
			$import->setLoggedIp($visitorMessage['ipaddress']);

			if ($visitorMessage['unpublishdate'])
			{
				$import->message_state = 'deleted';
				$import->setDeletionLogData([
					'delete_date' => $visitorMessage['unpublishdate'],
					'delete_user_id' => $this->lookupId('user', $visitorMessage['deleteuserid'], 0),
					'delete_reason' => $visitorMessage['deletereason']
				]);
			}
			else if ($visitorMessage['approved'])
			{
				$import->message_state = 'visible';
			}
			else
			{
				$import->message_state = 'moderated';
			}

			// now do comments
			if ($visitorMessage['totalcount'])
			{
				$comments = $this->sourceDb->fetchAll("
					SELECT node.nodeid, node.userid, node.authorname, node.publishdate, node.ipaddress,
						node.deleteuserid, node.deletereason, node.unpublishdate, node.approved, text.rawtext
					FROM {$this->prefix}node AS
						node
					INNER JOIN {$this->prefix}text AS
						text ON(text.nodeid = node.nodeid)
					WHERE node.parentid = ?
					ORDER BY node.publishdate
				", $oldId);

				$this->lookup('user', $this->pluck($comments, ['userid', 'deleteuserid']));

				foreach ($comments AS $comment)
				{
					$commentMessage = $stringFormatter->stripBbCode($comment['rawtext'], [
						'stripquote' => true,
						'hideUnviewable' => false
					]);

					if ($commentMessage === '')
					{
						continue;
					}

					/** @var \XF\Import\Data\ProfilePostComment $importComment */
					$importComment = $this->newHandler('XF:ProfilePostComment');

					$importComment->comment_date = $comment['publishdate'];
					$importComment->user_id = $this->lookupId('user', $comment['userid'], 0);
					$importComment->username = $comment['authorname'];
					$importComment->message = $commentMessage;

					$importComment->setLoggedIp($comment['ipaddress']);

					if ($comment['unpublishdate'])
					{
						$importComment->message_state = 'deleted';

						$importComment->setDeletionLogData([
							'delete_date' => $comment['unpublishdate'],
							'delete_user_id' => $this->lookupId('user', $comment['deleteuserid'], 0),
							'delete_reason' => $comment['deletereason']
						]);
					}
					else if ($comment['approved'])
					{
						$importComment->message_state = 'visible';
					}
					else
					{
						$importComment->message_state = 'moderated';
					}

					$import->addComment($comment['nodeid'], $importComment);
				}
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

	// ########################### STEP: FORUMS ###############################

	protected function getForums(array $stepConfig)
	{
		if ($stepConfig['import_everything'])
		{
			/*
			 * This will query all 'Channels' that can contain threads, including pseudo-forums like 'Blogs', 'Groups'
			 * and 'Uncategorized Groups', as these could potentially  contain threads, so we'll grab them and treat
			 * them as forums for the sake of importing as much data as possible.
			 *
			 * Channels designated as thread containers will be imported with their 'Forum' parent,
			 * while blogs and groups will have their own container nodes.
			 */

			$queryConditions = "
				WHERE node.urlident NOT IN('special', 'vbcms-comments')
				AND node.parentid <> ? ";

			$queryParameters = [$this->getSpecialNodeId()];
		}
		else
		{
			/*
			 * This query will only fetch forums that are specifically designated as containing content type = 'Thread',
			 * namely those that are children of the 'Forum' channel (nodeid=2). It will ignore forum-like nodes, such
			 * as 'Blogs' and 'Groups', and will also not attempt to import the 'Forum' parent channel itself.
			 */

			$queryConditions = "
				INNER JOIN {$this->prefix}closure AS
					closure ON(closure.child = node.nodeid)
				WHERE node.nodeid <> 2
				AND node.urlident <> 'vbcms-comments'
				AND closure.parent = ? ";

			$queryParameters = [$this->getContentTypeId('Thread')];
		}

		// Select the data and vb3-ise it so we don't have to radically alter/extend the vb3 importer to work with it

		return $this->sourceDb->fetchAllKeyed("
			SELECT
				node.nodeid AS forumid,
				node.parentid, node.urlident, node.title, node.description, node.displayorder,
				node.textcount AS threadcount,
				node.totalcount - node.textcount AS replycount,
				node.lastcontent AS lastpost,
				node.lastcontentauthor AS lastposter,
				IF(node.displayorder > 0, 1, 0) + IF((channel.category = 1 OR node.parentid = 1), 0, 4) AS options
			FROM {$this->prefix}node AS
				node
			INNER JOIN {$this->prefix}channel AS
				channel ON(channel.nodeid = node.nodeid) {$queryConditions}
			AND channel.category <> IF(node.parentid = 1, 0, -1)
			ORDER BY node.parentid, node.displayorder
		", 'forumid', $queryParameters);
	}

	protected function getForumSubscribers($forumId)
	{
		return $this->sourceDb->fetchPairs("
			SELECT userid, emailupdate
			FROM {$this->prefix}subscribediscussion
			WHERE discussionid = ?
		", $forumId);
	}

	protected function setupForumSubscribeData($emailUpdate)
	{
		return [
			'notify_on' => 'thread',
			'send_alert' => true,
			'send_email' => false
		];
	}

	protected function getForumPermissions()
	{
		/*
		 * Query all permissions, and vB3-ise the result
		 */
		return $this->sourceDb->fetchAll("
			SELECT
				permissionid AS forumpermissionid,
				nodeid AS forumid,
				groupid AS usergroupid,
				forumpermissions
			FROM {$this->prefix}permission
		");

		// TODO: is this it?
	}

	protected function logExtraForumData(array $forum, $newNodeId)
	{
		$this->dataManager->log('node', $forum['urlident'], $newNodeId);
	}

	// ########################### STEP: MODERATORS ###############################

	protected function getModerators()
	{
		return $this->arrayKeyRename(parent::getModerators(), 'nodeid', 'forumid');
	}

	// ########################### STEP: PREFIXES ###############################

	protected function getPrefixSetForums()
	{
		$prefixSetForums = [];

		foreach ($this->sourceDb->fetchAll("SELECT * FROM {$this->prefix}channelprefixset") AS $f)
		{
			$prefixSetForums[$f['prefixsetid']][$f['nodeid']] = $f['nodeid'];
		}

		return $prefixSetForums;
	}

	// ########################### STEP: FEEDS ###############################

	protected function getFeeds()
	{
		return $this->arrayKeyRename(parent::getFeeds(), 'nodeid', 'forumid');
	}

	// ########################### STEP: THREADS ###############################

	public function getStepEndThreads()
	{
		$textType = $this->getContentTypeId('Text');
		$pollType = $this->getContentTypeId('Poll');
		$galleryType = $this->getContentTypeId('Gallery');

		$forumIds = $this->getDbQuotedImportedNodeIds();

		return $this->sourceDb->fetchOne("
			SELECT MAX(nodeid)
			FROM {$this->prefix}node
			WHERE nodeid <> 2
			AND parentid IN({$forumIds})
			AND contenttypeid IN(?, ?, ?)
		", [$textType, $pollType, $galleryType]) ?: 0;
	}

	protected function getImportedNodeIds()
	{
		return array_keys($this->typeMap('node'));
	}

	protected function getDbQuotedImportedNodeIds()
	{
		return $this->sourceDb->quote($this->getImportedNodeIds()) ?: [0];
	}

	protected function getThreads($startAfter, $end, $limit)
	{
		/*
		 * Fetch corresponding fields to vB3 as closely as possible
		 *
		 * threadid
		 * forumid
		 * postuserid
		 * postusername
		 * prefixid
		 * title
		 * replycount
		 * views
		 * sticky
		 * open
		 * lastpost
		 * visible
		 */

		$textType = $this->getContentTypeId('Text');
		$pollType = $this->getContentTypeId('Poll');
		$galleryType = $this->getContentTypeId('Gallery');

		$forumIds = $this->getDbQuotedImportedNodeIds();

		return $this->sourceDb->fetchAllKeyed("
			SELECT
				node.nodeid AS threadid,
				node.parentid AS forumid,
				node.userid AS postuserid,
				IF(user.username IS NULL, node.authorname, user.username) AS postusername,
				IF(node.unpublishdate, 2, IF(node.approved, 1, 0)) AS visible,
				IF(nodeview.count IS NULL, 0, nodeview.count) AS views,
				node.textcount AS replycount,
				node.publishdate AS dateline,
				node.lastcontent AS lastpost,
				node.title,
				node.prefixid,
				node.sticky,
				node.open
			FROM {$this->prefix}node AS
				node
			LEFT JOIN {$this->prefix}user AS
				user ON(user.userid = node.userid)
			LEFT JOIN {$this->prefix}nodeview AS
				nodeview ON(nodeview.nodeid = node.nodeid)			
			WHERE node.nodeid <> 2
			AND node.nodeid > ? AND node.nodeid <= ?
			AND node.contenttypeid IN(?, ?, ?)
			AND node.parentid IN({$forumIds})
			ORDER BY node.nodeid
			LIMIT {$limit}
		", 'threadid', [$startAfter, $end, $textType, $pollType, $galleryType]);
	}

	protected function getThreadSubscriptions(array $oldThreadIds)
	{
		$oldThreadIds = $this->sourceDb->quote($oldThreadIds);

		return $this->sourceDb->fetchPairs("
			SELECT
				discussionid AS threadid,
				userid, emailupdate
			FROM {$this->prefix}subscribediscussion
			WHERE discussionid IN({$oldThreadIds})
		");
	}

	// ########################### STEP: POSTS ###############################

	protected function getThreadIds($startAfter, $end, $threadLimit)
	{
		$redirectType = $this->getContentTypeId('Redirect');

		$forumIds = $this->getDbQuotedImportedNodeIds();

		return $this->sourceDb->fetchAllColumn("
			SELECT node.nodeid
			FROM {$this->prefix}node AS
				node
		#	INNER JOIN {$this->prefix}text AS text ON(text.nodeid = node.nodeid)
			WHERE node.nodeid > ? AND node.nodeid <= ?
			AND node.contenttypeid <> ?
			AND node.parentid IN({$forumIds})
			AND node.nodeid NOT IN({$forumIds})
			AND node.nodeid = node.starter
			ORDER BY node.nodeid
		", [$startAfter, $end, $redirectType]);
	}

	protected function getPosts($threadId, $startDate)
	{
		/*
		 * Fetch corresponding fields to vB3 as closely as possible
		 *
		 * postid
		 * threadid
		 * username
		 * userid
		 * dateline
		 * pagetext
		 * ipaddress
		 * visible
		 */

		return $this->sourceDb->fetchAll("
			SELECT
				node.nodeid AS postid,
				node.starter AS threadid,
				node.publishdate AS dateline,
				IF(user.username IS NULL, node.authorname, user.username) AS username,
				IF(node.unpublishdate, 2, IF(node.approved, 1, 0)) AS visible,
				node.title,
				node.userid,
				node.ipaddress,				
				text.rawtext AS pagetext,				
				editlog.dateline AS editdate,
				editlog.userid AS edituserid
			FROM {$this->prefix}node AS
				node
			INNER JOIN {$this->prefix}text AS
				text ON(text.nodeid = node.nodeid)
			LEFT JOIN {$this->prefix}user AS
				user ON(user.userid = node.userid)
			LEFT JOIN {$this->prefix}editlog AS
				editlog ON(editlog.nodeid = node.nodeid)
			WHERE node.starter = ?
			AND node.publishdate > ?
			ORDER BY node.publishdate
		", [$threadId, $startDate]);
	}

	protected function rewriteQuotes($text, $extraQuoteEqualsPatterns = [])
	{
		$quoteEqualsPatterns = [
			'/\[quote=("|\'|)(?P<username>[^;\n\]]*);\s*n?(?P<postid>\d+)\s*\1\]/siU' => function ($match)
			{
				return sprintf('[QUOTE="%s, post: %d]', $match['username'], $this->lookupId('post', $match['postid']));
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

	// ########################### STEP: TAGS ###############################

	public function getStepEndContentTags()
	{
		$forumIds = $this->getDbQuotedImportedNodeIds();

		return $this->sourceDb->fetchOne("
			SELECT MAX(nodeid)
			FROM {$this->prefix}node AS
				node
		#	INNER JOIN {$this->prefix}text USING(nodeid)
			WHERE node.taglist IS NOT NULL
			AND node.parentid IN({$forumIds})
			AND node.nodeid NOT IN({$forumIds})
			AND nodeid = starter
		") ?: 0;
	}

	protected function getThreadIdsWithTags($startAfter, $end, $limit)
	{
		$forumIds = $this->getDbQuotedImportedNodeIds();

		return $this->sourceDb->fetchPairs("
			SELECT
				node.nodeid AS threadid,
				node.publishdate AS dateline
			FROM {$this->prefix}node AS
				node
			WHERE node.nodeid > ? AND node.nodeid <= ?
			AND node.taglist IS NOT NULL
			AND node.parentid IN({$forumIds})
			AND node.nodeid NOT IN({$forumIds})
			AND node.nodeid = node.starter
			ORDER BY node.nodeid
			LIMIT {$limit}
		", [$startAfter, $end]);
	}

	protected function getThreadTags($threadId)
	{
		return $this->sourceDb->fetchAll("
			SELECT
				tagnode.*,
				tag.tagtext
			FROM {$this->prefix}tagnode AS
				tagnode
			INNER JOIN {$this->prefix}tag AS
				tag ON(tag.tagid = tagnode.tagid)
			WHERE tagnode.nodeid = ?
		", $threadId);
	}

	// ########################### STEP: POST EDIT HISTORY ###############################

	public function getStepEndPostEditHistory()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(nodeid) FROM {$this->prefix}postedithistory") ?: 0;
	}

	protected function getPostEditHistoryPostIds($startAfter, $end, $limit)
	{
		return $this->sourceDb->fetchAllColumn("
			SELECT DISTINCT nodeid
			FROM {$this->prefix}postedithistory
			WHERE nodeid > ? AND nodeid <= ?
			ORDER BY nodeid
			LIMIT {$limit}
		", [$startAfter, $end]);
	}

	protected function getPostEditHistoryEdits(array $postIds)
	{
		$postIds = $this->sourceDb->quote($postIds);

		return $this->sourceDb->fetchAll("
			SELECT postedithistoryid, userid, username, dateline, reason, pagetext,
				nodeid AS postid
			FROM {$this->prefix}postedithistory
			WHERE nodeid IN({$postIds})
			ORDER BY nodeid
		");
	}

	// ########################### STEP: POLLS ###############################

	public function getStepEndPolls()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(nodeid) FROM {$this->prefix}poll") ?: 0;
	}

	public function stepPolls(StepState $state, array $stepConfig, $maxTime, $limit = 500)
	{
		$timer = new \XF\Timer($maxTime);

		$polls = $this->sourceDb->fetchAllKeyed("
			SELECT poll.*, node.title
			FROM {$this->prefix}poll AS
				poll
			INNER JOIN {$this->prefix}node AS
				node ON(node.nodeid = poll.nodeid)
			WHERE poll.nodeid > ? AND poll.nodeid <= ?
			ORDER BY poll.nodeid
			LIMIT {$limit} 
		", 'nodeid', [$state->startAfter, $state->end]);

		if (!$polls)
		{
			return $state->complete();
		}

		$this->lookup('thread', array_keys($polls));

		foreach ($polls AS $oldId => $poll)
		{
			$state->startAfter = $oldId;

			if (!$newThreadId = $this->lookupId('thread', $poll['nodeid']))
			{
				continue;
			}

			/** @var \XF\Import\Data\Poll $import */
			$import = $this->newHandler('XF:Poll');
			$import->bulkSet([
				'content_type' => 'thread',
				'content_id' => $newThreadId,
				'question' => $poll['title'],
				'public_votes' => $poll['public'],
				'max_votes' => $poll['multiple'] ? 0 : 1,
				'close_date' => $poll['timeout']
			]);

			$responses = $this->sourceDb->fetchAll("
				SELECT pollvote.*, polloption.*
				FROM {$this->prefix}polloption AS
					polloption
				LEFT JOIN {$this->prefix}pollvote AS
					pollvote ON(pollvote.polloptionid = polloption.polloptionid)
				WHERE polloption.nodeid = ?
			", $oldId);

			$this->lookup('user', $this->pluck($responses, 'userid'));

			$importResponses = [];

			foreach ($responses AS $response)
			{
				$pollOptionId = $response['polloptionid'];

				if (!isset($importResponses[$pollOptionId]))
				{
					/** @var \XF\Import\Data\PollResponse $importResponse */
					$importResponse = $this->newHandler('XF:PollResponse');
					$importResponse->response = $response['title'];

					$importResponses[$pollOptionId] = $importResponse;
					$import->addResponse($pollOptionId, $importResponse);
				}

				if (!$voteUserId = $this->lookupId('user', $response['userid']))
				{
					continue;
				}

				$importResponses[$pollOptionId]->addVote($voteUserId, $response['votedate']);
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
		return $this->sourceDb->fetchOne("SELECT MAX(filedataid) FROM {$this->prefix}filedata") ?: 0;
	}

	protected function getAttachments($startAfter, $end, $limit)
	{
		/*
		 * For vB5, this actually only gets the filedata IDs with which we will be working,
		 * the meat of the task is performed by getAttachmentsGroupedByFile()
		 */

		return $this->sourceDb->fetchPairs("
			SELECT filedataid, userid
			FROM {$this->prefix}filedata
			WHERE filedataid > ? AND filedataid <= ?
			ORDER BY filedataid
			LIMIT {$limit}
		", [$startAfter, $end]);
	}

	protected function getAttachmentsGroupedByFile(array $attachments)
	{
		/*
		 * Note, although vB5 supports attachments/photos for visitor messages, galleries etc.,
		 * we will ignore these at this point.
		 */

		// TODO: '0 AS counter' is clearly wrong, but this is because vB5 doesn't appear to update the DB at all with attachment views...

		// TODO: Allow XFMG to import images from galleries that are not 'posts'?

		$fileDataIds = $this->sourceDb->quote(array_keys($attachments));

		$attachments = $this->sourceDb->fetchAll("
			SELECT
				#CONCAT(u.id, '-node', n.parentid) AS attachmentid,
				IF(u.filename IS NULL, CONCAT(u.id, '.', f.extension), u.filename) AS filename,
				n.userid AS userid,
				n.nodeid,
				n.parentid AS postid,
				f.filedataid AS attachmentid,
				f.filedataid,
				f.userid AS filedata_userid,
				f.dateline,
				0 AS counter
			FROM (
				SELECT a.filedataid, a.nodeid,
					IF (a.filename = '', NULL, a.filename) AS filename,
					CONCAT('attach', a.nodeid) AS id
					FROM {$this->prefix}attach AS a
				UNION
				SELECT p.filedataid, p.nodeid, NULL AS filename,
					CONCAT('photo', p.filedataid) AS id
					FROM {$this->prefix}photo AS p
			) AS u
			INNER JOIN {$this->prefix}filedata AS
				f ON(f.filedataid = u.filedataid)
			INNER JOIN {$this->prefix}node AS
				n ON(n.nodeid = u.nodeid)
			WHERE f.filedataid IN({$fileDataIds})
			ORDER BY f.filedataid
		");

		$this->lookup('user', $this->pluck($attachments, ['userid', 'filedata_userid']));
		$this->lookup('post', $this->pluck($attachments, 'postid'));

		$grouped = [];

		foreach ($attachments AS $a)
		{
			$grouped[$a['filedataid']][$a['postid']] = $a;
		}

		return $grouped;
	}

	protected function getAttachmentFileData($fileDataId)
	{
		return $this->sourceDb->fetchOne("
			SELECT filedata FROM {$this->prefix}filedata
			WHERE filedataid = ?
		", $fileDataId);
	}

	public function rewriteEmbeddedAttachments(\XF\Mvc\Entity\Entity $container, \XF\Entity\Attachment $attachment, $oldId)
	{
		if (isset($container->message))
		{
			$message = $container->message;

			$message = preg_replace_callback(
				"#(\[ATTACH=CONFIG\])temp_{$oldId}_\d{13}_\d{3}(\[/ATTACH\])#siU",
				function($match) use ($attachment, $container)
				{
					$id = $attachment->attachment_id;

					if (isset($container->embed_metadata))
					{
						$metadata = $container->embed_metadata;
						$metadata['attachments'][$id] = $id;

						$container->embed_metadata = $metadata;
					}

					return "[ATTACH]{$id}.vB5[/ATTACH]";
				},
				$message
			);

			$container->message = $message;
		}
	}

	// ########################### STEP: REPUTATION ###############################

	public function getStepEndReputation()
	{
		// TODO: *negative* reputation brought in from vB3/4 is treated as a (positive) 'like' by vB5... who are we to argue?

		return $this->sourceDb->fetchOne("SELECT MAX(reputationid) FROM {$this->prefix}reputation WHERE reputation") ?: 0;
	}

	protected function getReputations($startAfter, $end, $limit)
	{
		/*
		 * Likes/reputation can be applied to forum posts and visitor messages,
		 * so fetch both of these, and specify the content type accordingly
		 */

		$visitorMessageNodeId = $this->getSpecialNodeChildNodeId('Visitor Messages');

		return $this->sourceDb->fetchAllKeyed("
			SELECT
				reputation.*,
				reputation.nodeid AS contentid,
				IF(node.parentid = ?, 'profile_post', 'post') AS contenttype
			FROM {$this->prefix}reputation AS
				reputation
			INNER JOIN {$this->prefix}node AS
				node ON(node.nodeid = reputation.nodeid)
			WHERE reputation.reputationid > ? AND reputation.reputationid <= ?
			ORDER BY reputation.reputationid
			LIMIT {$limit}
		", 'reputationid', [$visitorMessageNodeId, $startAfter, $end]);
	}

	// ########################### STEP: INFRACTIONS ###############################

	public function getStepEndInfractions()
	{
		return $this->sourceDb->fetchOne("SELECT MAX(nodeid) FROM {$this->prefix}infraction") ?: 0;
	}

	protected function getInfractions($startAfter, $end, $limit)
	{
		/*
		 * vB3-ise this result as much as possible
		 *
		 * postid
		 * userid
		 * title
		 * thread_title
		 * note
		 * points
		 * whoadded
		 * action
		 * dateline
		 * expires
		 * username
		 */

		// TODO: infractions on profile posts, conversations? Are they even supported?

		return $this->sourceDb->fetchAllKeyed("
			SELECT
				infraction.nodeid AS infractionid,
				infraction.infractednodeid AS postid,
				infraction.infracteduserid AS userid,
				IF (phrase.text IS NULL, infraction.customreason, COALESCE(phrase.text, '')) AS title,
				COALESCE(node.title, '') AS thread_title,
				infraction.note,
				infraction.points,
				infraction.action,
				infraction.actiondateline AS dateline,
				infraction.expires,
				user.username AS username,
				node.userid AS whoadded
			FROM {$this->prefix}infraction AS
				infraction
			INNER JOIN {$this->prefix}node AS
				node ON(node.nodeid = infraction.nodeid)
			INNER JOIN {$this->prefix}user AS
				user ON(user.userid = infraction.infracteduserid)
			LEFT JOIN {$this->prefix}phrase AS
				phrase ON(phrase.varname = CONCAT('infractionlevel', infraction.infractionlevelid, '_title')
					AND phrase.languageid = 0)
			WHERE infraction.nodeid > ? AND infraction.nodeid <= ?
			ORDER BY infraction.nodeid
			LIMIT {$limit}
		", 'infractionid', [$startAfter, $end]);
	}

	// ########################### STEP: ANNOUNCEMENTS ###############################

	protected function getAnnouncements()
	{
		return $this->arrayKeyRename(parent::getAnnouncements(), 'nodeid', 'forumid');
	}
}