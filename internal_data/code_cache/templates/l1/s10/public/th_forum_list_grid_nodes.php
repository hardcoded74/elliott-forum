<?php
// FROM HASH: 9da35ef771fec12e6509f615ecda631e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'src' => 'themehouse/global/20180112.js',
		'min' => 'themehouse/global/20180112.js',
	));
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'src' => 'themehouse/nodes/index.js',
		'min' => 'themehouse/nodes/index.js',
		'addon' => 'ThemeHouse/Nodes',
	));
	$__finalCompiled .= '

';
	$__templater->inlineJs('
	' . $__templater->func('th_grid_config_nodes', array(), false) . '

	window.themehouse.nodes.ele = new window.themehouse.nodes.grid({
		layout: window.themehouse.nodes.grid_options,
		settings: {

		},
	});

	window.themehouse.nodes.ele.register();
');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
);