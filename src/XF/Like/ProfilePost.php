<?php

namespace XF\Like;

use XF\Mvc\Entity\Entity;

class ProfilePost extends AbstractHandler
{
	public function likesCounted(Entity $entity)
	{
		return ($entity->message_state == 'visible');
	}

	public function getEntityWith()
	{
		return ['ProfileUser', 'ProfileUser.Privacy'];
	}
}