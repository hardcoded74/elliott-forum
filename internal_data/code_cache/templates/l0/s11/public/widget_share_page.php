<?php
// FROM HASH: 8e07de071da4f5f55bedd883ec91491b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	if ($__templater->func('property', array('uix_shareWidgetModal', ), false)) {
		$__compilerTemp1 .= '
						';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
													' . $__templater->callMacro('share_page_macros', 'buttons', array(
			'iconic' => $__vars['options']['iconic'],
		), $__vars) . '
												';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__compilerTemp1 .= '
							<a data-xf-click="overlay" data-target=".uix_shareWidgetModal" class="button button--primary">' . $__templater->fontAwesome('fa-share', array(
				'class' => 'mdi mdi-share',
			)) . ' Share this page</a>
							<div style="display: none">
								<div class="uix_shareWidgetModal">
									<div class="overlay-title">' . 'Share' . '</div>
									<div class="block-container">
										<div class="block-body">
											<div class="block-row">
												' . $__compilerTemp2 . '									
											</div>
										</div>
									</div>						
								</div>
							</div>
						';
		}
		$__compilerTemp1 .= '
					';
	} else {
		$__compilerTemp1 .= '
							' . $__templater->callMacro('share_page_macros', 'buttons', array(
			'iconic' => $__vars['options']['iconic'],
		), $__vars) . '
					';
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
			<div class="block-body block-row" style="text-align: center;">
				' . $__compilerTemp1 . '
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);