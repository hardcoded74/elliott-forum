<?php
// FROM HASH: 64fcb285b3a965c2e8eaa3514d17e813
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your post in the thread ' . $__templater->escape($__vars['extra']['sourceTitle']) . ' was moved to ' . ((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['targetLink'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('thread', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['title'])) . '</a>') . '.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);