<?php

namespace XF\Like;

use XF\Entity\LikedContent;
use XF\Mvc\Entity\Entity;

class ConversationMessage extends AbstractHandler
{
	public function likesCounted(Entity $entity)
	{
		return false;
	}

	public function publishLikeNewsFeed(\XF\Entity\User $sender, $contentId, Entity $content)
	{
	}

	public function unpublishLikeNewsFeed(LikedContent $like)
	{

	}
}