<?php
// FROM HASH: c30448be06756b4d5c078acc7727858d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to send the following email' . $__vars['xf']['language']['label_separator'] . '
				<strong><a href="' . $__templater->func('link', array('monetize-emails/edit', $__vars['message'], ), true) . '">' . $__templater->escape($__vars['email']['title']) . '</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Send now',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('monetize-emails/send', $__vars['email'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);