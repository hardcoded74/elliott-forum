<?php
// FROM HASH: faab5a68f96c8797d9675034d84a9184
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['uix_adminNotices'])) {
		foreach ($__vars['uix_adminNotices'] AS $__vars['notice']) {
			$__finalCompiled .= '
	<div class="blockMessage ' . $__templater->escape($__vars['notice']['class']) . ' blockMessage--iconic">
		' . $__templater->escape($__vars['notice']['message']) . '
	</div>
';
		}
	}
	return $__finalCompiled;
}
);