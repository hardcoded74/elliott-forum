<?php
// FROM HASH: eedf911488023e988342c71da6e9ede8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Message log entry');
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
				<a href="' . $__templater->func('link', array('monetize-messages', $__vars['entry']['Message'], ), true) . '">' . $__templater->escape($__vars['entry']['Message']['title']) . '</a>
			', array(
		'label' => 'Message',
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);