<?php
// FROM HASH: 0b2417d8878ca0b1988bd4f40ef5f56d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Contact us');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'autofocus' => 'autofocus',
			'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'username', ), false),
			'required' => 'required',
		), array(
			'label' => 'Your name',
			'hint' => 'Required',
		)) . '

				' . $__templater->formTextBoxRow(array(
			'name' => 'email',
			'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'email', ), false),
			'type' => 'email',
			'required' => 'required',
		), array(
			'label' => 'Your email address',
			'hint' => 'Required',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->formRow($__templater->escape($__vars['xf']['visitor']['username']), array(
			'label' => 'Your name',
		)) . '
				';
		if ($__vars['xf']['visitor']['email']) {
			$__compilerTemp1 .= '

					' . $__templater->formRow($__templater->escape($__vars['xf']['visitor']['email']), array(
				'label' => 'Your email address',
			)) . '

				';
		} else {
			$__compilerTemp1 .= '

					' . $__templater->formTextBoxRow(array(
				'name' => 'email',
				'type' => 'email',
				'required' => 'required',
			), array(
				'label' => 'Your email address',
				'hint' => 'Required',
			)) . '

				';
		}
		$__compilerTemp1 .= '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '

			' . $__templater->formRowIfContent($__templater->func('captcha', array($__vars['forceCaptcha'], false)), array(
		'label' => 'Verification',
		'hint' => 'Required',
		'force' => '$forceCaptcha',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'subject',
		'required' => 'required',
	), array(
		'label' => 'Subject',
		'hint' => 'Required',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'message',
		'rows' => '5',
		'autosize' => 'true',
		'required' => 'required',
	), array(
		'label' => 'Message',
		'hint' => 'Required',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Send',
	), array(
	)) . '
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('misc/contact', ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);