<?php
// FROM HASH: a9783bae254da7bec9e4b00939b4cf2b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
	' . 'Upload style property image' . '
');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formUploadRow(array(
		'name' => 'file',
		'accept' => '.gif,.jpeg,.jpg,.jpe,.png',
	), array(
		'explain' => 'Allowed file extensions are: .jpg, .jpeg, .jpe, .png, .gif',
		'label' => 'Image',
	)) . '
			
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'value' => '1',
		'name' => 'custom_disk_path',
		'explain' => 'th_uix_customize_upload_path_explain',
		'label' => 'Customize upload path' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'path',
		'value' => $__vars['defaultPath'],
	))),
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'name' => 'rename_file',
		'explain' => 'th_uix_customize_file_name_explain',
		'label' => 'Customize file name' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'name',
		'value' => $__vars['defaultName'],
	))),
		'_type' => 'option',
	)), array(
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'upload',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('uix-sp-image-upload/upload', $__vars['styleProperty'], ), false),
		'class' => 'block',
		'upload' => 'true',
		'ajax' => 'true',
		'data-xf-init' => 'uix-image-upload',
	));
	return $__finalCompiled;
}
);