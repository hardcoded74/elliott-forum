<?php
// FROM HASH: d6731294e6e4c7790ce3429988a35265
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['alert'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add alert');
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit alert' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['alert']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['alert'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('monetize-alerts/delete', $__vars['alert'], ), false),
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
		'sendRules' => $__vars['alert']['send_rules'],
	), $__vars) . '
				
				<hr class="formRowSep" />

				' . $__templater->formRow('
					<div class="inputGroup inputGroup--numbers">
						' . $__templater->formNumberBox(array(
		'name' => 'limit_alerts',
		'value' => $__vars['alert']['limit_alerts'],
		'placeholder' => 'Alerts',
		'min' => '0',
	)) . '
						<span class="inputGroup-text">' . 'per' . '</span>
						' . $__templater->formNumberBox(array(
		'name' => 'limit_days',
		'value' => $__vars['alert']['limit_days'],
		'placeholder' => 'Days',
		'min' => '0',
		'max' => ($__vars['xf']['options']['thmonetize_alertLogLength'] ?: ''),
	)) . '
						<span class="inputGroup-text">' . 'Days' . '</span>
					</div>
				', array(
		'rowtype' => 'input',
		'label' => 'Per user limit',
		'explain' => 'This controls the maximum number of this alert that can be sent to a user during a specified period. The length of the period should not exceed the log retention period or the global/total alerts per user limit. Set to 0 to allow unlimited alerts.',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['alert']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['alert'], 'title', ), false),
		'required' => 'true',
	), array(
		'label' => 'Title',
		'explain' => 'The alert title is not visible to the user.',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'value' => ($__vars['alert']['User'] ? $__vars['alert']['User']['username'] : $__vars['xf']['visitor']['username']),
		'ac' => 'single',
	), array(
		'label' => 'From user',
		'explain' => 'If you would like this alert to appear from a specific user, enter their name above. If no name is specified, the alert will be sent anonymously.',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formTextBoxRow(array(
		'name' => 'link_url',
		'type' => 'url',
		'value' => $__vars['alert']['link_url'],
		'dir' => 'ltr',
	), array(
		'label' => 'Link URL',
	)) . '

				' . $__templater->formTextBoxRow(array(
		'name' => 'link_title',
		'value' => $__vars['alert']['link_title'],
	), array(
		'label' => 'Link title',
		'explain' => 'If you provide a URL, you can use it in your alert as the main pop up link. You can either insert it yourself in the alert body with <b>{link}</b> or it will be appended at the end automatically.',
	)) . '

				' . $__templater->formCodeEditorRow(array(
		'name' => 'body',
		'value' => $__vars['alert']['body'],
		'mode' => 'html',
		'data-line-wrapping' => 'true',
		'class' => 'codeEditor--autoSize codeEditor--proportional',
	), array(
		'label' => 'Alert body',
		'hint' => 'You may use HTML',
		'explain' => 'The following placeholders will be replaced in the message: {name}, {id}, {link}.' . ' ' . 'You may also use {phrase:phrase_title} which will be replaced with the phrase text in the recipient\'s language.',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'selected' => $__vars['alert']['active'],
		'label' => 'Active',
		'_type' => 'option',
	)), array(
		'label' => 'Options',
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
		'action' => $__templater->func('link', array('monetize-alerts/save', $__vars['alert'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);