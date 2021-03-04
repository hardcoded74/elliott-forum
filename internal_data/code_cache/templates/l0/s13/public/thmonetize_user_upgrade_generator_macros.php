<?php
// FROM HASH: 4de9bd4e59aebcacd03b4c6b3786846c
return array(
'macros' => array('generator' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'upgrade' => '!',
		'isLight' => '!',
		'color' => '!',
		'shape' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	.thmonetize_upgrade--' . $__templater->escape($__vars['upgrade']['user_upgrade_id']) . ' {
		--upgrade-backgroundColor: ' . $__templater->filter($__vars['color'], array(array('raw', array()),), true) . ';
		--upgrade-textColor: ' . ($__vars['isLight'] ? '#fff' : '#000') . ';

		i {
			color: ' . $__templater->filter($__vars['color'], array(array('raw', array()),), true) . ';
		}

		.block-header, .formRow > dt {
			background: ' . $__templater->filter($__vars['color'], array(array('raw', array()),), true) . ';
			color: ' . ($__vars['isLight'] ? '#fff' : '#000') . ';
		}

		.formRow .formRow-hint {
			color: ' . ($__vars['isLight'] ? '#fff' : '#000') . ';
		}

		.thmonetize_upgradeHeader__priceRow {
			text-shadow: 0 0 2px ' . $__templater->filter($__vars['color'], array(array('raw', array()),), true) . ';
		}

		';
	if (($__vars['shape'] === 'circle') OR ($__vars['shape'] === 'square')) {
		$__finalCompiled .= '
			.thmonetize_upgradeHeader--shape {
				background: xf-diminish(' . $__templater->filter($__vars['color'], array(array('raw', array()),), true) . ', 14%);
				.thmonetize_upgradeHeader__price {
					background: ' . $__templater->filter($__vars['color'], array(array('raw', array()),), true) . ';
					color: ' . ($__vars['isLight'] ? '#fff' : '#000') . ';
				}
			}
		';
	} else if ($__vars['shape']) {
		$__finalCompiled .= '
			.thmonetize_upgradeHeader--shape {
				background: xf-diminish(' . $__templater->filter($__vars['color'], array(array('raw', array()),), true) . ', 14%);
				.thmonetize_upgradeHeader__price {
					color: ' . ($__vars['isLight'] ? '#fff' : '#000') . ';
					&:before {
						color: ' . $__templater->filter($__vars['color'], array(array('raw', array()),), true) . ';
					}
				}
			}
		';
	}
	$__finalCompiled .= '
	}
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