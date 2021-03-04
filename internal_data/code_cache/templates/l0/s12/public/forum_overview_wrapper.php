<?php
// FROM HASH: c2794418ee26ce2c1dd63c4d6aa80796
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callAdsMacro('forum_overview_top', array(), $__vars) . '
' . $__templater->widgetPosition('forum_overview_top', array()) . '

';
	if ($__templater->func('property', array('uix_removeIndexPageTitle', ), false)) {
		$__finalCompiled .= '
	';
		$__templater->setPageParam('uix_hidePageTitle', '1');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->func('property', array('uix_removeWhatsNewButtons', ), false)) {
		$__compilerTemp1 .= '
		';
		if ($__vars['pageSelected'] == 'new_posts') {
			$__compilerTemp1 .= '
			' . $__templater->button('
				' . 'Forum list' . '
			', array(
				'href' => $__templater->func('link', array('forums/list', ), false),
				'icon' => 'list',
			), '', array(
			)) . '
		';
		} else {
			$__compilerTemp1 .= '
			' . $__templater->button('
				' . 'New posts' . '
			', array(
				'href' => (($__vars['xf']['options']['forumsDefaultPage'] == 'new_posts') ? $__templater->func('link', array('forums/new-posts', ), false) : $__templater->func('link', array('whats-new/posts', ), false)),
				'icon' => 'bolt',
			), '', array(
			)) . '
		';
		}
		$__compilerTemp1 .= '
	';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canCreateThread', array()) OR $__templater->method($__vars['xf']['visitor'], 'canCreateThreadPreReg', array())) {
		$__compilerTemp2 .= '
		' . $__templater->button('
			' . 'Post thread' . $__vars['xf']['language']['ellipsis'] . '
		', array(
			'href' => $__templater->func('link', array('forums/create-thread', ), false),
			'class' => 'button--cta',
			'icon' => 'write',
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__compilerTemp1 . '
	' . $__compilerTemp2 . '
');
	$__finalCompiled .= '

' . $__templater->filter($__vars['innerContent'], array(array('raw', array()),), true) . '

' . $__templater->callAdsMacro('forum_overview_bottom', array(), $__vars) . '
' . $__templater->widgetPosition('forum_overview_bottom', array());
	return $__finalCompiled;
}
);