<?php
// FROM HASH: 2112708779901d1551c28a795b5929af
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['affiliateLink'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add affiliate link');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit affiliate link' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['affiliateLink']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['affiliateLink'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('affiliate-links/delete', $__vars['affiliateLink'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['affiliateLink']['reference_link_parser'])) {
		foreach ($__vars['affiliateLink']['reference_link_parser'] AS $__vars['linkParser']) {
			$__compilerTemp1 .= '
							';
			if ($__vars['linkParser']['type'] === 'replace') {
				$__compilerTemp1 .= '
								<li class="inputPair">
									' . $__templater->formTextBox(array(
					'name' => 'reference_link_parser_param_one[]',
					'value' => $__vars['linkParser']['param_one'],
					'placeholder' => 'Find',
					'size' => '20',
				)) . '
									' . $__templater->formTextBox(array(
					'name' => 'reference_link_parser_param_two[]',
					'value' => $__vars['linkParser']['param_two'],
					'placeholder' => 'Replace',
					'size' => '20',
				)) . '
									' . $__templater->formHiddenVal('reference_link_parser_type[]', 'replace', array(
				)) . '
								</li>
							';
			}
			$__compilerTemp1 .= '
						';
		}
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['affiliateLink']['reference_link_parser'])) {
		foreach ($__vars['affiliateLink']['reference_link_parser'] AS $__vars['linkParser']) {
			$__compilerTemp2 .= '
							';
			if ($__vars['linkParser']['type'] === 'parse') {
				$__compilerTemp2 .= '
								<li class="inputPair">
									' . $__templater->formSelect(array(
					'name' => 'reference_link_parser_param_one[]',
					'value' => $__vars['linkParser']['param_one'],
				), array(array(
					'value' => '',
					'label' => $__vars['xf']['language']['parenthesis_open'] . 'Component' . $__vars['xf']['language']['parenthesis_close'],
					'_type' => 'option',
				),
				array(
					'value' => 'scheme',
					'label' => 'Scheme',
					'_type' => 'option',
				),
				array(
					'value' => 'host',
					'label' => 'Host',
					'_type' => 'option',
				),
				array(
					'value' => 'port',
					'label' => 'Port',
					'_type' => 'option',
				),
				array(
					'value' => 'user',
					'label' => 'Username',
					'_type' => 'option',
				),
				array(
					'value' => 'pass',
					'label' => 'Password',
					'_type' => 'option',
				),
				array(
					'value' => 'path',
					'label' => 'Path',
					'_type' => 'option',
				),
				array(
					'value' => 'query',
					'label' => 'Query',
					'_type' => 'option',
				),
				array(
					'value' => 'fragment',
					'label' => 'Fragment',
					'_type' => 'option',
				))) . '
									' . $__templater->formTextBox(array(
					'name' => 'reference_link_parser_param_two[]',
					'value' => $__vars['linkParser']['param_two'],
					'placeholder' => 'Replace',
					'size' => '20',
				)) . '
									' . $__templater->formHiddenVal('reference_link_parser_type[]', 'parse', array(
				)) . '
								</li>
							';
			}
			$__compilerTemp2 .= '
						';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h2 class="block-tabHeader tabs hScroller" data-xf-init="tabs h-scroller" role="tablist">
			<span class="hScroller-scroll">
				<a class="tabs-tab is-active" role="tab" tabindex="0" aria-controls="general-options">' . 'General options' . '</a>
				<a class="tabs-tab" role="tab" tabindex="1" aria-controls="link-criteria">' . 'Link criteria' . '</a>
				' . $__templater->callMacro('helper_criteria', 'user_tabs', array(), $__vars) . '
			</span>
		</h2>

		<ul class="tabPanes block-body">
			<li class="is-active" role="tabpanel" id="general-options">
				' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['affiliateLink']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['affiliateLink'], 'title', ), false),
	), array(
		'label' => 'Title',
	)) . '
				
				' . $__templater->formTextBoxRow(array(
		'name' => 'reference_link_prefix',
		'value' => $__vars['affiliateLink']['reference_link_prefix'],
		'maxlength' => $__templater->func('max_length', array($__vars['affiliateLink'], 'reference_link_prefix', ), false),
	), array(
		'label' => 'Reference link prefix',
		'explain' => 'Inserts affiliate data at the beginning of original link, if the conditions set are met.',
	)) . '
				
				' . $__templater->formTextBoxRow(array(
		'name' => 'reference_link_suffix',
		'value' => $__vars['affiliateLink']['reference_link_suffix'],
		'maxlength' => $__templater->func('max_length', array($__vars['affiliateLink'], 'reference_link_suffix', ), false),
	), array(
		'label' => 'Reference link suffix',
		'explain' => 'Inserts affiliate data at the end of original link, if the conditions set below are met.',
	)) . '

				' . $__templater->formRow('
					
					<ul class="listPlain inputPair-container">
						' . $__compilerTemp1 . '
						<li class="inputPair" data-xf-init="field-adder">
							' . $__templater->formTextBox(array(
		'name' => 'reference_link_parser_param_one[]',
		'placeholder' => 'Find',
		'size' => '20',
		'data-i' => '0',
	)) . '
							' . $__templater->formTextBox(array(
		'name' => 'reference_link_parser_param_two[]',
		'placeholder' => 'Replace',
		'size' => '20',
		'data-i' => '0',
	)) . '
							' . $__templater->formHiddenVal('reference_link_parser_type[]', 'replace', array(
	)) . '
						</li>
					</ul>
				', array(
		'rowtype' => 'input',
		'label' => 'Reference link find and replace',
		'explain' => 'Replace any part of the original link.',
	)) . '
				
				' . $__templater->formRow('
					
					<ul class="listPlain inputPair-container">
						' . $__compilerTemp2 . '
						<li class="inputPair" data-xf-init="field-adder">
							' . $__templater->formSelect(array(
		'name' => 'reference_link_parser_param_one[]',
		'data-i' => '0',
	), array(array(
		'value' => '',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Component' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	),
	array(
		'value' => 'scheme',
		'label' => 'Scheme',
		'_type' => 'option',
	),
	array(
		'value' => 'host',
		'label' => 'Host',
		'_type' => 'option',
	),
	array(
		'value' => 'port',
		'label' => 'Port',
		'_type' => 'option',
	),
	array(
		'value' => 'user',
		'label' => 'Username',
		'_type' => 'option',
	),
	array(
		'value' => 'pass',
		'label' => 'Password',
		'_type' => 'option',
	),
	array(
		'value' => 'path',
		'label' => 'Path',
		'_type' => 'option',
	),
	array(
		'value' => 'query',
		'label' => 'Query',
		'_type' => 'option',
	),
	array(
		'value' => 'fragment',
		'label' => 'Fragment',
		'_type' => 'option',
	))) . '
							' . $__templater->formTextBox(array(
		'name' => 'reference_link_parser_param_two[]',
		'placeholder' => 'Replace',
		'size' => '20',
		'data-i' => '0',
	)) . '
							' . $__templater->formHiddenVal('reference_link_parser_type[]', 'parse', array(
	)) . '
						</li>
					</ul>
				', array(
		'rowtype' => 'input',
		'label' => 'Reference link component replace',
		'explain' => 'Replace specific parts of the original link.',
	)) . '
				
				<hr class="formRowSep" />

				' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'selected' => $__vars['affiliateLink']['active'],
		'label' => 'Active',
		'_type' => 'option',
	),
	array(
		'name' => 'url_cloaking',
		'selected' => $__vars['affiliateLink']['url_cloaking'],
		'label' => 'URL cloaking',
		'hint' => 'Hide the reference links and process them through a gateway. Please note that cloaking can severely penalize your Google rankings.',
		'_type' => 'option',
	),
	array(
		'name' => 'url_encoding',
		'selected' => $__vars['affiliateLink']['url_encoding'],
		'label' => 'URL encoding',
		'hint' => 'Encode the reference links in order to meet any affiliate link provider requirements.',
		'_type' => 'option',
	)), array(
		'label' => 'Options',
	)) . '
			</li>
			
			<li role="tabpanel" id="link-criteria">
				' . $__templater->formTextBoxRow(array(
		'name' => 'link_criteria[domain_match]',
		'value' => $__vars['affiliateLink']['link_criteria']['domain_match'],
	), array(
		'label' => 'Domain matches',
		'explain' => 'Any full part of the domain which must appear.',
	)) . '
				
				' . $__templater->formTextBoxRow(array(
		'name' => 'link_criteria[domain_no_match]',
		'value' => $__vars['affiliateLink']['link_criteria']['domain_no_match'],
	), array(
		'label' => 'Domain does not match',
		'explain' => 'Any full part of the domain which must not appear.',
	)) . '

				' . $__templater->formTextBoxRow(array(
		'name' => 'link_criteria[domain_extension_match]',
		'value' => $__vars['affiliateLink']['link_criteria']['domain_extension_match'],
	), array(
		'label' => 'Domain extension matches',
		'explain' => 'The very last full part of the domain which must appear',
	)) . '

				' . $__templater->formTextBoxRow(array(
		'name' => 'link_criteria[domain_extension_no_match]',
		'value' => $__vars['affiliateLink']['link_criteria']['domain_extension_no_match'],
	), array(
		'label' => 'Domain extension does not match',
		'explain' => 'The very last full part of the domain which must not appear',
	)) . '

				' . $__templater->formTextBoxRow(array(
		'name' => 'link_criteria[ref_link_match]',
		'value' => $__vars['affiliateLink']['link_criteria']['ref_link_match'],
	), array(
		'label' => 'Reference link matches',
		'explain' => 'Any part of the reference link, including the domain, which must appear',
	)) . '

				' . $__templater->formTextBoxRow(array(
		'name' => 'link_criteria[ref_link_no_match]',
		'value' => $__vars['affiliateLink']['link_criteria']['ref_link_no_match'],
	), array(
		'label' => 'Reference link does not match',
		'explain' => 'Any part of the reference link, including the domain, which must not appear',
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
		'action' => $__templater->func('link', array('affiliate-links/save', $__vars['affiliateLink'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);