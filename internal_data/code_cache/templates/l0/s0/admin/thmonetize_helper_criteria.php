<?php
// FROM HASH: e7a001281e339af2a39254119e8b4915
return array(
'macros' => array('after_groups' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'criteria' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_criteria[thmonetize_active_upgrades_count][rule]',
		'value' => 'thmonetize_active_upgrades_count',
		'selected' => $__vars['criteria']['thmonetize_active_upgrades_count'],
		'label' => 'User has at least X active upgrades' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[thmonetize_active_upgrades_count][data][upgrades]',
		'value' => $__vars['criteria']['thmonetize_active_upgrades_count']['upgrades'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[thmonetize_active_upgrades_maximum][rule]',
		'value' => 'thmonetize_active_upgrades_maximum',
		'selected' => $__vars['criteria']['thmonetize_active_upgrades_maximum'],
		'label' => 'User has no more than X active upgrades' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[thmonetize_active_upgrades_maximum][data][upgrades]',
		'value' => $__vars['criteria']['thmonetize_active_upgrades_maximum']['upgrades'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[thmonetize_active_upgrades_expiry][rule]',
		'value' => 'thmonetize_active_upgrades_expiry',
		'selected' => $__vars['criteria']['thmonetize_active_upgrades_expiry'],
		'label' => 'User has upgrades expiring in no more than X days' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[thmonetize_active_upgrades_expiry][data][days]',
		'value' => $__vars['criteria']['thmonetize_active_upgrades_expiry']['days'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	)), array(
		'label' => 'User upgrades',
	)) . '

	<hr class="formRowSep" />
';
	return $__finalCompiled;
}
),
'user_upgrade_tabs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'container' => '',
		'userUpgradeTabTitle' => '',
		'active' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['tabs'] = $__templater->preEscaped('
		<a class="tabs-tab' . (($__vars['active'] == 'user_upgrade') ? ' is-active' : '') . '"
			role="tab" tabindex="0" aria-controls="' . $__templater->func('unique_id', array('criteriaUserUpgrade', ), true) . '">
			' . ($__vars['userUpgradeTabTitle'] ? $__templater->escape($__vars['userUpgradeTabTitle']) : 'User upgrade criteria') . '</a>
	');
	$__finalCompiled .= '
	';
	if ($__vars['container']) {
		$__finalCompiled .= '
		<div class="tabs" role="tablist">
			' . $__templater->filter($__vars['tabs'], array(array('raw', array()),), true) . '
		</div>
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['tabs'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'user_upgrade_panes' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'container' => '',
		'active' => '',
		'criteria' => '!',
		'data' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['data']['userUpgrades'])) {
		foreach ($__vars['data']['userUpgrades'] AS $__vars['userUpgradeId'] => $__vars['userUpgradeTitle']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['userUpgradeId'],
				'label' => $__templater->escape($__vars['userUpgradeTitle']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['data']['userUpgrades'])) {
		foreach ($__vars['data']['userUpgrades'] AS $__vars['userUpgradeId'] => $__vars['userUpgradeTitle']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['userUpgradeId'],
				'label' => $__templater->escape($__vars['userUpgradeTitle']),
				'_type' => 'option',
			);
		}
	}
	$__vars['panes'] = $__templater->preEscaped('
		<li class="' . (($__vars['active'] == 'user_upgrade') ? ' is-active' : '') . '" role="tabpanel" id="' . $__templater->func('unique_id', array('criteriaUserUpgrade', ), true) . '">
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_upgrade_criteria[is_active][rule]',
		'value' => 'is_active',
		'selected' => $__vars['criteria']['is_active'],
		'label' => 'User upgrade is active',
		'_type' => 'option',
	),
	array(
		'name' => 'user_upgrade_criteria[is_not_active][rule]',
		'value' => 'is_not_active',
		'selected' => $__vars['criteria']['is_not_active'],
		'label' => 'User upgrade is NOT active',
		'_type' => 'option',
	),
	array(
		'name' => 'user_upgrade_criteria[is_expired][rule]',
		'value' => 'is_expired',
		'selected' => $__vars['criteria']['is_expired'],
		'label' => 'User upgrade is expired',
		'_type' => 'option',
	),
	array(
		'name' => 'user_upgrade_criteria[is_not_expired][rule]',
		'value' => 'is_not_expired',
		'selected' => $__vars['criteria']['is_not_expired'],
		'label' => 'User upgrade is NOT expired',
		'_type' => 'option',
	)), array(
		'label' => 'User upgrade status',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_upgrade_criteria[is_recurring][rule]',
		'value' => 'is_recurring',
		'selected' => $__vars['criteria']['is_recurring'],
		'label' => 'User upgrade is recurring',
		'_type' => 'option',
	),
	array(
		'name' => 'user_upgrade_criteria[is_not_recurring][rule]',
		'value' => 'is_not_recurring',
		'selected' => $__vars['criteria']['is_not_recurring'],
		'label' => 'User upgrade is NOT recurring',
		'_type' => 'option',
	)), array(
		'label' => 'User upgrade details',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_upgrade_criteria[start_date_within][rule]',
		'value' => 'start_date_within',
		'selected' => $__vars['criteria']['start_date_within'],
		'label' => 'User upgrade start date is within X days' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_upgrade_criteria[start_date_within][data][days]',
		'value' => $__vars['criteria']['start_date_within']['days'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_upgrade_criteria[start_date_not_within][rule]',
		'value' => 'start_date_not_within',
		'selected' => $__vars['criteria']['start_date_not_within'],
		'label' => 'User upgrade start date is NOT within X days' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_upgrade_criteria[start_date_not_within][data][days]',
		'value' => $__vars['criteria']['start_date_not_within']['days'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	)), array(
		'label' => 'User upgrade start date',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_upgrade_criteria[end_date_within][rule]',
		'value' => 'end_date_within',
		'selected' => $__vars['criteria']['end_date_within'],
		'label' => 'User upgrade end date is within X days' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_upgrade_criteria[end_date_within][data][days]',
		'value' => $__vars['criteria']['end_date_within']['days'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_upgrade_criteria[end_date_not_within][rule]',
		'value' => 'end_date_not_within',
		'selected' => $__vars['criteria']['end_date_not_within'],
		'label' => 'User upgrade end date is NOT within X days' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_upgrade_criteria[end_date_not_within][data][days]',
		'value' => $__vars['criteria']['end_date_not_within']['days'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	)), array(
		'label' => 'User upgrade end date',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_upgrade_criteria[updated_within][rule]',
		'value' => 'updated_within',
		'selected' => $__vars['criteria']['updated_within'],
		'label' => 'User upgrade last updated is within X days' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_upgrade_criteria[updated_within][data][days]',
		'value' => $__vars['criteria']['updated_within']['days'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_upgrade_criteria[updated_not_within][rule]',
		'value' => 'updated_not_within',
		'selected' => $__vars['criteria']['updated_not_within'],
		'label' => 'User upgrade last updated is NOT within X days' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_upgrade_criteria[updated_not_within][data][days]',
		'value' => $__vars['criteria']['updated_not_within']['days'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	)), array(
		'label' => 'User upgrade last updated',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_upgrade_criteria[user_upgrades][rule]',
		'value' => 'user_upgrades',
		'selected' => $__vars['criteria']['user_upgrades'],
		'label' => 'User upgrade is one of the selected upgrades' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'user_upgrade_criteria[user_upgrades][data][user_upgrade_ids]',
		'size' => '4',
		'multiple' => 'true',
		'value' => $__vars['criteria']['user_upgrades']['user_upgrade_ids'],
	), $__compilerTemp1)),
		'_type' => 'option',
	),
	array(
		'name' => 'user_upgrade_criteria[not_user_upgrades][rule]',
		'value' => 'not_user_upgrades',
		'selected' => $__vars['criteria']['not_user_upgrades'],
		'label' => 'User upgrade is NOT one of the selected upgrades' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'user_upgrade_criteria[not_user_upgrades][data][user_upgrade_ids]',
		'size' => '4',
		'multiple' => 'true',
		'value' => $__vars['criteria']['not_user_upgrades']['user_upgrade_ids'],
	), $__compilerTemp2)),
		'_type' => 'option',
	)), array(
		'label' => 'Specific user upgrades',
	)) . '
		</li>
	');
	$__finalCompiled .= '

	';
	if ($__vars['container']) {
		$__finalCompiled .= '
		<ul class="tabPanes">
			' . $__templater->filter($__vars['panes'], array(array('raw', array()),), true) . '
		</ul>
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['panes'], array(array('raw', array()),), true) . '
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