<?php
// FROM HASH: 08e702e49fa53ff5a0810aa5d717146b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['sponsor'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add sponsor');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit sponsor' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['sponsor']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['sponsor'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('sponsors/delete', $__vars['sponsor'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['sponsor']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['sponsor'], 'title', ), false),
	), array(
		'label' => 'Title',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'url',
		'value' => $__vars['sponsor']['url'],
		'maxlength' => $__templater->func('max_length', array($__vars['sponsor'], 'url', ), false),
	), array(
		'label' => 'URL',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'image',
		'value' => $__vars['sponsor']['image'],
		'maxlength' => $__templater->func('max_length', array($__vars['sponsor'], 'image', ), false),
	), array(
		'label' => 'Image',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'width',
		'min' => '0',
		'value' => $__vars['sponsor']['width'],
	), array(
		'label' => 'Width',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'height',
		'min' => '0',
		'value' => $__vars['sponsor']['height'],
	), array(
		'label' => 'Height',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'selected' => $__vars['sponsor']['active'],
		'label' => 'Active',
		'_type' => 'option',
	),
	array(
		'name' => 'directory',
		'selected' => $__vars['sponsor']['directory'],
		'label' => 'Show in directory',
		'_type' => 'option',
	)), array(
		'label' => 'Options',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('sponsors/save', $__vars['sponsor'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);