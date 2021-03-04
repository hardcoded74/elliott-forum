<?php
// FROM HASH: 3044a7b3883d7c19dd3f1064edddcf9e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['userUpgrades'])) {
		foreach ($__vars['userUpgrades'] AS $__vars['upgradeId'] => $__vars['upgrade']) {
			$__finalCompiled .= '
	';
			$__vars['optionName'] = 'alt_url.' . $__vars['upgradeId'];
			$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
				'name' => 'options[' . $__vars['optionName'] . ']',
				'value' => $__vars['profile']['options'][$__vars['optionName']],
			), array(
				'label' => $__templater->escape($__vars['upgrade']['title']),
				'explain' => 'Alternative URL for ' . $__templater->escape($__vars['upgrade']['title']) . '.',
			)) . '
';
		}
	}
	return $__finalCompiled;
}
);