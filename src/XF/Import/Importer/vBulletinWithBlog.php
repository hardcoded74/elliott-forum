<?php

namespace XF\Import\Importer;

class vBulletinWithBlog extends vBulletin
{
	use vBulletinBlogTrait;

	public static function getListInfo()
	{
		return [
			'target' => 'XenForo',
			'source' => 'vBulletin 3.7, 3.8 with vBulletin Blog',
			'beta' => true
		];
	}
}