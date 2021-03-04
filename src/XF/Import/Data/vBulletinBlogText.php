<?php

namespace XF\Import\Data;

class vBulletinBlogText extends Post
{
	public function getImportType()
	{
		return 'blog_text';
	}
}