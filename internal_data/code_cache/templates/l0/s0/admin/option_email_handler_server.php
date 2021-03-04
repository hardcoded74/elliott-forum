<?php
// FROM HASH: b444a31cfe95aa28033965a4d034f20c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['option']['title']));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->filter($__vars['type'], array(array('to_upper', array()),), true) . '
				' . $__templater->formHiddenVal('type', $__vars['type'], array(
	)) . '
			', array(
		'label' => 'Connection type',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formTextBox(array(
		'name' => 'host',
		'placeholder' => 'Host',
		'size' => '40',
		'required' => 'required',
	)) . '
					<span class="inputGroup-text">:</span>
					' . $__templater->formTextBox(array(
		'name' => 'port',
		'placeholder' => 'Port',
		'size' => '5',
		'required' => 'required',
	)) . '
				</div>
			', array(
		'label' => 'Host',
		'rowtype' => 'input',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formTextBox(array(
		'name' => 'username',
		'placeholder' => 'Username',
		'size' => '15',
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formTextBox(array(
		'name' => 'password',
		'placeholder' => 'Password',
		'autocomplete' => 'new-password',
		'type' => 'password',
		'size' => '15',
	)) . '
				</div>
			', array(
		'label' => 'Username and password',
		'rowtype' => 'input',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'encryption',
		'value' => '',
	), array(array(
		'value' => '',
		'label' => 'None',
		'_type' => 'option',
	),
	array(
		'value' => 'tls',
		'disabled' => (!$__templater->func('in_array', array('tls', $__vars['transports'], ), false)),
		'label' => 'TLS',
		'_type' => 'option',
	),
	array(
		'value' => 'ssl',
		'disabled' => (!$__templater->func('in_array', array('ssl', $__vars['transports'], ), false)),
		'label' => 'SSL',
		'_type' => 'option',
	)), array(
		'label' => 'Encryption',
		'explain' => 'The <a href="https://secure.php.net/manual/en/book.openssl.php" target="_blank"><code>openssl</code></a> PHP extension is required to use TLS or SSL encryption.',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('options/email-handler-server', $__vars['option'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);