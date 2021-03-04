<?php
// FROM HASH: b87b5792c3004e59a23c6fb46b181c33
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formSelectRow(array(
		'name' => 'options[platform]',
		'value' => $__vars['options']['platform'],
	), array(array(
		'value' => '',
		'_type' => 'option',
	),
	array(
		'value' => 'facebook',
		'label' => 'Facebook',
		'_type' => 'option',
	),
	array(
		'value' => 'twitter',
		'label' => 'Twitter',
		'_type' => 'option',
	)), array(
		'label' => 'Platform',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'options[name]',
		'value' => $__vars['options']['name'],
	), array(
		'label' => 'Name',
	));
	return $__finalCompiled;
}
);