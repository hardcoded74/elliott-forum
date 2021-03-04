<?php

class XenForo_AdminSearchHandler_Option extends XenForo_AdminSearchHandler_Abstract
{
	protected function _getTemplateName()
	{
		return 'quicksearch_options';
	}

	public function getPhraseKey()
	{
		return 'options';
	}

	public function search($searchText, array $phraseMatches = null)
	{
		/* @var $optionModel XenForo_Model_Option */
		$optionModel = $this->getModelFromCache('XenForo_Model_Option');

		$options = $optionModel->getOptions(array('adminQuickSearch' =>
			array('searchText' => $searchText, 'phraseMatches' => $phraseMatches)
		));
		if (!$options)
		{
			return array();
		}

		$relations = $optionModel->getOptionRelationsGroupedByOption(array_keys($options));
		$groups = $optionModel->getOptionGroupList();

		foreach ($options AS $id => $option)
		{
			if (!isset($relations[$id]))
			{
				unset($options[$id]);
				continue;
			}

			$exists = false;
			foreach ($relations[$id] AS $groupId => $relation)
			{
				if (isset($groups[$groupId]))
				{
					$exists = true;
					break;
				}
			}

			if (!$exists)
			{
				unset($options[$id]);
			}
		}

		return $optionModel->prepareOptions($options);
	}

	public function getPhraseConditions()
	{
		return array(
			'like' => XenForo_Db::quoteLike('option_', 'r'),
			'regex' => '/^option_(.*)(_explain|_description|)?$/U'
		);
	}

	public function getAdminPermission()
	{
		return 'option';
	}
}