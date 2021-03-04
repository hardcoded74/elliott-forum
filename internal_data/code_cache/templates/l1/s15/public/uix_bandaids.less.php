<?php
// FROM HASH: a88d51818067ac327f4df5dbc402e10a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// Added to fix the dark pixel bug https://github.com/Audentio/xf2theme-issues/issues/1055

.device--isAndroid .p-staffBar .hScroller-scroll {
	overflow-x: auto;
}

// Add persistent scrollbar on windows

.offCanvasMenu-content {
	overflow-y: scroll;
}

// remove bottom tabbar on mobile when keyboard is present

@media (max-height: 400px) {
	.uix_tabBar {display: none;}
}';
	return $__finalCompiled;
}
);