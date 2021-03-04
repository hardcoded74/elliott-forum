<?php
// FROM HASH: c9f4cab44eaef1c7333b66f5c1612338
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Share upgrade link');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['profiles'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			';
		if ($__templater->isTraversable($__vars['upgrade']['payment_profile_ids'])) {
			foreach ($__vars['upgrade']['payment_profile_ids'] AS $__vars['profileId']) {
				$__finalCompiled .= '
				<h3 class="block-header">
					' . $__templater->escape($__vars['profiles'][$__vars['profileId']]['title']) . '
				</h3>
				';
				$__compilerTemp1 = '';
				$__compilerTemp1 .= '
							' . $__templater->callMacro('public:share_page_macros', 'buttons', array(
					'iconic' => true,
					'hideLink' => true,
					'pageUrl' => $__templater->method($__vars['upgrade'], 'getThMonetizeUpgradeCanonicalLink', array($__vars['profileId'], )),
					'pageTitle' => $__vars['upgrade']['title'],
					'pageDesc' => $__vars['upgrade']['description'],
				), $__vars) . '
						';
				if (strlen(trim($__compilerTemp1)) > 0) {
					$__finalCompiled .= '
					<div class="block-body block-row block-row--separated block-row--separated--mergePrev">
						' . $__compilerTemp1 . '
					</div>
				';
				}
				$__finalCompiled .= '
				<div class="block-body block-row block-row--separated">
					' . $__templater->callMacro('public:share_page_macros', 'share_clipboard_input', array(
					'label' => '',
					'text' => $__templater->method($__vars['upgrade'], 'getThMonetizeUpgradeCanonicalLink', array($__vars['profileId'], )),
					'successText' => 'Link copied to clipboard.',
				), $__vars) . '
				</div>
			';
			}
		}
		$__finalCompiled .= '
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);