<?php
// FROM HASH: 99fd1e5c51f9002650bba4b9f36d66df
return array(
'macros' => array('notice' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'log' => '!',
		'message' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ul class="listInline listInline--bullet listInline--selfInline">
		';
	if (!$__templater->test($__vars['message'], 'empty', array())) {
		$__finalCompiled .= '
			<li>' . $__templater->escape($__vars['message']) . '</li>
		';
	}
	$__finalCompiled .= '
		<li>' . 'Deleted by ' . ($__templater->escape($__vars['log']['delete_username']) ?: 'N/A') . '' . '</li>
		';
	if ($__vars['log']) {
		$__finalCompiled .= '
			<li>' . $__templater->func('date_dynamic', array($__vars['log']['delete_date'], array(
		))) . '</li>
			';
		if ($__vars['log']['delete_reason']) {
			$__finalCompiled .= '
				<li>' . 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['log']['delete_reason']) . '</li>
			';
		}
		$__finalCompiled .= '
		';
	}
	$__finalCompiled .= '
	</ul>		
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