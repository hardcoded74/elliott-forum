<?php

namespace XF\Import\Data;

class vBulletinBlogEntry extends Thread
{
	public function getImportType()
	{
		return 'blog';
	}

	protected $blogText = null;
	protected $blogTextId = null;
	protected $loggedIp = null;

	protected function getPostHandler()
	{
		if ($this->postHandler === null)
		{
			$this->postHandler = $this->dataManager->newHandler('XF:Post');
			$this->postHandler->preventRetainIds();
		}

		return $this->postHandler;
	}

	/**
	 * @param $text
	 * @param $id ID from the blog_text table
	 */
	public function setBlogText($text, $id)
	{
		$this->blogText = $text;
		$this->blogTextId = $id;
	}

	public function setLoggedIp($ipaddress)
	{
		$this->loggedIp = $ipaddress;
	}

	protected function preSave($oldId)
	{
		if ($this->blogTextId === null || $this->blogText === null)
		{
			throw new \LogicException("Must set blog text ID and content using setBlogText(id, text) before saving");
		}

		if ($this->loggedIp === null)
		{
			throw new \LogicException("Must log an IP using setLoggedIp() before saving");
		}

		return parent::preSave($oldId);
	}

	protected function postSave($oldId, $newId)
	{
		/** @var \XF\Import\Data\vBulletinBlogText $postHandler */
		$postHandler = $this->dataManager->newHandler('XF:vBulletinBlogText');
		$postHandler->preventRetainIds();
		$postHandler->bulkSet([
			'thread_id' => $newId,
			'post_date' => $this->post_date,
			'user_id' => $this->user_id,
			'username' => $this->username,
			'message' => $this->blogText,
			'position' => 0
		]);
		$postHandler->setLoggedIp($this->loggedIp);
		$postHandler->save($this->blogTextId);

		return parent::postSave($oldId, $newId);
	}
}