<?php
// FROM HASH: b44f08786cc98706dba24b7f28d3f0d2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('thmonetize_upgrade_page_macros', 'features', array(
		'features' => $__vars['upgrade']['thmonetize_features'],
		'styleProperties' => $__vars['upgrade']['thmonetize_style_properties'],
	), $__vars);
	return $__finalCompiled;
}
);