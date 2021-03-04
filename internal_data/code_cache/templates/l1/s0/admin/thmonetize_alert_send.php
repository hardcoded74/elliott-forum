<?php
// FROM HASH: 8755909764bac5d54d7f466e4133c4c1
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
				' . 'Please confirm that you want to send the following alert' . $__vars['xf']['language']['label_separator'] . '
				<strong><a href="' . $__templater->func('link', array('monetize-alerts/edit', $__vars['message'], ), true) . '">' . $__templater->escape($__vars['alert']['title']) . '</a></strong>
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
		'action' => $__templater->func('link', array('monetize-alerts/send', $__vars['alert'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);