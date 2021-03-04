<?php
// FROM HASH: 1e22da79469cd3d09fd0929ecff66a7d
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
	$__finalCompiled .= '
';
	$__templater->includeCss('th_uix_uploadable_style_property.less');
	$__finalCompiled .= '

<div class="inputGroup inputGroup--joined inputGroup--uixFileUpload">
	' . $__templater->formTextBox(array(
		'name' => $__vars['formBaseKey'] . '[background-image]',
		'value' => $__templater->method($__vars['property'], 'getCssPropertyValue', array('background-image', )),
		'data-style-property-id' => $__vars['property']['property_id'],
		'class' => 'input--cssProp input--colorWidthMatched',
		'dir' => 'ltr',
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
</div>';
	return $__finalCompiled;
}
);