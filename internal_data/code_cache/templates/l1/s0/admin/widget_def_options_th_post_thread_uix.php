<?php
// FROM HASH: 057a8c1fcf45fd7079be8d417353b822
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formTextAreaRow(array(
		'name' => 'options[description]',
		'value' => $__vars['options']['description'],
		'rows' => '5',
	), array(
		'label' => 'Description',
	));
	return $__finalCompiled;
}
);