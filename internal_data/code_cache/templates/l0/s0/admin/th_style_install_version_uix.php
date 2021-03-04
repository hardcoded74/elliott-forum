<?php
// FROM HASH: c80177186ba262a2d9ee8cf5a0c79d86
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Install ' . $__templater->escape($__vars['product']['product_name']) . '');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('ThemeHouse Styles'), $__templater->func('link', array('styles/themehouse', ), false), array(
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__vars['freshInstall']) {
		$__compilerTemp1 .= '
		<div class="blockMessage blockMessage--important">
			<b>Note:</b> If you\'re upgrading from 2.0.2.1.0 or below there are some significant changes in this update, please do <a href="https://www.themehouse.com/help/documentation/uix2/faqs#uix-204-update-potential-issues" target="_BLANK">review these changes before proceeding </a>
		</div>
	';
	}
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['versions'])) {
		foreach ($__vars['versions'] AS $__vars['version']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['version']['id'],
				'label' => $__templater->escape($__vars['version']['version']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = '';
	if (!$__vars['freshInstall']) {
		$__compilerTemp3 .= '
			' . $__templater->formHiddenVal('style_id', $__vars['style']['style_id'], array(
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	' . $__compilerTemp1 . '

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'By continuing we will attempt to automatically install the selected version of ' . $__templater->escape($__vars['product']['product_name']) . '. To ensure we provide the best installation possible your XenForo version, XenForo installation URL and ThemeHouse API key will be passed to our servers when the installation happens.' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>

		' . $__templater->formSelectRow(array(
		'name' => 'version_id',
	), $__compilerTemp2, array(
		'label' => 'Version',
	)) . '

		' . $__compilerTemp3 . '

		' . $__templater->formHiddenVal('step', 'ftp_details', array(
	)) . '

		' . $__templater->formSubmitRow(array(
		'icon' => 'submit',
	), array(
	)) . '
	</div>
', array(
		'action' => $__vars['submitUrl'],
		'class' => 'block',
	));
	return $__finalCompiled;
}
);