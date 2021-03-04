<?php
// FROM HASH: 9c3b1d371bed55b8b5fcc72beb56ed5b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Email log entry');
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
				<a href="' . $__templater->func('link', array('monetize-emails', $__vars['entry']['Email'], ), true) . '">' . $__templater->escape($__vars['entry']['Email']['title']) . '</a>
			', array(
		'label' => 'Email',
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);