<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 ?><?php

class XenForo_ControllerAdmin_Tag extends XenForo_ControllerAdmin_Abstract
{
	protected function _preDispatch($action)
	{
		$this->assertAdminPermission('tag');
	}

	public function actionIndex()
	{
		$page = max(1, $this->_input->filterSingle('page', XenForo_Input::UINT));
		$perPage = 50;

		$tagModel = $this->_getTagModel();

		$containing = $this->_input->filterSingle('containing', XenForo_Input::STRING);
		$order = $this->_input->filterSingle('order', XenForo_Input::STRING);

		$total = $tagModel->countTagList($containing);
		$this->canonicalizePageNumber($page, $perPage, $total, 'tags');

		$tags = $tagModel->getTagList($containing, array(
			'order' => $order,
			'page' => $page,
			'perPage' => $perPage
		));

		$viewParams = array(
			'tags' => $tags,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			'containing' => $containing,
			'order' => $order
		);
		return $this->responseView('XenForo_ViewAdmin_Tag_List', 'tag_list', $viewParams);
	}

	protected function _getTagAddEditResponse(array $tag)
	{
		$viewParams = array(
			'tag' => $tag
		);
		return $this->responseView('XenForo_ViewAdmin_Tag_Edit', 'tag_edit', $viewParams);
	}

	public function actionAdd()
	{
		return $this->_getTagAddEditResponse(array(
			'tag' => '',
			'tag_url' => '',
			'permanent' => 1
		));
	}

	public function actionEdit()
	{
		$tagId = $this->_input->filterSingle('tag_id', XenForo_Input::UINT);
		$tag = $this->_getTagOrError($tagId);

		return $this->_getTagAddEditResponse($tag);
	}

	public function actionSave()
	{
		$this->_assertPostOnly();

		$tagId = $this->_input->filterSingle('tag_id', XenForo_Input::UINT);
		$dwData = $this->_input->filter(array(
			'tag' => XenForo_Input::STRING,
			'tag_url' => XenForo_Input::STRING,
			'permanent' => XenForo_Input::BOOLEAN
		));

		$dw = XenForo_DataWriter::create('XenForo_DataWriter_Tag');
		if ($tagId)
		{
			$dw->setExistingData($tagId);
		}
		$dw->bulkSet($dwData);
		$dw->save();

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('tags')
		);
	}

	public function actionDelete()
	{
		if ($this->isConfirmedPost())
		{
			return $this->_deleteData(
				'XenForo_DataWriter_Tag', 'tag_id',
				XenForo_Link::buildAdminLink('tags')
			);
		}
		else
		{
			$tagId = $this->_input->filterSingle('tag_id', XenForo_Input::UINT);
			$tag = $this->_getTagOrError($tagId);

			$viewParams = array(
				'tag' => $tag
			);

			return $this->responseView('XenForo_ViewAdmin_Tag_Delete', 'tag_delete', $viewParams);
		}
	}

	public function actionMerge()
	{
		$tagId = $this->_input->filterSingle('tag_id', XenForo_Input::UINT);
		$tag = $this->_getTagOrError($tagId);

		if ($this->isConfirmedPost())
		{
			$targetName = $this->_input->filterSingle('target', XenForo_Input::STRING);
			$targetTag = $this->_getTagModel()->getTag($targetName);
			if (!$targetTag)
			{
				return $this->responseError(new XenForo_Phrase('requested_tag_not_found'));
			}

			if ($targetTag['tag_id'] == $tag['tag_id'])
			{
				return $this->responseError(new XenForo_Phrase('you_may_not_merge_tag_with_itself'));
			}

			$this->_getTagModel()->mergeTags($tag['tag_id'], $targetTag['tag_id']);

			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildAdminLink('tags')
			);
		}
		else
		{
			$viewParams = array(
				'tag' => $tag
			);

			return $this->responseView('XenForo_ViewAdmin_Tag_Merge', 'tag_merge', $viewParams);
		}
	}

	/**
	 * @param integer $tagId
	 *
	 * @return array
	 */
	protected function _getTagOrError($tagId)
	{
		$tag = $this->_getTagModel()->getTagById($tagId);
		if (!$tag)
		{
			throw $this->responseException($this->responseError(new XenForo_Phrase('requested_tag_not_found'), 404));
		}

		return $tag;
	}

	/**
	 * @return  XenForo_Model_Tag
	 */
	protected function _getTagModel()
	{
		return $this->getModelFromCache('XenForo_Model_Tag');
	}
}