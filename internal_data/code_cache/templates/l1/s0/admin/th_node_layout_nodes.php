<?php
// FROM HASH: 1af17e985b300bb65b5cd7f6641b6e90
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Node layout and styling');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('
		' . 'Default grid options' . '
	', array(
		'href' => $__templater->func('link', array('node-layout/default-grid', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
	' . $__templater->button('
		' . 'Rebuild cache' . '
	', array(
		'href' => $__templater->func('link', array('node-layout/rebuild', ), false),
		'icon' => 'refresh',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['nodeTree'], 'countChildren', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">
			<div class="block-outer-main">
				' . $__templater->callMacro('th_style_macros_nodes', 'style_change_menu', array(
			'styleTree' => $__vars['styleTree'],
			'currentStyle' => $__vars['style'],
			'route' => 'node-layout',
		), $__vars) . '
			</div>
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'nodes',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp1 = '';
		$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
		if ($__templater->isTraversable($__compilerTemp2)) {
			foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
				$__compilerTemp1 .= '
						';
				$__vars['node'] = $__vars['treeEntry']['record'];
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
				), array(array(
					'class' => 'dataList-cell--link dataList-cell--main',
					'hash' => $__vars['node']['node_id'],
					'_type' => 'cell',
					'html' => '
								<a href="' . $__templater->func('link', array('node-layout', $__vars['node'], array('style_id' => $__vars['style']['style_id'], ), ), true) . '">
									<div class="u-depth' . $__templater->escape($__vars['treeEntry']['depth']) . '">
										<div class="dataList-mainRow">' . $__templater->escape($__vars['node']['title']) . ' <span class="dataList-hint" dir="auto">' . $__templater->escape($__vars['node']['NodeType']['title']) . '</span></div>
									</div>
								</a>
							',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__templater->method($__vars['nodeTree'], 'getFlattened', array(0, )), ), true) . '</span>
			</div>
		</div>
	</div>
	';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	return $__finalCompiled;
}
);