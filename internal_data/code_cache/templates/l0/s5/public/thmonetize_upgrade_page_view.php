<?php
// FROM HASH: 8e990f5aa2171a3eaefddae6e8ef7164
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['upgradePage']['title']));
	$__finalCompiled .= '

';
	$__templater->includeCss('thmonetize_upgrade_page.less');
	$__finalCompiled .= '

' . $__templater->callMacro('thmonetize_upgrade_page_macros', 'upgrade_options', array(
		'upgradePage' => $__vars['upgradePage'],
		'upgrades' => $__vars['upgrades'],
		'profiles' => $__vars['profiles'],
	), $__vars);
	return $__finalCompiled;
}
);