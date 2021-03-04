<?php
// FROM HASH: 235398d1cf8cb97d5ef226b781c109e8
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
			' . $__templater->formHiddenVal('style_id', $__vars['style']['style_id'], array(
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please enter your FTP details so your style can be installed.' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>

		' . $__templater->formTextBoxRow(array(
		'name' => 'ftp_host',
		'value' => 'localhost',
	), array(
		'label' => 'FTP host',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'ftp_port',
		'value' => '21',
	), array(
		'label' => 'FTP port',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'ftp_user',
	), array(
		'label' => 'FTP username',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'ftp_pass',
		'type' => 'password',
	), array(
		'label' => 'FTP password',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'ftp_dir',
		'value' => $__vars['fileDir'],
	), array(
		'label' => 'FTP directory',
	)) . '

		' . $__compilerTemp1 . '

		' . $__templater->formHiddenVal('version_id', $__vars['versionId'], array(
	)) . '

		' . $__templater->formHiddenVal('step', 'install', array(
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