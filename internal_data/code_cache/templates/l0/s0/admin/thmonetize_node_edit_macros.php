<?php
// FROM HASH: 1f6d26d65537b2c707eb95b59251315a
return array(
'macros' => array('sponsor' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
		'sponsors' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['sponsors'], 'empty', array())) {
		$__finalCompiled .= '
		<hr class="formRowSep" />
		';
		$__compilerTemp1 = array(array(
			'value' => '0',
			'label' => 'None',
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['sponsors'])) {
			foreach ($__vars['sponsors'] AS $__vars['sponsorId'] => $__vars['sponsorTitle']) {
				$__compilerTemp1[] = array(
					'value' => $__vars['sponsorId'],
					'label' => $__templater->escape($__vars['sponsorTitle']),
					'_type' => 'option',
				);
			}
		}
		$__finalCompiled .= $__templater->formSelectRow(array(
			'name' => 'node[th_sponsor_id]',
			'value' => $__vars['node']['th_sponsor_id'],
		), $__compilerTemp1, array(
			'label' => 'Sponsor',
		)) . '
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

	return $__finalCompiled;
}
);