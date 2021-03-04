<?php

namespace XF\Import\Importer;

class vBulletin4WithBlog extends vBulletin4
{
	use vBulletinBlogTrait, vBulletin4BlogTrait
	{
		vBulletin4BlogTrait::getBlogTags insteadof vBulletinBlogTrait;
	}

	public static function getListInfo()
	{
		return [
			'target' => 'XenForo',
			'source' => 'vBulletin 4.x with vBulletin Blog',
			'beta' => true
		];
	}
}