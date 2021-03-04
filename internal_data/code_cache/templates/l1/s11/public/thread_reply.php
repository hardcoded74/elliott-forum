<?php
// FROM HASH: c41b35848b3cc7e88b3d4a25303a1b83
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reply to thread');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['thread'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
					' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
			'forceHash' => $__vars['thread']['draft_reply']['attachment_hash'],
		), $__vars) . '
				';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['multiQuote']) {
		$__compilerTemp2 .= '
					' . $__templater->callMacro('multi_quote_macros', 'button', array(
			'href' => $__templater->func('link', array('threads/multi-quote', $__vars['thread'], ), false),
			'messageSelector' => '.js-post',
			'storageKey' => 'multiQuoteThread',
		), $__vars) . '
				';
	}
	$__compilerTemp3 = '';
	if ((!$__vars['xf']['visitor']['user_id']) AND (!$__templater->method($__vars['thread'], 'canReplyPreReg', array()))) {
		$__compilerTemp3 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => '_xfUsername',
			'data-xf-init' => 'guest-username',
			'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'username', ), false),
		), array(
			'label' => 'Name',
		)) . '
			';
	} else if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp3 .= '
				' . $__templater->callMacro('helper_thread_options', 'watch_input', array(
			'thread' => $__vars['thread'],
		), $__vars) . '
				' . $__templater->callMacro('helper_thread_options', 'thread_status', array(
			'thread' => $__vars['thread'],
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['defaultMessage'],
		'attachments' => ($__vars['attachmentData'] ? $__vars['attachmentData']['attachments'] : array()),
		'placeholder' => 'Write your reply...',
		'data-preview-url' => $__templater->func('link', array('threads/reply-preview', $__vars['thread'], ), false),
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Message',
	)) . '

			' . $__templater->formRow('
				' . $__compilerTemp1 . '

				' . $__compilerTemp2 . '
			', array(
	)) . '

			' . $__compilerTemp3 . '

			' . $__templater->formRowIfContent($__templater->func('captcha', array(false, false)), array(
		'label' => 'Verification',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Post reply',
		'icon' => 'reply',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('threads/add-reply', $__vars['thread'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'draft' => $__templater->func('link', array('threads/draft', $__vars['thread'], ), false),
		'data-xf-init' => 'attachment-manager',
	));
	return $__finalCompiled;
}
);