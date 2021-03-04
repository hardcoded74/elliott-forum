<?php
// FROM HASH: 1d113749419e678fc63bbeb760680852
return array(
'macros' => array('grid_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'nodeStyling' => '!',
		'style' => null,
		'autoEnable' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['autoEnable']) {
		$__finalCompiled .= '
		' . $__templater->formHiddenVal('grid_options[max_columns][enable]', '1', array(
		)) . '
		' . $__templater->formHiddenVal('grid_options[min_column_width][enable]', '1', array(
		)) . '
		' . $__templater->formHiddenVal('grid_options[fill_last_row][enable]', '1', array(
		)) . '

		' . $__templater->formTextBoxRow(array(
			'name' => 'grid_options[max_columns][value]',
			'value' => $__vars['nodeStyling']['grid_options']['max_columns']['value'],
		), array(
			'label' => 'Maximum columns',
			'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'grid_options', 'max_columns', ), true),
		)) . '

		' . $__templater->formTextBoxRow(array(
			'name' => 'grid_options[min_column_width][value]',
			'value' => $__vars['nodeStyling']['grid_options']['min_column_width']['value'],
		), array(
			'label' => 'Minimum column widths',
			'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'grid_options', 'min_column_width', ), true),
		)) . '

		' . $__templater->formSelectRow(array(
			'name' => 'grid_options[fill_last_row][value]',
			'value' => $__vars['nodeStyling']['grid_options']['fill_last_row']['value'],
		), array(array(
			'value' => '0',
			'label' => 'Divide last row into equal widths',
			'_type' => 'option',
		),
		array(
			'value' => '1',
			'label' => 'Don\'t Fill (Will leave empty spaces)',
			'_type' => 'option',
		),
		array(
			'value' => '2',
			'label' => 'Divide last row according to lower number of columns',
			'_type' => 'option',
		),
		array(
			'value' => '3',
			'label' => 'Make remaining nodes full width own their own rows',
			'_type' => 'option',
		)), array(
			'label' => 'Fill last row',
			'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'grid_options', 'fill_last_row', ), true),
		)) . '

		';
	} else {
		$__finalCompiled .= '

		' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'grid_options[max_columns][enable]',
			'selected' => $__vars['nodeStyling']['grid_options']['max_columns']['enable'],
			'label' => 'Use custom value',
			'_dependent' => array($__templater->formTextBox(array(
			'name' => 'grid_options[max_columns][value]',
			'value' => $__vars['nodeStyling']['grid_options']['max_columns']['value'],
		))),
			'_type' => 'option',
		)), array(
			'label' => 'Maximum columns',
			'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'grid_options', 'max_columns', ), true),
		)) . '

		' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'grid_options[min_column_width][enable]',
			'selected' => $__vars['nodeStyling']['grid_options']['min_column_width']['enable'],
			'label' => 'Use custom value',
			'_dependent' => array($__templater->formTextBox(array(
			'name' => 'grid_options[min_column_width][value]',
			'value' => $__vars['nodeStyling']['grid_options']['min_column_width']['value'],
		))),
			'_type' => 'option',
		)), array(
			'label' => 'Minimum column widths',
			'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'grid_options', 'min_column_width', ), true),
		)) . '

		' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'grid_options[fill_last_row][enable]',
			'selected' => $__vars['nodeStyling']['grid_options']['fill_last_row']['enable'],
			'label' => 'Use custom value',
			'_dependent' => array($__templater->formSelect(array(
			'name' => 'grid_options[fill_last_row][value]',
			'value' => $__vars['nodeStyling']['grid_options']['fill_last_row']['value'],
		), array(array(
			'value' => '0',
			'label' => 'Divide last row into equal widths',
			'_type' => 'option',
		),
		array(
			'value' => '1',
			'label' => 'Don\'t Fill (Will leave empty spaces)',
			'_type' => 'option',
		),
		array(
			'value' => '2',
			'label' => 'Divide last row according to lower number of columns',
			'_type' => 'option',
		),
		array(
			'value' => '3',
			'label' => 'Make remaining nodes full width own their own rows',
			'_type' => 'option',
		)))),
			'_type' => 'option',
		)), array(
			'label' => 'Fill last row',
			'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'grid_options', 'fill_last_row', ), true),
		)) . '
	';
	}
	$__finalCompiled .= '

	' . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit node layout and styling' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['node']['title']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ((!$__vars['node']['depth']) OR ($__vars['node']['node_type_id'] === 'LayoutSeparator')) {
		$__compilerTemp1 .= '
			<h3 class="block-header"><span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				' . 'Node grid' . '
			</span></h3>
			<div class="block-body block-body--collapsible">
				' . $__templater->callMacro(null, 'grid_options', array(
			'nodeStyling' => $__vars['nodeStyling'],
			'style' => $__vars['style'],
		), $__vars) . '
			</div>
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h3 class="block-header"><span class="collapseTrigger collapseTrigger--block is-active" data-xf-click="toggle" data-target="< :up:next">
			' . 'Node styling' . '
		</span></h3>
		<div class="block-body block-body--collapsible is-active">
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[class_name][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['class_name']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[class_name][value]',
		'value' => $__vars['nodeStyling']['styling_options']['class_name']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Node class',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'class_name', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[background_image_url][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['background_image_url']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[background_image_url][value]',
		'value' => $__vars['nodeStyling']['styling_options']['background_image_url']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Background image URL',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'background_image_url', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[background_color][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['background_color']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[background_color][value]',
		'value' => $__vars['nodeStyling']['styling_options']['background_color']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Background color',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'background_color', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[text_color][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['text_color']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[text_color][value]',
		'value' => $__vars['nodeStyling']['styling_options']['text_color']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Text color',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'text_color', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[retain_text_styling][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['retain_text_styling']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'styling_options[retain_text_styling][value]',
		'value' => $__vars['nodeStyling']['styling_options']['retain_text_styling']['value'],
	), array(array(
		'value' => '0',
		'label' => 'No',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Yes',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	)), array(
		'label' => 'Retain text styling',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'retain_text_styling', ), true),
	)) . '
		</div>

		' . $__compilerTemp1 . '

		<h3 class="block-header"><span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
			' . 'Node icons' . '
		</span></h3>
		<div class="block-body block-body--collapsible">
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[category_icon_class][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['category_icon_class']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[category_icon_class][value]',
		'value' => $__vars['nodeStyling']['styling_options']['category_icon_class']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Category icon class',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'category_icon_class', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[category_icon_class_unread][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['category_icon_class_unread']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[category_icon_class_unread][value]',
		'value' => $__vars['nodeStyling']['styling_options']['category_icon_class_unread']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Category icon class (unread)',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'category_icon_class_unread', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[forum_icon_class][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['forum_icon_class']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[forum_icon_class][value]',
		'value' => $__vars['nodeStyling']['styling_options']['forum_icon_class']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Forum icon class',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'forum_icon_class', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[forum_icon_class_unread][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['forum_icon_class_unread']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[forum_icon_class_unread][value]',
		'value' => $__vars['nodeStyling']['styling_options']['forum_icon_class_unread']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Forum icon class (unread)',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'forum_icon_class_unread', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[page_icon_class][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['page_icon_class']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[page_icon_class][value]',
		'value' => $__vars['nodeStyling']['styling_options']['page_icon_class']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Page icon class',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'page_icon_class', ), true),
	)) . '
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'styling_options[link_forum_icon_class][enable]',
		'selected' => $__vars['nodeStyling']['styling_options']['link_forum_icon_class']['enable'],
		'label' => 'Use custom value',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'styling_options[link_forum_icon_class][value]',
		'value' => $__vars['nodeStyling']['styling_options']['link_forum_icon_class']['value'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Link forum icon class',
		'hint' => $__templater->func('th_inheritedstylingvalue_nodes', array($__vars['node'], $__vars['style'], 'styling_options', 'link_forum_icon_class', ), true),
	)) . '
		</div>

		' . $__templater->formHiddenVal('style_id', $__vars['style']['style_id'], array(
	)) . '
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('node-layout/save', $__vars['node'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '

';
	return $__finalCompiled;
}
);