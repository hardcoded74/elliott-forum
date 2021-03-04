<?php
// FROM HASH: 16af5cd366bbcc0b66e0b7572d75dbdb
return array(
'macros' => array('sponsor_below' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['node']['Sponsor']['active'] AND $__vars['node']['Sponsor']['title']) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('thmonetize_sponsor.less');
		$__finalCompiled .= '
		<dl class="node-thMonetizeSponsorBlock pairs pairs--justified">
			<dt>' . 'Sponsored by' . '</dt>
			<dd><a href="' . $__templater->escape($__vars['node']['Sponsor']['url']) . '">' . $__templater->escape($__vars['node']['Sponsor']['title']) . '</a></dd>
		</dl>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'sponsor_image' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['node']['Sponsor']['active'] AND $__vars['node']['Sponsor']['image']) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('thmonetize_sponsor.less');
		$__finalCompiled .= '
		<div class="node-thMonetizeSponsorImage" style="width: ' . $__templater->escape($__vars['node']['Sponsor']['width']) . 'px;">
			<a href="' . $__templater->escape($__vars['node']['Sponsor']['url']) . '"><img src="' . $__templater->escape($__vars['node']['Sponsor']['image']) . '" width="' . $__templater->escape($__vars['node']['Sponsor']['width']) . '" height="' . $__templater->escape($__vars['node']['Sponsor']['height']) . '"></a>
		</div>
	';
	}
	$__finalCompiled .= '
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