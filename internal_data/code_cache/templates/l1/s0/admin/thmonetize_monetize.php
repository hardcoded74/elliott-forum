<?php
// FROM HASH: e9f0cdd5e58d7fa8a723deacb47f4afb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Monetize');
	$__finalCompiled .= '

' . $__templater->callMacro('section_nav_macros', 'section_nav', array(
		'section' => 'thMonetize',
	), $__vars);
	return $__finalCompiled;
}
);