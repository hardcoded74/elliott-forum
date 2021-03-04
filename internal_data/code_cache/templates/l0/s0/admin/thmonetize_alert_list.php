<?php
// FROM HASH: 7f42b0dd5c7343200a0bc5ba2de6964f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Alerts');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add alert', array(
		'href' => $__templater->func('link', array('monetize-alerts/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['alerts'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['alerts'])) {
			foreach ($__vars['alerts'] AS $__vars['alert']) {
				$__compilerTemp1 .= '
						';
				$__compilerTemp2 = '';
				if ($__vars['alert']['active']) {
					$__compilerTemp2 .= '
									' . (('Next send' . ': ') . $__templater->func('date_time', array($__vars['alert']['next_send'], ), true)) . '
								';
				}
				$__compilerTemp1 .= $__templater->dataRow(array(
					'label' => $__templater->escape($__vars['alert']['title']),
					'href' => $__templater->func('link', array('monetize-alerts/edit', $__vars['alert'], ), false),
					'delete' => $__templater->func('link', array('monetize-alerts/delete', $__vars['alert'], ), false),
					'explain' => '
								' . $__compilerTemp2 . '
							',
				), array(array(
					'name' => 'active[' . $__vars['alert']['alert_id'] . ']',
					'selected' => $__vars['alert']['active'],
					'class' => 'dataList-cell--separated',
					'submit' => 'true',
					'tooltip' => 'Enable / disable \'' . $__vars['alert']['title'] . '\'',
					'_type' => 'toggle',
					'html' => '',
				),
				array(
					'href' => $__templater->func('link', array('monetize-alerts/send', $__vars['alert'], ), false),
					'overlay' => 'true',
					'data-xf-init' => 'tooltip',
					'title' => 'Send now',
					'class' => 'dataList-cell--iconic',
					'_type' => 'action',
					'html' => '
								' . $__templater->fontAwesome('fa-envelope', array(
				)) . '
							',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'monetize-alerts',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				' . $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
				<div class="block-footer">
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['totalAlerts'], ), true) . '</span>
				</div>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('monetize-alerts/toggle', ), false),
			'class' => 'block',
			'ajax' => 'true',
		)) . '
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	return $__finalCompiled;
}
);