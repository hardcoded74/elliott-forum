<?php
// FROM HASH: 2d41f43db9ef8b3478262b686fc448c5
return array(
'macros' => array('after_can_purchase' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'upgrade' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'thmonetize_allow_multiple',
		'selected' => $__vars['upgrade']['thmonetize_allow_multiple'],
		'label' => 'Can be purchased multiple times',
		'hint' => 'This will allow an upgrade to be purchased again by the same user, even if their existing upgrade purchase has not yet expired.',
		'_type' => 'option',
	)), array(
		'label' => '',
	)) . '
';
	return $__finalCompiled;
}
),
'after_cost' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'upgrade' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'thmonetize_custom_amount',
		'selected' => $__vars['upgrade']['thmonetize_custom_amount'],
		'label' => 'Allow custom amount',
		'hint' => 'This will allow users to override the above cost amount when they are purchasing the upgrade.<br/><br/>
<b>Note:</b> If you later uninstall [TH] Monetize, recurring upgrades purchased with a custom amount may be rejected.',
		'_type' => 'option',
	)), array(
		'label' => 'Cost options',
	)) . '
';
	return $__finalCompiled;
}
),
'form_bottom' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'upgrade' => '!',
		'upgradePageRelations' => '!',
		'upgradePages' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'xf/sort.js, vendor/dragula/dragula.js',
	));
	$__finalCompiled .= '
	';
	$__templater->includeCss('public:dragula.less');
	$__finalCompiled .= '

	<h3 class="block-formSectionHeader">
		<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
			<span class="block-formSectionHeader-aligner">' . 'Style properties' . '</span>
		</span>
	</h3>
	<div class="block-body block-body--collapsible' . ($__vars['upgrade']['thmonetize_style_properties'] ? ' is-active' : '') . '">
		' . $__templater->formCheckBoxRow(array(
	), array(array(
		'selected' => $__vars['upgrade']['thmonetize_style_properties']['color'],
		'label' => 'Background color' . $__vars['xf']['language']['label_separator'],
		'data-hide' => 'true',
		'_dependent' => array($__templater->callMacro('public:color_picker_macros', 'color_picker', array(
		'name' => 'thmonetize_style_properties[color]',
		'value' => $__vars['upgrade']['thmonetize_style_properties']['color'],
		'allowPalette' => true,
		'row' => false,
	), $__vars)),
		'_type' => 'option',
	),
	array(
		'selected' => $__vars['upgrade']['thmonetize_style_properties']['shape'],
		'label' => 'Shape' . $__vars['xf']['language']['label_separator'],
		'data-hide' => 'true',
		'_dependent' => array($__templater->formRadio(array(
		'listclass' => 'listColumns',
		'value' => ($__vars['upgrade']['thmonetize_style_properties']['shape'] ?: 'circle'),
		'name' => 'thmonetize_style_properties[shape]',
	), array(array(
		'value' => 'circle',
		'label' => 'Circle',
		'_type' => 'option',
	),
	array(
		'value' => 'square',
		'label' => 'Square',
		'_type' => 'option',
	),
	array(
		'value' => 'star',
		'label' => 'Star',
		'_type' => 'option',
	),
	array(
		'value' => 'certificate',
		'label' => 'Certificate',
		'_type' => 'option',
	),
	array(
		'value' => 'cloud',
		'label' => 'Cloud',
		'_type' => 'option',
	),
	array(
		'value' => 'comment-alt',
		'label' => 'Comment',
		'_type' => 'option',
	),
	array(
		'value' => 'folder',
		'label' => 'Folder',
		'_type' => 'option',
	),
	array(
		'value' => 'heart',
		'label' => 'Heart',
		'_type' => 'option',
	),
	array(
		'value' => 'hexagon',
		'label' => 'Hexagon',
		'_type' => 'option',
	),
	array(
		'value' => 'poop',
		'label' => 'Poop',
		'_type' => 'option',
	),
	array(
		'value' => 'shield',
		'label' => 'Shield',
		'_type' => 'option',
	),
	array(
		'value' => 'weight-hanging',
		'label' => 'Weight',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	),
	array(
		'selected' => $__vars['upgrade']['thmonetize_style_properties']['icon'],
		'label' => 'Feature list icon class' . $__vars['xf']['language']['label_separator'],
		'data-hide' => 'true',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'thmonetize_style_properties[icon]',
		'value' => $__vars['upgrade']['thmonetize_style_properties']['icon'],
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Style properties',
	)) . '
	</div>

	<h3 class="block-formSectionHeader">
		<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
			<span class="block-formSectionHeader-aligner">' . 'Features' . '</span>
		</span>
	</h3>
	<div class="block-body block-body--collapsible' . ($__vars['upgrade']['thmonetize_features'] ? ' is-active' : '') . '">
		';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['upgrade']['thmonetize_features'])) {
		foreach ($__vars['upgrade']['thmonetize_features'] AS $__vars['text']) {
			$__compilerTemp1 .= '
					<div class="inputGroup">
						<span class="inputGroup-text dragHandle"
							  aria-label="' . $__templater->filter('Drag handle', array(array('for_attr', array()),), true) . '"></span>
						' . $__templater->formTextBox(array(
				'name' => 'thmonetize_features[]',
				'value' => $__vars['text'],
				'placeholder' => 'Text',
			)) . '
					</div>
				';
		}
	}
	$__finalCompiled .= $__templater->formRow('

			<div class="inputGroup-container" data-xf-init="list-sorter" data-drag-handle=".dragHandle">
				' . $__compilerTemp1 . '
				<div class="inputGroup is-undraggable js-blockDragafter" data-xf-init="field-adder"
					 data-remove-class="is-undraggable js-blockDragafter">
					<span class="inputGroup-text dragHandle"
						aria-label="' . $__templater->filter('Drag handle', array(array('for_attr', array()),), true) . '"></span>
					' . $__templater->formTextBox(array(
		'name' => 'thmonetize_features[]',
		'placeholder' => 'Text',
		'data-i' => '0',
	)) . '
				</div>
			</div>
		', array(
		'rowtype' => 'input',
		'label' => 'Features',
		'explain' => 'Features will be shown as a bullet point list underneath the upgrade description. The bullet point icon styling can be set in style properties above.',
	)) . '
	</div>

	';
	if (!$__templater->test($__vars['upgradePages'], 'empty', array())) {
		$__finalCompiled .= '
		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Upgrade pages' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible' . ($__vars['upgradePageRelations'] ? ' is-active' : '') . '">
			';
		$__compilerTemp2 = array();
		if ($__templater->isTraversable($__vars['upgradePages'])) {
			foreach ($__vars['upgradePages'] AS $__vars['upgradePage']) {
				$__compilerTemp2[] = array(
					'name' => 'upgrade_page_relations[' . $__vars['upgradePage']['upgrade_page_id'] . '][selected]',
					'selected' => $__vars['upgradePageRelations'][$__vars['upgradePage']['upgrade_page_id']] !== null,
					'label' => $__templater->escape($__vars['upgradePage']['title']),
					'data-hide' => 'true',
					'_dependent' => array($__templater->callMacro('display_order_macros', 'input', array(
					'name' => 'upgrade_page_relations[' . $__vars['upgradePage']['upgrade_page_id'] . '][display_order]',
					'value' => $__templater->filter($__vars['upgradePageRelations'][$__vars['upgradePage']['upgrade_page_id']]['display_order'], array(array('default', array(1, )),), false),
				), $__vars), $__templater->formCheckBox(array(
				), array(array(
					'name' => 'upgrade_page_relations[' . $__vars['upgradePage']['upgrade_page_id'] . '][featured]',
					'checked' => $__vars['upgradePageRelations'][$__vars['upgradePage']['upgrade_page_id']]['featured'],
					'label' => 'Display as featured',
					'_type' => 'option',
				)))),
					'_type' => 'option',
				);
			}
		}
		$__finalCompiled .= $__templater->formCheckBoxRow(array(
			'listclass' => 'listColumns',
		), $__compilerTemp2, array(
			'label' => 'Upgrade pages',
		)) . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);