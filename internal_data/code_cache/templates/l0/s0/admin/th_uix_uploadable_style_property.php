<?php
// FROM HASH: 23ddc3f1c62f28e188b1c0b784d17ef8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'themehouse/uix/style-property-upload.js',
		'addon' => 'ThemeHouse/UIX',
	));
	$__templater->includeCss('th_uix_uploadable_style_property.less');
	$__finalCompiled .= $__templater->formRow('
	' . '' . '
	' . '' . '
	
	<div class="inputGroup inputGroup--joined inputGroup--uixFileUpload">
		' . $__templater->formTextBox(array(
		'name' => $__vars['formBaseKey'],
		'type' => $__vars['valueOptions']['type'],
		'dir' => 'auto',
		'data-style-property-id' => $__vars['property']['property_id'],
		'value' => $__vars['property']['property_value'],
	)) . '

		' . $__templater->button('
			' . 'Upload' . '
		', array(
		'class' => 'button--link',
		'data-xf-click' => 'overlay',
		'href' => $__templater->func('link', array('uix-sp-image-upload', $__vars['property'], ), false),
		'value' => $__vars['property']['property_name'],
	), '', array(
	)) . '
	</div>
', array(
		'class' => $__vars['valueOptions']['class'],
		'code' => $__vars['valueOptions']['code'],
		'rowclass' => $__vars['rowClass'],
		'label' => $__templater->escape($__vars['titleHtml']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['property']['description']),
	));
	return $__finalCompiled;
}
);