<?php
// FROM HASH: b3b97ddccda81a8071acc25ca0bdd6d8
return array(
'macros' => array('time' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'sendRules' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formRadioRow(array(
		'name' => 'send_rules[day_type]',
		'value' => $__vars['sendRules']['day_type'],
	), array(array(
		'value' => 'dom',
		'label' => 'Day of the month' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'send_rules[dom]',
		'value' => ($__vars['sendRules']['dom'] ?: $__templater->filter(array(-1, ), array(array('raw', array()),), false)),
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => '1',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => '2',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => '3',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => '4',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => '5',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => '6',
		'_type' => 'option',
	),
	array(
		'value' => '7',
		'label' => '7',
		'_type' => 'option',
	),
	array(
		'value' => '8',
		'label' => '8',
		'_type' => 'option',
	),
	array(
		'value' => '9',
		'label' => '9',
		'_type' => 'option',
	),
	array(
		'value' => '10',
		'label' => '10',
		'_type' => 'option',
	),
	array(
		'value' => '11',
		'label' => '11',
		'_type' => 'option',
	),
	array(
		'value' => '12',
		'label' => '12',
		'_type' => 'option',
	),
	array(
		'value' => '13',
		'label' => '13',
		'_type' => 'option',
	),
	array(
		'value' => '14',
		'label' => '14',
		'_type' => 'option',
	),
	array(
		'value' => '15',
		'label' => '15',
		'_type' => 'option',
	),
	array(
		'value' => '16',
		'label' => '16',
		'_type' => 'option',
	),
	array(
		'value' => '17',
		'label' => '17',
		'_type' => 'option',
	),
	array(
		'value' => '18',
		'label' => '18',
		'_type' => 'option',
	),
	array(
		'value' => '19',
		'label' => '19',
		'_type' => 'option',
	),
	array(
		'value' => '20',
		'label' => '20',
		'_type' => 'option',
	),
	array(
		'value' => '21',
		'label' => '21',
		'_type' => 'option',
	),
	array(
		'value' => '22',
		'label' => '22',
		'_type' => 'option',
	),
	array(
		'value' => '23',
		'label' => '23',
		'_type' => 'option',
	),
	array(
		'value' => '24',
		'label' => '24',
		'_type' => 'option',
	),
	array(
		'value' => '25',
		'label' => '25',
		'_type' => 'option',
	),
	array(
		'value' => '26',
		'label' => '26',
		'_type' => 'option',
	),
	array(
		'value' => '27',
		'label' => '27',
		'_type' => 'option',
	),
	array(
		'value' => '28',
		'label' => '28',
		'_type' => 'option',
	),
	array(
		'value' => '29',
		'label' => '29',
		'_type' => 'option',
	),
	array(
		'value' => '30',
		'label' => '30',
		'_type' => 'option',
	),
	array(
		'value' => '31',
		'label' => '31',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	),
	array(
		'value' => 'dow',
		'label' => 'Day of the week' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'send_rules[dow]',
		'value' => ($__vars['sendRules']['dow'] ?: $__templater->filter(array(-1, ), array(array('raw', array()),), false)),
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => 'Sunday',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Monday',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => 'Tuesday',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => 'Wednesday',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => 'Thursday',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => 'Friday',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => 'Saturday',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	)), array(
		'label' => 'Send on day(s)',
	)) . '

	' . $__templater->formSelectRow(array(
		'name' => 'send_rules[hours]',
		'value' => ($__vars['sendRules']['hours'] ?: $__templater->filter(array(-1, ), array(array('raw', array()),), false)),
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => '0 ' . $__vars['xf']['language']['parenthesis_open'] . 'Midnight' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => '1',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => '2',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => '3',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => '4',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => '5',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => '6',
		'_type' => 'option',
	),
	array(
		'value' => '7',
		'label' => '7',
		'_type' => 'option',
	),
	array(
		'value' => '8',
		'label' => '8',
		'_type' => 'option',
	),
	array(
		'value' => '9',
		'label' => '9',
		'_type' => 'option',
	),
	array(
		'value' => '10',
		'label' => '10',
		'_type' => 'option',
	),
	array(
		'value' => '11',
		'label' => '11',
		'_type' => 'option',
	),
	array(
		'value' => '12',
		'label' => '12 ' . $__vars['xf']['language']['parenthesis_open'] . 'Noon' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	),
	array(
		'value' => '13',
		'label' => '13',
		'_type' => 'option',
	),
	array(
		'value' => '14',
		'label' => '14',
		'_type' => 'option',
	),
	array(
		'value' => '15',
		'label' => '15',
		'_type' => 'option',
	),
	array(
		'value' => '16',
		'label' => '16',
		'_type' => 'option',
	),
	array(
		'value' => '17',
		'label' => '17',
		'_type' => 'option',
	),
	array(
		'value' => '18',
		'label' => '18',
		'_type' => 'option',
	),
	array(
		'value' => '19',
		'label' => '19',
		'_type' => 'option',
	),
	array(
		'value' => '20',
		'label' => '20',
		'_type' => 'option',
	),
	array(
		'value' => '21',
		'label' => '21',
		'_type' => 'option',
	),
	array(
		'value' => '22',
		'label' => '22',
		'_type' => 'option',
	),
	array(
		'value' => '23',
		'label' => '23',
		'_type' => 'option',
	)), array(
		'label' => 'Send at hour(s)',
		'explain' => 'Send times are based on the UTC time zone.',
	)) . '

	' . $__templater->formSelectRow(array(
		'name' => 'send_rules[minutes]',
		'value' => ($__vars['sendRules']['minutes'] ?: $__templater->filter(array(-1, ), array(array('raw', array()),), false)),
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => '0',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => '1',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => '2',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => '3',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => '4',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => '5',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => '6',
		'_type' => 'option',
	),
	array(
		'value' => '7',
		'label' => '7',
		'_type' => 'option',
	),
	array(
		'value' => '8',
		'label' => '8',
		'_type' => 'option',
	),
	array(
		'value' => '9',
		'label' => '9',
		'_type' => 'option',
	),
	array(
		'value' => '10',
		'label' => '10',
		'_type' => 'option',
	),
	array(
		'value' => '11',
		'label' => '11',
		'_type' => 'option',
	),
	array(
		'value' => '12',
		'label' => '12',
		'_type' => 'option',
	),
	array(
		'value' => '13',
		'label' => '13',
		'_type' => 'option',
	),
	array(
		'value' => '14',
		'label' => '14',
		'_type' => 'option',
	),
	array(
		'value' => '15',
		'label' => '15',
		'_type' => 'option',
	),
	array(
		'value' => '16',
		'label' => '16',
		'_type' => 'option',
	),
	array(
		'value' => '17',
		'label' => '17',
		'_type' => 'option',
	),
	array(
		'value' => '18',
		'label' => '18',
		'_type' => 'option',
	),
	array(
		'value' => '19',
		'label' => '19',
		'_type' => 'option',
	),
	array(
		'value' => '20',
		'label' => '20',
		'_type' => 'option',
	),
	array(
		'value' => '21',
		'label' => '21',
		'_type' => 'option',
	),
	array(
		'value' => '22',
		'label' => '22',
		'_type' => 'option',
	),
	array(
		'value' => '23',
		'label' => '23',
		'_type' => 'option',
	),
	array(
		'value' => '24',
		'label' => '24',
		'_type' => 'option',
	),
	array(
		'value' => '25',
		'label' => '25',
		'_type' => 'option',
	),
	array(
		'value' => '26',
		'label' => '26',
		'_type' => 'option',
	),
	array(
		'value' => '27',
		'label' => '27',
		'_type' => 'option',
	),
	array(
		'value' => '28',
		'label' => '28',
		'_type' => 'option',
	),
	array(
		'value' => '29',
		'label' => '29',
		'_type' => 'option',
	),
	array(
		'value' => '30',
		'label' => '30',
		'_type' => 'option',
	),
	array(
		'value' => '31',
		'label' => '31',
		'_type' => 'option',
	),
	array(
		'value' => '32',
		'label' => '32',
		'_type' => 'option',
	),
	array(
		'value' => '33',
		'label' => '33',
		'_type' => 'option',
	),
	array(
		'value' => '34',
		'label' => '34',
		'_type' => 'option',
	),
	array(
		'value' => '35',
		'label' => '35',
		'_type' => 'option',
	),
	array(
		'value' => '36',
		'label' => '36',
		'_type' => 'option',
	),
	array(
		'value' => '37',
		'label' => '37',
		'_type' => 'option',
	),
	array(
		'value' => '38',
		'label' => '38',
		'_type' => 'option',
	),
	array(
		'value' => '39',
		'label' => '39',
		'_type' => 'option',
	),
	array(
		'value' => '40',
		'label' => '40',
		'_type' => 'option',
	),
	array(
		'value' => '41',
		'label' => '41',
		'_type' => 'option',
	),
	array(
		'value' => '42',
		'label' => '42',
		'_type' => 'option',
	),
	array(
		'value' => '43',
		'label' => '43',
		'_type' => 'option',
	),
	array(
		'value' => '44',
		'label' => '44',
		'_type' => 'option',
	),
	array(
		'value' => '45',
		'label' => '45',
		'_type' => 'option',
	),
	array(
		'value' => '46',
		'label' => '46',
		'_type' => 'option',
	),
	array(
		'value' => '47',
		'label' => '47',
		'_type' => 'option',
	),
	array(
		'value' => '48',
		'label' => '48',
		'_type' => 'option',
	),
	array(
		'value' => '49',
		'label' => '49',
		'_type' => 'option',
	),
	array(
		'value' => '50',
		'label' => '50',
		'_type' => 'option',
	),
	array(
		'value' => '51',
		'label' => '51',
		'_type' => 'option',
	),
	array(
		'value' => '52',
		'label' => '52',
		'_type' => 'option',
	),
	array(
		'value' => '53',
		'label' => '53',
		'_type' => 'option',
	),
	array(
		'value' => '54',
		'label' => '54',
		'_type' => 'option',
	),
	array(
		'value' => '55',
		'label' => '55',
		'_type' => 'option',
	),
	array(
		'value' => '56',
		'label' => '56',
		'_type' => 'option',
	),
	array(
		'value' => '57',
		'label' => '57',
		'_type' => 'option',
	),
	array(
		'value' => '58',
		'label' => '58',
		'_type' => 'option',
	),
	array(
		'value' => '59',
		'label' => '59',
		'_type' => 'option',
	)), array(
		'label' => 'Send at minute(s)',
	)) . '
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