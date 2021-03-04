<?php
// FROM HASH: 92c2897a0fd5493cf5a8d6e4400ed9d3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.avatar.avatar--resourceIconDefault
{
	color: xf-default(@xf-textColorMuted, black) !important;
	background: mix(xf-default(@xf-textColorMuted, black), xf-default(@xf-avatarBg, white), 25%) !important;
	text-align: center;

	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;

	> span:before
	{
		.m-faBase();
		.m-faContent(@fa-var-cog);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'settings',
	), $__vars) . '
	}
}';
	return $__finalCompiled;
}
);