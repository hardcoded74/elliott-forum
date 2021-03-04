<?php
// FROM HASH: e0f84b34a6102df726d9e168b3bb67ce
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
				' . 'Please confirm that you want to send the following message' . $__vars['xf']['language']['label_separator'] . '
				<strong><a href="' . $__templater->func('link', array('monetize-messages/edit', $__vars['message'], ), true) . '">' . $__templater->escape($__vars['message']['title']) . '</a></strong>
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
		'action' => $__templater->func('link', array('monetize-messages/send', $__vars['message'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);