<?php

class XenForo_ControllerPublic_Tag extends XenForo_ControllerPublic_Abstract
{
	protected function _preDispatch($action)
	{
		if (!XenForo_Application::getOptions()->enableTagging)
		{
			throw $this->responseException($this->responseNoPermission());
		}
	}

	public function actionIndex()
	{
		$tagUrl = $this->_input->filterSingle('tag_url', XenForo_Input::STRING);
		if ($tagUrl)
		{
			return $this->responseReroute(__CLASS__, 'tag');
		}

		$tagModel = $this->_getTagModel();

		$tags = $this->_input->filterSingle('tags', XenForo_Input::STRING);

		if ($this->_request->isPost())
		{
			$tagList = $tagModel->splitTags($tags);
			if (!$tagList)
			{
				return $this->responseError(new XenForo_Phrase('please_enter_single_tag'));
			}

			if (count($tagList) == 1)
			{
				$tag = reset($tagList);
				$tagDetails = $tagModel->getTag($tag);
				if ($tagDetails)
				{
					return $this->responseRedirect(
						XenForo_ControllerResponse_Redirect::SUCCESS,
						XenForo_Link::buildPublicLink('tags', $tagDetails),
						''
					);
				}
				else
				{
					return $this->responseError(new XenForo_Phrase('following_tags_not_found_x', array('tags' => $tag)));
				}
			}

			if (!XenForo_Visitor::getInstance()->canSearch())
			{
				return $this->responseError(new XenForo_Phrase('please_enter_single_tag'));
			}

			$validTags = $tagModel->getTags($tagList, $notFound);
			if ($notFound)
			{
				return $this->responseError(new XenForo_Phrase('following_tags_not_found_x', array('tags' => implode(', ', $notFound))));
			}
			else
			{
				$tagConstraint = implode(' ', array_keys($validTags));

				$constraints = array(
					'tag' => $tagConstraint
				);

				/** @var XenForo_Model_Search $searchModel */
				$searchModel = $this->getModelFromCache('XenForo_Model_Search');
				$searcher = new XenForo_Search_Searcher($searchModel);
				$results = $searcher->searchGeneral('', $constraints, 'date');

				if (!$results)
				{
					return $this->responseMessage(new XenForo_Phrase('no_results_found'));
				}

				$search = $searchModel->insertSearch(
					$results, 'tag', '', array('tag' => $tagConstraint), 'date', false
				);

				return $this->responseRedirect(
					XenForo_ControllerResponse_Redirect::SUCCESS,
					XenForo_Link::buildPublicLink('search', $search),
					''
				);
			}
		}

		if (XenForo_Application::getOptions()->tagCloud['enabled'])
		{
			$tagCloud = $tagModel->getTagsForCloud(
				XenForo_Application::getOptions()->tagCloud['count'], XenForo_Application::getOptions()->tagCloudMinUses
			);
			$tagCloudLevels = $tagModel->getTagCloudLevels($tagCloud);
		}
		else
		{
			$tagCloud = array();
			$tagCloudLevels = array();
		}

		$viewParams = array(
			'tags' => $tags,
			'tagCloud' => $tagCloud,
			'tagCloudLevels' => $tagCloudLevels,
			'canSearch' => XenForo_Visitor::getInstance()->canSearch()
		);
		return $this->responseView('XenForo_ViewPublic_Tag_Search', 'tag_search', $viewParams);
	}

	public function actionTag()
	{
		$tagModel = $this->_getTagModel();

		$tagUrl = $this->_input->filterSingle('tag_url', XenForo_Input::STRING);
		$tag = $tagModel->getTagByUrl($tagUrl);
		if (!$tag)
		{
			return $this->responseError(new XenForo_Phrase('requested_tag_not_found'), 404);
		}

		$page = max(1, $this->_input->filterSingle('page', XenForo_Input::UINT));
		$perPage = XenForo_Application::getOptions()->searchResultsPerPage;

		$unpreparedResults = null;

		$cache = $tagModel->getTagResultsCache($tag['tag_id']);
		if ($cache)
		{
			$contentTags = json_decode($cache['results'], true);
		}
		else
		{
			$limit = XenForo_Application::getOptions()->maximumSearchResults;
			$contentTags = $tagModel->getContentIdsByTagId($tag['tag_id'], $limit);
			$insertCache = (count($contentTags) > $perPage); // if we would have more than one page, lets cache this to save work

			$contentTags = $tagModel->getViewableTagResults(array_values($contentTags), null, $unpreparedResults);
			if (!$contentTags)
			{
				return $this->responseMessage(new XenForo_Phrase('no_results_found'));
			}

			if ($insertCache)
			{
				$tagModel->insertTagResultsCache($tag['tag_id'], $contentTags);
			}
		}

		$totalResults = count($contentTags);

		$this->canonicalizePageNumber($page, $perPage, $totalResults, 'tags', $tag);
		$this->canonicalizeRequestUrl(
			XenForo_Link::buildPublicLink('tags', $tag, array('page' => $page))
		);

		$pageResultIds = array_slice($contentTags, ($page - 1) * $perPage, $perPage);

		if ($unpreparedResults)
		{
			// we already queried and filtered this data, we just need to filter it down and prepare it
			$results = $tagModel->finalizeUnpreparedResults($unpreparedResults, $pageResultIds);
		}
		else
		{
			$results = $tagModel->getTagResultsForDisplay($pageResultIds);
		}

		$resultStartOffset = ($page - 1) * $perPage + 1;
		$resultEndOffset = ($page - 1) * $perPage + count($pageResultIds);

		$ignoredNames = array();
		foreach ($results['results'] AS $result)
		{
			$content = $result['content'];
			if (!empty($content['isIgnored']) && !empty($content['user_id']) && !empty($content['username']))
			{
				$ignoredNames[$content['user_id']] = $content['username'];
			}
		}

		$viewParams = array(
			'tag' => $tag,
			'results' => $results,
			'ignoredNames' => $ignoredNames,

			'resultStartOffset' => $resultStartOffset,
			'resultEndOffset' => $resultEndOffset,

			'page' => $page,
			'perPage' => $perPage,
			'totalResults' => $totalResults
		);
		return $this->responseView('XenForo_ViewPublic_Tag_View', 'tag_view', $viewParams);
	}

	public static function getSessionActivityDetailsForList(array $activities)
	{
		return new XenForo_Phrase('viewing_tags');
	}

	/**
	 * @return XenForo_Model_Tag
	 */
	protected function _getTagModel()
	{
		return $this->getModelFromCache('XenForo_Model_Tag');
	}
}