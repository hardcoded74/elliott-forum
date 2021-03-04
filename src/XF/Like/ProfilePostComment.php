<?php

namespace XF\Like;

use XF\Mvc\Entity\Entity;

class ProfilePostComment extends AbstractHandler
{
	public function likesCounted(Entity $entity)
	{
		return ($entity->message_state == 'visible');
	}
}