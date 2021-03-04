<?php
// FROM HASH: 287d1d26974086e9c8c4317c3fd66c2c
return array(
'macros' => array('style_change_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'styleTree' => '!',
		'route' => '!',
		'routeParams' => array(),
		'currentStyle' => null,
		'linkClass' => 'button button--link',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<a class="' . $__templater->escape($__vars['linkClass']) . ' menuTrigger"
	   data-xf-click="menu"
	   role="button"
	   tabindex="0"
	   aria-expanded="false"
	   aria-haspopup="true">' . 'Style' . $__vars['xf']['language']['label_separator'] . ' ' . ($__vars['currentStyle']['style_id'] ? $__templater->escape($__vars['currentStyle']['title']) : 'Default styling') . '</a>

	<div class="menu" data-menu="menu" aria-hidden="true">
		<div class="menu-content">
			<h3 class="menu-header">' . 'Styles' . '</h3>
			';
	$__compilerTemp1 = $__templater->method($__vars['styleTree'], 'getFlattened', array());
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['treeEntry']) {
			$__finalCompiled .= '
				<a href="' . $__templater->func('link', array($__vars['route'], '', array('style_id' => $__vars['treeEntry']['record']['style_id'], ), ), true) . '"
				   class="blockLink ' . (($__vars['currentStyle'] AND ($__vars['currentStyle']['style_id'] == $__vars['treeEntry']['record']['style_id'])) ? 'is-selected' : '') . '">
					<span class="u-depth' . $__templater->escape($__vars['treeEntry']['depth']) . '">' . ($__vars['treeEntry']['record']['style_id'] ? $__templater->escape($__vars['treeEntry']['record']['title']) : 'Default styling') . '</span>
				</a>
			';
		}
	}
	$__finalCompiled .= '
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);