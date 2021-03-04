<?php
// FROM HASH: bdbdeb28009d392201de78f61a132177
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['thmonetize_upgrade']) {
		$__finalCompiled .= '
	' . $__templater->formHiddenVal('thmonetize_user_upgrade_id', $__vars['fields']['thmonetize_user_upgrade_id'], array(
		)) . '
	' . $__templater->formRow('
		' . $__templater->escape($__vars['thmonetize_upgrade']['title']) . ' (' . $__templater->escape($__vars['thmonetize_upgrade']['cost_phrase']) . ')
	', array(
			'label' => 'Account upgrade',
			'explain' => $__templater->escape($__vars['thmonetize_upgrade']['description']),
		)) . '
	';
		if (($__templater->func('count', array($__vars['thmonetize_upgrade']['payment_profile_ids'], ), false) > 1)) {
			$__finalCompiled .= '
		';
			$__compilerTemp1 = array(array(
				'label' => $__vars['xf']['language']['parenthesis_open'] . 'Choose a payment method' . $__vars['xf']['language']['parenthesis_close'],
				'_type' => 'option',
			));
			if ($__templater->isTraversable($__vars['thmonetize_upgrade']['payment_profile_ids'])) {
				foreach ($__vars['thmonetize_upgrade']['payment_profile_ids'] AS $__vars['profileId']) {
					$__compilerTemp1[] = array(
						'value' => $__vars['profileId'],
						'label' => $__templater->escape($__vars['thmonetize_profiles'][$__vars['profileId']]),
						'_type' => 'option',
					);
				}
			}
			$__finalCompiled .= $__templater->formSelectRow(array(
				'name' => 'thmonetize_payment_profile_id',
				'value' => $__vars['fields']['thmonetize_payment_profile_id'],
			), $__compilerTemp1, array(
				'label' => 'Payment method',
				'hint' => 'Required',
			)) . '
	';
		} else {
			$__finalCompiled .= '
		' . $__templater->formHiddenVal('thmonetize_payment_profile_id', $__templater->filter($__vars['thmonetize_upgrade']['payment_profile_ids'], array(array('first', array()),), false), array(
			)) . '
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);