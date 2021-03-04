<?php
// FROM HASH: 602f4a029bbd58717090e8218f4423dc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Emails');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add email', array(
		'href' => $__templater->func('link', array('monetize-emails/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['emails'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['emails'])) {
			foreach ($__vars['emails'] AS $__vars['email']) {
				$__compilerTemp1 .= '
						';
				$__compilerTemp2 = '';
				if ($__vars['email']['active']) {
					$__compilerTemp2 .= '
									' . (('Next send' . ': ') . $__templater->func('date_time', array($__vars['email']['next_send'], ), true)) . '
								';
				}
				$__compilerTemp1 .= $__templater->dataRow(array(
					'label' => $__templater->escape($__vars['email']['title']),
					'href' => $__templater->func('link', array('monetize-emails/edit', $__vars['email'], ), false),
					'delete' => $__templater->func('link', array('monetize-emails/delete', $__vars['email'], ), false),
					'explain' => '
								' . $__compilerTemp2 . '
							',
				), array(array(
					'name' => 'active[' . $__vars['email']['email_id'] . ']',
					'selected' => $__vars['email']['active'],
					'class' => 'dataList-cell--separated',
					'submit' => 'true',
					'tooltip' => 'Enable / disable \'' . $__vars['email']['title'] . '\'',
					'_type' => 'toggle',
					'html' => '',
				),
				array(
					'href' => $__templater->func('link', array('monetize-emails/send', $__vars['email'], ), false),
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
			'key' => 'monetize-emails',
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
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['totalEmails'], ), true) . '</span>
				</div>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('monetize-emails/toggle', ), false),
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