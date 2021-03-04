<?php
// FROM HASH: b46f9f5661d162093958e822f4a10b7c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// FONT AWESOME WIDTHS
// these are icons that we reference directly using .fa-{icon} classes in our CSS
// so these definitions allow us to specify min-widths to avoid flicker / stutter
/* TODO: Needs updating for FA5
html {
	.fa-angle-down:before { .m-faContent(@fa-var-angle-down, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-down',
	), $__vars) . ';}
	.fa-angle-up:before { .m-faContent(@fa-var-angle-up, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-up',
	), $__vars) . ' ;}
	.fa-arrow-up:before { .m-faContent(@fa-var-arrow-up, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'arrow-up',
	), $__vars) . ';}
	.fa-arrow-down:before { .m-faContent(@fa-var-arrow-down, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'arrow-down',
	), $__vars) . ';}
	.fa-bar-chart:before { .m-faContent(@fa-var-bar-chart, 1.15em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'poll',
	), $__vars) . ';}
	.fa-bars:before { .m-faContent(@fa-var-bars, 0.86em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'menu',
	), $__vars) . ';}
	.fa-caret-left:before { .m-faContent(@fa-var-caret-left, 0.36em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'menu-left',
	), $__vars) . ';}
	.fa-caret-right:before { .m-faContent(@fa-var-caret-right, 0.36em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'menu-right',
	), $__vars) . ';}
	.fa-clock-o:before { .m-faContent(@fa-var-clock-o, 0.86em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'clock',
	), $__vars) . '}
	.fa-cog:before { .m-faContent(@fa-var-cog, 0.86em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'settings',
	), $__vars) . ';}
	.fa-cogs:before { .m-faContent(@fa-var-cogs, 1.08em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'settings',
	), $__vars) . ';}
	.fa-comments:before { .m-faContent(@fa-var-comments, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'comment-multiple',
	), $__vars) . ';}
	.fa-file-o:before { .m-faContent(@fa-var-file-o, 0.86em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'file',
	), $__vars) . ';}
	.fa-globe:before { .m-faContent(@fa-var-globe, 0.86em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'earth',
	), $__vars) . ';}
	.fa-home:before { .m-faContent(@fa-var-home, 0.93em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'home',
	), $__vars) . ';}
	.fa-key:before { .m-faContent(@fa-var-key, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'login',
	), $__vars) . ';}
	.fa-paint-brush:before { .m-faContent(@fa-var-paint-brush, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'brush',
	), $__vars) . ';}
	.fa-pencil:before { .m-faContent(@fa-var-pencil, 0.86em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'edit',
	), $__vars) . ' ;}
	.fa-rss:before { .m-faContent(@fa-var-rss, 0.79em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'rss',
	), $__vars) . ';}
	.fa-search:before { .m-faContent(@fa-var-search, 0.93em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'search',
	), $__vars) . ';}
	.fa-tags:before { .m-faContent(@fa-var-tags, 1.08em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'tag-multiple',
	), $__vars) . ';}
	.fa-th:before { .m-faContent(@fa-var-th, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'tag-multiple',
	), $__vars) . ';}
	.fa-thumbs-up:before { .m-faContent(@fa-var-thumbs-up, 0.93em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'like',
	), $__vars) . ';}
	.fa-user:before { .m-faContent(@fa-var-user, 0.72em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'user',
	), $__vars) . ';}
	.fa-warning:before { .m-faContent(@fa-var-warning, 1em); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'warning',
	), $__vars) . ';}
}*/';
	return $__finalCompiled;
}
);