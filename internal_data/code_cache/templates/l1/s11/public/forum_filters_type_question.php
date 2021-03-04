<?php
// FROM HASH: 604d4d178ad0d9d2f4f2877034d5b41d
return array(
'extends' => function($__templater, array $__vars) { return 'forum_filters'; },
'extensions' => array('before_date_limit' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	<div class="menu-row menu-row--separated">
		';
	$__compilerTemp1 = array(array(
		'name' => 'unanswered',
		'selected' => $__vars['filters']['unanswered'],
		'label' => 'Only show questions that have received no answers',
		'_type' => 'option',
	));
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1[] = array(
			'name' => 'your_answers',
			'selected' => $__vars['filters']['your_answers'],
			'label' => 'Only show questions you\'ve answered',
			'_type' => 'option',
		);
	}
	$__finalCompiled .= $__templater->formCheckBox(array(
	), $__compilerTemp1) . '
	</div>
';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->renderExtension('before_date_limit', $__vars, $__extensions);
	return $__finalCompiled;
}
);