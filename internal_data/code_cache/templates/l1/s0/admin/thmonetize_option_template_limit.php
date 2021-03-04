<?php
// FROM HASH: 9d64b5449dcc2551f7882bb4aaea797b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('

	<div class="inputGroup inputGroup--numbers">
		' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[' . $__vars['formatParams']['entity'] . ']',
		'value' => $__vars['option']['option_value'][$__vars['formatParams']['entity']],
		'placeholder' => $__vars['formatParams']['placeholder'],
		'min' => '0',
	)) . '
		<span class="inputGroup-text">' . 'per' . '</span>
		' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[days]',
		'value' => $__vars['option']['option_value']['days'],
		'placeholder' => 'Days',
		'min' => '0',
		'max' => ($__vars['formatParams']['max'] ?: ''),
	)) . '
		<span class="inputGroup-text">' . 'Days' . '</span>
	</div>
', array(
		'rowtype' => 'input',
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);