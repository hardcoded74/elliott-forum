<?php
// FROM HASH: f1551a0291e5f4320b6ef46b9deae0f9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['keyword'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add keyword');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit keyword' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['keyword']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['keyword'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('keywords/delete', $__vars['keyword'], ), false),
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
				' . $__templater->callMacro('helper_criteria', 'user_tabs', array(), $__vars) . '
			</span>
		</h2>

		<ul class="tabPanes block-body">
			<li class="is-active" role="tabpanel" id="general-options">
				' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['keyword']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['keyword'], 'title', ), false),
	), array(
		'label' => 'Title',
	)) . '
				
				<hr class="formRowSep" />

				' . $__templater->formTextBoxRow(array(
		'name' => 'keyword',
		'value' => $__vars['keyword']['keyword'],
		'maxlength' => $__templater->func('max_length', array($__vars['keyword'], 'keyword', ), false),
	), array(
		'label' => 'Word or phrase',
	)) . '
				
				' . $__templater->formCheckBoxRow(array(
	), array(array(
		'checked' => $__vars['keyword']['replace_case_insensitive'],
		'name' => 'keyword_options[case_insensitive]',
		'value' => '1',
		'hint' => 'If checked, the keyword will be matched case insenstive.',
		'label' => '
						' . 'Match case insensitive' . '
					',
		'_type' => 'option',
	),
	array(
		'checked' => $__vars['keyword']['replace_romanized'],
		'name' => 'keyword_options[romanized]',
		'value' => '1',
		'hint' => 'If checked, accents will be ignored when searching for keyword matches. For example both, <strong>café</strong> and <strong>cafe</strong> will match the keyword <strong>cafe</strong>.',
		'label' => '
						' . 'Match accent insensitive' . '
					',
		'_type' => 'option',
	),
	array(
		'checked' => $__vars['keyword']['replace_in_word'],
		'name' => 'keyword_options[in_word]',
		'value' => '1',
		'hint' => 'If checked, the keyword will match, even when it is inside a word. For example both, <strong>key</strong> and <strong>keyword</strong> will match the keyword <strong>key</strong>.',
		'label' => '
						' . 'Match in word' . '
					',
		'_type' => 'option',
	)), array(
		'label' => 'Keyword match options',
	)) . '

				' . $__templater->formRow('
					' . $__templater->formRadio(array(
		'name' => 'replace_type',
		'value' => $__vars['keyword']['replace_type'],
	), array(array(
		'value' => 'url',
		'label' => 'Replace with link to URL' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'replacement',
		'value' => (($__vars['keyword']['replace_type'] === 'url') ? $__vars['keyword']['replacement'] : ''),
	))),
		'_type' => 'option',
	),
	array(
		'value' => 'html',
		'label' => 'Replace with text' . $__vars['xf']['language']['label_separator'],
		'hint' => 'You may use HTML here.',
		'_dependent' => array($__templater->formTextArea(array(
		'name' => 'replacement',
		'rows' => '2',
		'value' => (($__vars['keyword']['replace_type'] === 'html') ? $__vars['keyword']['replacement'] : ''),
	))),
		'_type' => 'option',
	))) . '
				', array(
		'label' => 'Replacement',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formNumberBoxRow(array(
		'name' => 'limit',
		'min' => '0',
		'value' => $__vars['keyword']['limit'],
	), array(
		'label' => 'Limit',
	)) . '

				' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'selected' => $__vars['keyword']['active'],
		'label' => 'Active',
		'_type' => 'option',
	)), array(
		'label' => 'Options',
	)) . '
			</li>

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
		'action' => $__templater->func('link', array('keywords/save', $__vars['keyword'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);