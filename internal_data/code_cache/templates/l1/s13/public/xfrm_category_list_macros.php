<?php
// FROM HASH: 27c99fc63d21edf5868166af27017346
return array(
'macros' => array('simple_category_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'selected' => '0',
		'pathToSelected' => array(),
		'children' => '!',
		'extras' => '!',
		'isActive' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ol class="categoryList toggleTarget' . ($__vars['isActive'] ? ' is-active' : '') . '">
		';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['id'] => $__vars['child']) {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'simple_category_list_item', array(
				'selected' => $__vars['selected'],
				'pathToSelected' => $__vars['pathToSelected'],
				'category' => $__vars['child']['record'],
				'extras' => $__vars['extras'][$__vars['id']],
				'children' => $__vars['child'],
				'childExtras' => $__vars['extras'],
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '
	</ol>
';
	return $__finalCompiled;
}
),
'simple_category_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'selected' => '!',
		'pathToSelected' => array(),
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['isSelected'] = ($__vars['category']['resource_category_id'] == $__vars['selected']);
	$__finalCompiled .= '
	';
	$__vars['hasPathToSelected'] = $__vars['pathToSelected'][$__vars['category']['resource_category_id']];
	$__finalCompiled .= '
	';
	$__vars['isActive'] = ($__vars['isSelected'] OR ($__vars['hasPathToSelected'] AND !$__templater->test($__vars['children'], 'empty', array())));
	$__finalCompiled .= '

	<li class="categoryList-item">
		<div class="categoryList-itemRow">
			';
	if (!$__templater->test($__vars['children'], 'empty', array())) {
		$__finalCompiled .= '
				<a class="categoryList-toggler' . ($__vars['isActive'] ? ' is-active' : '') . '"
					data-xf-click="toggle" data-target="< :up :next"
					role="button" tabindex="0" aria-label="' . 'Toggle expanded' . '"
				></a>
			';
	} else {
		$__finalCompiled .= '
				<span class="categoryList-togglerSpacer"></span>
			';
	}
	$__finalCompiled .= '
			<a href="' . $__templater->func('link', array('resources/categories', $__vars['category'], ), true) . '" class="categoryList-link' . ($__vars['isSelected'] ? ' is-selected' : '') . '">
				' . $__templater->escape($__vars['category']['title']) . '
			</a>
			<span class="categoryList-label">
				<span class="label label--subtle label--smallest">' . $__templater->filter($__vars['extras']['resource_count'], array(array('number_short', array()),), true) . '</span>
			</span>
		</div>
		';
	if (!$__templater->test($__vars['children'], 'empty', array())) {
		$__finalCompiled .= '
			' . $__templater->callMacro(null, 'simple_category_list', array(
			'selected' => $__vars['selected'],
			'pathToSelected' => $__vars['pathToSelected'],
			'children' => $__vars['children'],
			'extras' => $__vars['childExtras'],
			'isActive' => $__vars['isActive'],
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
	</li>
';
	return $__finalCompiled;
}
),
'simple_list_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'categoryTree' => '!',
		'categoryExtras' => '!',
		'selected' => 0,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-minorHeader">' . 'Categories' . '</h3>
			<div class="block-body">
				';
	if ($__templater->method($__vars['categoryTree'], 'count', array())) {
		$__finalCompiled .= '
					' . $__templater->callMacro(null, 'simple_category_list', array(
			'children' => $__vars['categoryTree'],
			'extras' => $__vars['categoryExtras'],
			'isActive' => true,
			'selected' => $__vars['selected'],
			'pathToSelected' => ($__vars['selected'] ? $__templater->method($__vars['categoryTree'], 'getPathTo', array($__vars['selected'], )) : array()),
		), $__vars) . '
				';
	} else {
		$__finalCompiled .= '
					<div class="block-row">' . 'N/A' . '</div>
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'category' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'chooseName' => '',
		'bonusInfo' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	<div class="node node--depth2 node--resourceCategory node--resourceCategory' . $__templater->escape($__vars['category']['resource_category_id']) . '">
		<div class="node-body">
			<div class="node-main js-nodeMain">
				';
	if ($__vars['chooseName']) {
		$__finalCompiled .= '
					' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'labelclass' => 'u-pullRight',
			'class' => 'js-chooseItem',
			'name' => $__vars['chooseName'] . '[]',
			'value' => $__vars['category']['resource_category_id'],
			'_type' => 'option',
		))) . '
				';
	}
	$__finalCompiled .= '

				';
	$__vars['descriptionDisplay'] = $__templater->func('property', array('nodeListDescriptionDisplay', ), false);
	$__finalCompiled .= '
				<h3 class="node-title">
					<a href="' . $__templater->func('link', array('resources/categories', $__vars['category'], ), true) . '" data-xf-init="' . (($__vars['descriptionDisplay'] == 'tooltip') ? 'element-tooltip' : '') . '" data-shortcut="node-description">' . $__templater->escape($__vars['category']['title']) . '</a>
				</h3>
				';
	if (($__vars['descriptionDisplay'] != 'none') AND $__vars['category']['description']) {
		$__finalCompiled .= '
					<div class="node-description ' . (($__vars['descriptionDisplay'] == 'tooltip') ? 'node-description--tooltip js-nodeDescTooltip' : '') . '">' . $__templater->filter($__vars['category']['description'], array(array('raw', array()),), true) . '</div>
				';
	}
	$__finalCompiled .= '

				<div class="node-meta">
					<div class="node-statsMeta">
						<dl class="pairs pairs--inline">
							<dt>' . 'xfrm_resources' . '</dt>
							<dd>' . $__templater->filter($__vars['extras']['resource_count'], array(array('number', array()),), true) . '</dd>
						</dl>
					</div>
				</div>

				';
	if (!$__templater->test($__vars['bonusInfo'], 'empty', array())) {
		$__finalCompiled .= '
					<div class="node-bonus">' . $__templater->escape($__vars['bonusInfo']) . '</div>
				';
	}
	$__finalCompiled .= '
			</div>

			<div class="node-stats node-stats--single">
				<dl class="pairs pairs--rows">
					<dt>' . 'xfrm_resources' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['resource_count'], array(array('number', array()),), true) . '</dd>
				</dl>
			</div>

			<div class="node-extra">
				';
	if ($__vars['extras']['last_update']) {
		$__finalCompiled .= '
					<div class="node-extra-row"><a href="' . $__templater->func('link', array('resources', array('resource_id' => $__vars['extras']['last_resource_id'], ), ), true) . '" class="node-extra-title" title="' . $__templater->escape($__vars['extras']['last_resource_title']) . '">' . $__templater->escape($__vars['extras']['last_resource_title']) . '</a></div>
					<div class="node-extra-row">
						' . $__templater->func('date_dynamic', array($__vars['extras']['last_update'], array(
			'class' => 'node-extra-date',
		))) . '
					</div>
				';
	} else {
		$__finalCompiled .= '
					<span class="node-extra-placeholder">' . 'None' . '</span>
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>
	
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);