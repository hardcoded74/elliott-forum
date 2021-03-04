<?php
// FROM HASH: 0d09fb59ac6156a17043fc7115be64c8
return array(
'macros' => array('header' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'article' => '!',
		'thread' => '!',
		'titleHtml' => null,
		'showMeta' => true,
		'metaHtml' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['thread'], 'canReply', array())) {
		$__compilerTemp1 .= '
			' . $__templater->button('
				' . 'thxpress_go_to_article' . '
			', array(
			'href' => $__vars['article']['link'],
			'class' => 'button--cta',
			'icon' => 'article',
		), '', array(
		)) . '
		';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['thread'], 'canReply', array())) {
		$__compilerTemp2 .= '
            ' . $__templater->button('
                ' . 'Reply' . '
            ', array(
			'href' => $__templater->func('link', array('threads/reply', $__vars['thread'], ), false),
			'class' => 'button--cta uix_quickReply--button',
			'icon' => 'write',
		), '', array(
		)) . '
        ';
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
		' . $__compilerTemp1 . '
        ' . $__compilerTemp2 . '
	');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<!--dont believe we need this anymore (IH) -->
';
	return $__finalCompiled;
}
);