<?php

namespace XF\Import\Importer;

use XF\Import\StepState;

trait vBulletin4BlogTrait
{
	// ########################### STEP: BLOG TAGS ###############################

	protected function getBlogTags($blogId)
	{
		return $this->sourceDb->fetchAll("
			SELECT tagcontent.*, tag.tagtext
			FROM {$this->prefix}tagcontent AS
				tagcontent
			INNER JOIN {$this->prefix}tag AS
				tag ON(tag.tagid = tagcontent.tagid)
			WHERE tagcontent.contentid = ?
			AND tagcontent.contenttypeid = ?
		", [$blogId, $this->getContentTypeId('BlogEntry')]);
	}
}