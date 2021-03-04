<?php
// FROM HASH: 409411bf83a9b5bc9804c468ed6844ca
return array(
'macros' => array('option_form_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '',
		'options' => '!',
		'containerBeforeHtml' => '',
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
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp1 .= '
								';
				if ($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] < 2000) {
					$__compilerTemp1 .= '

									';
					if ($__vars['group']) {
						$__compilerTemp1 .= '
										';
						$__vars['curHundred'] = $__templater->func('floor', array($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] / 100, ), false);
						$__compilerTemp1 .= '
										';
						if (($__vars['curHundred'] > $__vars['hundred'])) {
							$__compilerTemp1 .= '
											';
							$__vars['hundred'] = $__vars['curHundred'];
							$__compilerTemp1 .= '
											<hr class="formRowSep" />
										';
						}
						$__compilerTemp1 .= '
									';
					}
					$__compilerTemp1 .= '

									' . $__templater->callMacro('option_macros', 'option_row', array(
						'group' => $__vars['group'],
						'option' => $__vars['option'],
					), $__vars) . '
								';
				}
				$__compilerTemp1 .= '
							';
			}
		}
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['options'])) {
			foreach ($__vars['options'] AS $__vars['option']) {
				$__compilerTemp2 .= '
								';
				if (($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] >= 2000) AND ($__vars['option']['Relations'][$__vars['group']['group_id']]['display_order'] < 3000)) {
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
				$__compilerTemp2 .= '
							';
			}
		}
		$__finalCompiled .= $__templater->form('
			' . $__templater->filter($__vars['containerBeforeHtml'], array(array('raw', array()),), true) . '
			<div class="block-container">
				<h2 class="block-tabHeader tabs" data-xf-init="tabs" role="tablist">
					<a class="tabs-tab is-active"
					   role="tab"
					   tabindex="0"
					   aria-controls="uixGeneralOptions">
						' . 'General options' . '
					</a>
					<a class="tabs-tab"
					   role="tab"
					   tabindex="0"
					   aria-controls="uixSocialMedia">
						' . 'Social Media' . '
					</a>
				</h2>
				<ul class="tabPanes">
					<li class="is-active" role="tabpanel" id="uixGeneralOptions">
						<div class="block-body">
							' . $__compilerTemp1 . '
						</div>
					</li>
					<li role="tabpanel" id="uixSocialMedia">
						<div class="block-body">
							' . $__compilerTemp2 . '
						</div>
					</li>
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