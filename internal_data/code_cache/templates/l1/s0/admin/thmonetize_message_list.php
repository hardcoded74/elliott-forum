<?php
// FROM HASH: 40a09ecb1c46cfdc6594d7f6903c7b43
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Messages');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add message', array(
		'href' => $__templater->func('link', array('monetize-messages/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['messages'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['messages'])) {
			foreach ($__vars['messages'] AS $__vars['message']) {
				$__compilerTemp1 .= '
						';
				$__compilerTemp2 = '';
				if ($__vars['message']['active']) {
					$__compilerTemp2 .= '
									' . (('Next send' . ': ') . $__templater->func('date_time', array($__vars['message']['next_send'], ), true)) . '
								';
				}
				$__compilerTemp1 .= $__templater->dataRow(array(
					'label' => $__templater->escape($__vars['message']['title']),
					'href' => $__templater->func('link', array('monetize-messages/edit', $__vars['message'], ), false),
					'delete' => $__templater->func('link', array('monetize-messages/delete', $__vars['message'], ), false),
					'explain' => '
								' . $__compilerTemp2 . '
							',
				), array(array(
					'name' => 'active[' . $__vars['message']['message_id'] . ']',
					'selected' => $__vars['message']['active'],
					'class' => 'dataList-cell--separated',
					'submit' => 'true',
					'tooltip' => 'Enable / disable \'' . $__vars['message']['title'] . '\'',
					'_type' => 'toggle',
					'html' => '',
				),
				array(
					'href' => $__templater->func('link', array('monetize-messages/send', $__vars['message'], ), false),
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
			'key' => 'monetize-messages',
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
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['totalMessages'], ), true) . '</span>
				</div>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('monetize-messages/toggle', ), false),
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