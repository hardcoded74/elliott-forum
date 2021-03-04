<?php
// FROM HASH: 32ea0fe39df6a8efa482e4cab51bfae6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reply to conversation');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Conversations'), $__templater->func('link', array('conversations', ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['conversation']['title'])), $__templater->func('link', array('conversations', $__vars['conversation'], ), false), array(
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
					' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
			'forceHash' => $__vars['conversation']['draft_reply']['attachment_hash'],
		), $__vars) . '
				';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['multiQuote']) {
		$__compilerTemp2 .= '
					' . $__templater->callMacro('multi_quote_macros', 'button', array(
			'href' => $__templater->func('link', array('conversations/multi-quote', $__vars['conversation'], ), false),
			'messageSelector' => '.js-message',
			'storageKey' => 'multiQuoteConversation',
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
		'data-preview-url' => $__templater->func('link', array('conversations/reply-preview', $__vars['conversation'], ), false),
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Message',
	)) . '

			' . $__templater->formRow('
				' . $__compilerTemp1 . '

				' . $__compilerTemp2 . '
			', array(
		'rowtype' => 'fullWidth noLabel',
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
		'action' => $__templater->func('link', array('conversations/add-reply', $__vars['conversation'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'draft' => $__templater->func('link', array('conversations/draft', $__vars['conversation'], ), false),
		'data-xf-init' => 'attachment-manager',
	));
	return $__finalCompiled;
}
);