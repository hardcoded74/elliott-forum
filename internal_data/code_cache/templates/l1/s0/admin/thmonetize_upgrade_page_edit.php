<?php
// FROM HASH: 370cf9de3424d9180e92dde4ba8d2d7e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['upgradePage'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add upgrade page');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit upgrade page' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['upgradePage']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['upgradePage'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('upgrade-pages/delete', $__vars['upgradePage'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['upgrades'])) {
		foreach ($__vars['upgrades'] AS $__vars['userUpgrade']) {
			$__compilerTemp1[] = array(
				'name' => 'relations[' . $__vars['userUpgrade']['user_upgrade_id'] . '][selected]',
				'selected' => $__vars['relations'][$__vars['userUpgrade']['user_upgrade_id']] !== null,
				'label' => $__templater->escape($__vars['userUpgrade']['title']) . ' (' . $__templater->escape($__vars['userUpgrade']['cost_phrase']) . ')',
				'data-hide' => 'true',
				'_dependent' => array($__templater->callMacro('display_order_macros', 'input', array(
				'name' => 'relations[' . $__vars['userUpgrade']['user_upgrade_id'] . '][display_order]',
				'value' => $__templater->filter($__vars['relations'][$__vars['userUpgrade']['user_upgrade_id']]['display_order'], array(array('default', array(1, )),), false),
			), $__vars), $__templater->formCheckBox(array(
			), array(array(
				'name' => 'relations[' . $__vars['userUpgrade']['user_upgrade_id'] . '][featured]',
				'checked' => $__vars['relations'][$__vars['userUpgrade']['user_upgrade_id']]['featured'],
				'label' => 'Display as featured',
				'_type' => 'option',
			)))),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = array(array(
		'name' => 'accounts_page_link',
		'selected' => $__vars['upgradePage']['accounts_page_link'],
		'label' => 'Link to accounts page',
		'hint' => 'This will add a \'Show more upgrades\' link to the default account upgrades page. This is recommended if you are not showing all available and purchased user upgrades.',
		'_type' => 'option',
	));
	if (!$__templater->test($__vars['upgradePages'], 'empty', array())) {
		$__compilerTemp3 = array();
		if ($__templater->isTraversable($__vars['upgradePages'])) {
			foreach ($__vars['upgradePages'] AS $__vars['upgradePageId'] => $__vars['_upgradePage']) {
				$__compilerTemp3[] = array(
					'name' => 'upgrade_page_links[]',
					'value' => $__vars['upgradePageId'],
					'selected' => $__templater->func('in_array', array($__vars['upgradePageId'], $__vars['upgradePage']['upgrade_page_links'], ), false),
					'label' => $__templater->escape($__vars['_upgradePage']['title']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp2[] = array(
			'selected' => $__vars['upgradePage']['upgrade_page_links'],
			'label' => 'Link to other upgrade pages',
			'hint' => 'This will add a link to the following upgrade pages if the user satisfies the user criteria.<br />
<b>Note:</b> page criteria and availability of user upgrades will not be checked.',
			'_dependent' => array($__templater->formCheckBox(array(
			'listclass' => 'listColumns',
		), $__compilerTemp3)),
			'_type' => 'option',
		);
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h2 class="block-tabHeader tabs hScroller" data-xf-init="tabs h-scroller" role="tablist">
			<span class="hScroller-scroll">
				<a class="tabs-tab is-active" role="tab" tabindex="0" aria-controls="general-options">' . 'General options' . '</a>
				' . $__templater->callMacro('helper_criteria', 'user_tabs', array(), $__vars) . '
				' . $__templater->callMacro('helper_criteria', 'page_tabs', array(), $__vars) . '
			</span>
		</h2>

		<ul class="tabPanes block-body">
			<li class="is-active" role="tabpanel" id="general-options">
				' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['upgradePage']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['upgradePage'], 'title', ), false),
	), array(
		'label' => 'Title',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formNumberBoxRow(array(
		'name' => 'display_order',
		'min' => '0',
		'value' => $__vars['upgradePage']['display_order'],
	), array(
		'label' => 'Display order',
		'explain' => 'Users will see the first upgrade page, sorted by display order, for which they satisfy the given user criteria and page criteria (if any).',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'selected' => $__vars['upgradePage']['active'],
		'label' => 'Active',
		'_type' => 'option',
	),
	array(
		'name' => 'accounts_page',
		'selected' => $__vars['upgradePage']['accounts_page'],
		'label' => 'Show as accounts page',
		'hint' => 'This will allow the upgrade page to be shown instead of the default account upgrades page.',
		'_type' => 'option',
	),
	array(
		'name' => 'error_message',
		'selected' => $__vars['upgradePage']['error_message'],
		'label' => 'Show as error message',
		'hint' => 'This will allow the upgrade page to be shown instead of a no permission error.<br />
<b>Note:</b> this requires the global <a href="admin.php?options/groups/thmonetize/">Suggest user upgrades on no permission error</a> option to be enabled.',
		'_type' => 'option',
	),
	array(
		'name' => 'overlay',
		'selected' => $__vars['upgradePage']['overlay'],
		'label' => 'Show as overlay',
		'hint' => 'This will show the user upgrades page as an overlay on pages that match the pages criteria.',
		'_dependent' => array($__templater->formCheckBox(array(
	), array(array(
		'name' => 'overlay_dismissible',
		'selected' => $__vars['upgradePage']['overlay_dismissible'],
		'label' => 'Overlay can be dismissed',
		'hint' => 'If this is unchecked, the overlay will not be able to be closed.',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	)), array(
		'label' => 'Options',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formRadioRow(array(
		'name' => 'show_all',
		'value' => $__vars['upgradePage']['show_all'],
	), array(array(
		'value' => '1',
		'label' => 'Show all user upgrades',
		'hint' => 'This will show all available and purchased user upgrades.',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => 'Show selected user upgrades',
		'hint' => 'This will show only the below selected user upgrades, sorted by the display orders given.',
		'_dependent' => array($__templater->formCheckBox(array(
		'listclass' => 'listColumns',
	), $__compilerTemp1)),
		'_type' => 'option',
	)), array(
		'label' => 'User upgrades',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formCheckBoxRow(array(
	), $__compilerTemp2, array(
		'label' => 'Links',
	)) . '

				<hr class="formRowSep" />

				' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'page_criteria_overlay_only',
		'selected' => $__vars['upgradePage']['page_criteria_overlay_only'],
		'label' => 'Apply page criteria to overlays only',
		'hint' => 'Most of the page criteria are not applicable or will not work correctly when applied to the account upgrades page or in place of no permission errors. If you know what you are doing and you would like to apply page criteria in all situations, uncheck this checkbox. Otherwise, we recommend leaving this checkbox as checked.',
		'_type' => 'option',
	)), array(
		'label' => 'Advanced options',
	)) . '
			</li>

			' . $__templater->callMacro('helper_criteria', 'user_panes', array(
		'criteria' => $__templater->method($__vars['userCriteria'], 'getCriteriaForTemplate', array()),
		'data' => $__templater->method($__vars['userCriteria'], 'getExtraTemplateData', array()),
	), $__vars) . '

			' . $__templater->callMacro('helper_criteria', 'page_panes', array(
		'criteria' => $__templater->method($__vars['pageCriteria'], 'getCriteriaForTemplate', array()),
		'data' => $__templater->method($__vars['pageCriteria'], 'getExtraTemplateData', array()),
	), $__vars) . '
			
		</ul>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('upgrade-pages/save', $__vars['upgradePage'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);