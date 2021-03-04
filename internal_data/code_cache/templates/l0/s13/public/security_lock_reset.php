<?php
// FROM HASH: ccf7e152784356be764301b6fbe2b9f6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reset password');
	$__finalCompiled .= '

<div class="blockMessage blockMessage--important">
	' . 'In order to keep your account secure, we require you to change your password before you can continue using the site.' . '
</div>

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow($__templater->escape($__vars['user']['username']), array(
		'label' => 'Username',
	)) . '

			' . $__templater->formPasswordBoxRow(array(
		'name' => 'password',
		'autocomplete' => 'new-password',
		'checkstrength' => 'true',
	), array(
		'label' => 'New password',
	)) . '

			' . $__templater->formPasswordBoxRow(array(
		'name' => 'password_confirm',
		'autocomplete' => 'new-password',
	), array(
		'label' => 'Confirm new password',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('security-lock/reset', $__vars['user'], array('c' => $__vars['c'], ), ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);