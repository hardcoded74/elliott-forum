<?php
// FROM HASH: a12ed07c191894f304be82ee9d455fe3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (($__vars['context'] == 'create') AND ($__vars['subContext'] == 'quick')) {
		$__finalCompiled .= '
	';
		$__vars['rowType'] = 'fullWidth noGutter mergeNext';
		$__finalCompiled .= '
';
	} else if (($__vars['context'] == 'edit') AND ($__vars['subContext'] == 'first_post_quick')) {
		$__finalCompiled .= '
	';
		$__vars['rowType'] = 'fullWidth mergeNext';
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['rowType'] = '';
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__vars['show'] = (($__vars['thread']['Forum']['forum_type_id'] == 'article') AND (($__vars['context'] != 'edit') OR ($__vars['subContext'] != 'thread')));
	$__finalCompiled .= '
';
	if ($__vars['show']) {
		$__finalCompiled .= '
	' . $__templater->formRow('', array(
			'label' => 'About article images',
			'explain' => 'Note: the first image in your article, whether linked directly or attached, will be used as the cover image.',
			'rowtype' => $__vars['rowType'],
		)) . '
';
	}
	return $__finalCompiled;
}
);