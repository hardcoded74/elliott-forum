<?php
// FROM HASH: d0723385c486c156a59075350a62eac5
return array(
'macros' => array('upgrade_page' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'upgradePage' => '!',
		'upgrades' => '!',
		'profiles' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('thmonetize_upgrade_page.less');
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'themehouse/monetize/structure.js',
		'min' => 'themehouse/monetize/structure.min.js',
		'addon' => 'ThemeHouse/Monetize',
	));
	$__finalCompiled .= '
	<div style="display: none">
		<div class="block"
			data-xf-init="thmonetize_notice-overlay"
			data-dismissible="' . ($__vars['upgradePage']['overlay_dismissible'] ? 'true' : 'false') . '"
			>
			<div class="overlay-title">' . $__templater->escape($__vars['upgradePage']['title']) . '</div>
			' . $__templater->callMacro('thmonetize_upgrade_page_macros', 'upgrade_options', array(
		'upgradePage' => $__vars['upgradePage'],
		'upgrades' => $__vars['upgrades'],
		'profiles' => $__vars['profiles'],
	), $__vars) . '
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['thmonetize_upgradePage'] AND ($__vars['thmonetize_upgrades'] AND $__vars['thmonetize_profiles'])) {
		$__finalCompiled .= '
	' . $__templater->callMacro(null, 'upgrade_page', array(
			'upgradePage' => $__vars['thmonetize_upgradePage'],
			'upgrades' => $__vars['thmonetize_upgrades'],
			'profiles' => $__vars['thmonetize_profiles'],
		), $__vars) . '
';
	}
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);