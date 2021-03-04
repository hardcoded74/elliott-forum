<?php
// FROM HASH: 3de8dd1797b701adb22c4879f4e9d073
return array(
'macros' => array('renderSeparator' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'depth' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="thNodes_separator node--id' . $__templater->escape($__vars['node']['node_id']) . '" data-separatorType="' . $__templater->escape($__vars['extras']['separator']['separator_type']) . '"></div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
);