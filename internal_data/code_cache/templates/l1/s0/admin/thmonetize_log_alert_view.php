<?php
// FROM HASH: 6d5f8adbbf97065c798f69c618e99c27
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Alert log entry');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('users/edit', $__vars['entry']['User'], ), true) . '">' . $__templater->escape($__vars['entry']['User']['username']) . '</a>
			', array(
		'label' => 'User',
	)) . '
			' . $__templater->formRow('
				' . $__templater->func('date_dynamic', array($__vars['entry']['log_date'], array(
	))) . '
			', array(
		'label' => 'Date',
	)) . '
			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('monetize-alerts', $__vars['entry']['Alert'], ), true) . '">' . $__templater->escape($__vars['entry']['Alert']['title']) . '</a>
			', array(
		'label' => 'Alert',
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);