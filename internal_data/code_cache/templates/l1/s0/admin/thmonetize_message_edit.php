<?php
// FROM HASH: 41c93ecd11986d1cedcc8aa0d6a5e2d4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['message'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add message');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit message' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['message']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['message'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('monetize-messages/delete', $__vars['message'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<h2 class="block-tabHeader tabs hScroller" data-xf-init="tabs h-scroller" role="tablist">
			<span class="hScroller-scroll">
				<a class="tabs-tab is-active" role="tab" tabindex="0" aria-controls="general-options">' . 'General options' . '</a>
				' . $__templater->callMacro('thmonetize_helper_criteria', 'user_upgrade_tabs', array(), $__vars) . '
				' . $__templater->callMacro('helper_criteria', 'user_tabs', array(), $__vars) . '
			</span>
		</h2>

		<ul class="tabPanes block-body">
			<li class="is-active" role="tabpanel" id="general-options">
				
				' . $__templater->callMacro('thmonetize_communication_edit_macros', 'time', array(
		'sendRules' => $__vars['message']['send_rules'],
	), $__vars) . '

				<hr class="formRowSep" />

				' . $__templater->formRow('
					<div class="inputGroup inputGroup--numbers">
						' . $__templater->formNumberBox(array(
		'name' => 'limit_messages',
		'value' => $__vars['message']['limit_messages'],
		'placeholder' => 'Messages',
		'min' => '0',
	)) . '
						<span class="inputGroup-text">' . 'per' . '</span>
						' . $__templater->formNumberBox(array(
		'name' => 'limit_days',
		'value' => $__vars['message']['limit_days'],
		'placeholder' => 'Days',
		'min' => '0',
		'max' => ($__vars['xf']['options']['thmonetize_messageLogLength'] ?: ''),
	)) . '
						<span class="inputGroup-text">' . 'Days' . '</span>
					</div>
				', array(
		'rowtype' => 'input',
		'label' => 'Per user limit',
		'explain' => 'This controls the maximum number of this message that can be sent to a user during a specified period. The length of the period should not exceed the log retention period or the global/total messages per user limit. Set to 0 to allow unlimited messages.',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'value' => ($__vars['message']['User'] ? $__vars['message']['User']['username'] : $__vars['xf']['visitor']['username']),
		'ac' => 'single',
	), array(
		'label' => 'From user',
		'explain' => '
						<p>' . 'Enter the name of an existing user the conversation should be started by.' . '</p>
						<p><b>' . 'Note' . $__vars['xf']['language']['label_separator'] . '</b> ' . 'You cannot start a conversation with yourself.' . '</p>
					',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['message']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['message'], 'title', ), false),
		'required' => 'true',
	), array(
		'label' => 'Conversation title',
	)) . '

				' . $__templater->formTextAreaRow(array(
		'name' => 'body',
		'rows' => '5',
		'autosize' => 'true',
		'value' => $__vars['message']['body'],
		'required' => 'true',
	), array(
		'label' => 'Conversation message',
		'hint' => 'You may use BB code',
		'explain' => 'The following placeholders will be replaced in the message: {name}, {email}, {id}.' . ' ' . 'You may also use {phrase:phrase_title} which will be replaced with the phrase text in the recipient\'s language.',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'selected' => $__vars['message']['active'],
		'label' => 'Active',
		'_type' => 'option',
	),
	array(
		'name' => 'open_invite',
		'selected' => $__vars['message']['open_invite'],
		'label' => 'Allow anyone in the conversation to invite others',
		'_type' => 'option',
	),
	array(
		'name' => 'conversation_locked',
		'selected' => $__vars['message']['conversation_locked'],
		'label' => 'Lock conversation (no responses will be allowed)',
		'_type' => 'option',
	)), array(
		'label' => 'Options',
	)) . '

				' . $__templater->formRadioRow(array(
		'name' => 'delete_type',
		'value' => $__vars['message']['delete_type'],
	), array(array(
		'value' => '',
		'label' => 'Do not leave conversation',
		'explain' => 'The conversation will remain in your inbox and you will be notified of responses.',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted',
		'label' => 'Leave conversation and accept future messages',
		'explain' => 'Should this conversation receive further responses in the future, this conversation will be restored to your inbox.',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted_ignored',
		'label' => 'Leave conversation and ignore future messages',
		'explain' => 'You will not be notified of any future responses and the conversation will remain deleted.',
		'_type' => 'option',
	)), array(
		'label' => 'Future message handling',
	)) . '
			</li>

			' . $__templater->callMacro('thmonetize_helper_criteria', 'user_upgrade_panes', array(
		'criteria' => $__templater->method($__vars['userUpgradeCriteria'], 'getCriteriaForTemplate', array()),
		'data' => $__templater->method($__vars['userUpgradeCriteria'], 'getExtraTemplateData', array()),
	), $__vars) . '

			' . $__templater->callMacro('helper_criteria', 'user_panes', array(
		'criteria' => $__templater->method($__vars['userCriteria'], 'getCriteriaForTemplate', array()),
		'data' => $__templater->method($__vars['userCriteria'], 'getExtraTemplateData', array()),
	), $__vars) . '

		</ul>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('monetize-messages/save', $__vars['message'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);