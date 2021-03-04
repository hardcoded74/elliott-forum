<?php

namespace XF\Import\Data;

class vBulletinBlogAttachment extends Attachment
{
	public function getImportType()
	{
		return 'blog_attachment';
	}
}