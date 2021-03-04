<?php
// FROM HASH: e2d0b00c7bf1e38e8cc1389a36d20652
return array(
'macros' => array('option_form_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '',
		'options' => '!',
		'containerBeforeHtml' => '',
		'tabs' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['options'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['tabs'])) {
			foreach ($__vars['tabs'] AS $__vars['tabIndex'] => $__vars['title']) {
				$__compilerTemp1 .= '
							<a class="tabs-tab"
							   role="tab"
							   tabindex="' . $__templater->escape($__vars['tabIndex']) . '"
							   aria-controls="options' . $__templater->escape($__vars['tabIndex']) . '">
								' . $__templater->escape($__vars['title']) . '
							</a>
						';
			}
		}
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['tabs'])) {
			foreach ($__vars['tabs'] AS $__vars['tabIndex'] => $__vars['title']) {
				$__compilerTemp2 .= '
						<li role="tabpanel" id="options' . $__templater->escape($__vars['tabIndex']) . '">
							<div class="block-body">
								';
				if ($__templater->isTraversable($__vars['options'][$__vars['tabIndex']])) {
					foreach ($__vars['options'][$__vars['tabIndex']] AS $__vars['option']) {
						$__compilerTemp2 .= '

									';
						if ($__vars['group']) {
							$__compilerTemp2 .= '
										';
							$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
							$__compilerTemp2 .= '
										';
							if (($__vars['curHundred'] > $__vars['hundred'])) {
								$__compilerTemp2 .= '
											';
								$__vars['hundred'] = $__vars['curHundred'];
								$__compilerTemp2 .= '
											<hr class="formRowSep" />
										';
							}
							$__compilerTemp2 .= '
									';
						}
						$__compilerTemp2 .= '

									' . $__templater->callMacro('option_macros', 'option_row', array(
							'group' => $__vars['group'],
							'option' => $__vars['option'],
						), $__vars) . '
								';
					}
				}
				$__compilerTemp2 .= '
							</div>
						</li>
					';
			}
		}
		$__finalCompiled .= $__templater->form('
			<div class="block-container">
				' . $__templater->filter($__vars['containerBeforeHtml'], array(array('raw', array()),), true) . '
				<h2 class="block-tabHeader tabs hScroller"
					data-xf-init="tabs h-scroller"
					role="tablist">
					<span class="hScroller-scroll">
						' . $__compilerTemp1 . '
					</span>
				</h2>
				<ul class="tabPanes">
					' . $__compilerTemp2 . '
				</ul>
				' . $__templater->formSubmitRow(array(
			'sticky' => 'true',
			'icon' => 'save',
		), array(
		)) . '
			</div>
		', array(
			'action' => $__templater->func('link', array('options/update', ), false),
			'ajax' => 'true',
			'class' => 'block',
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