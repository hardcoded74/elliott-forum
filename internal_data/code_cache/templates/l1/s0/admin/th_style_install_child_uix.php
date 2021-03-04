<?php
// FROM HASH: 585664a684f9e0649952dcddb5b636bd
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
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['childStyles'])) {
		foreach ($__vars['childStyles'] AS $__vars['fileName'] => $__vars['childStyle']) {
			$__compilerTemp1[] = array(
				'name' => 'child_styles[]',
				'value' => $__vars['fileName'],
				'label' => $__templater->escape($__vars['childStyle']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = '';
	if (!$__vars['freshInstall']) {
		$__compilerTemp2 .= '
			' . $__templater->formHiddenVal('style_id', $__vars['style']['style_id'], array(
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		' . $__templater->formCheckBoxRow(array(
	), $__compilerTemp1, array(
		'label' => 'Child styles',
	)) . '

		' . $__compilerTemp2 . '

		' . $__templater->formHiddenVal('step', 'install', array(
	)) . '

		' . $__templater->formHiddenVal('path', $__vars['path'], array(
	)) . '
		' . $__templater->formHiddenVal('version_id', $__vars['versionId'], array(
	)) . '

		' . $__templater->formHiddenVal('ftp_host', $__vars['ftpDetails']['ftp_host'], array(
	)) . '
		' . $__templater->formHiddenVal('ftp_port', $__vars['ftpDetails']['ftp_port'], array(
	)) . '
		' . $__templater->formHiddenVal('ftp_user', $__vars['ftpDetails']['ftp_user'], array(
	)) . '
		' . $__templater->formHiddenVal('ftp_pass', $__vars['ftpDetails']['ftp_pass'], array(
	)) . '
		' . $__templater->formHiddenVal('ftp_dir', $__vars['ftpDetails']['ftp_dir'], array(
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