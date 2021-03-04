<?php
// FROM HASH: 0b1b279f944739efbacd49b16fc6003c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Upgrade required');
	$__finalCompiled .= '

';
	$__templater->includeCss('thmonetize_upgrade_page.less');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'robots', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= $__templater->escape($__vars['error']);
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	<div class="blockMessage blockMessage--error blockMessage--iconic">
		' . $__compilerTemp1 . '
	</div>
';
	}
	$__finalCompiled .= '

<div class="blocks">
	';
	if ($__vars['upgradePage']) {
		$__finalCompiled .= '
		' . $__templater->callMacro('thmonetize_upgrade_page_macros', 'upgrade_options', array(
			'upgradePage' => $__vars['upgradePage'],
			'upgrades' => $__vars['upgrades'],
			'profiles' => $__vars['profiles'],
		), $__vars) . '
	';
	} else if ($__vars['xf']['visitor']['user_id'] OR $__vars['xf']['options']['thmonetize_allowGuestsToViewUserUpgrades']) {
		$__finalCompiled .= '
		<div class="thmonetize_UpgradeButtons">
			<div class="block-container">
				<div class="block-footer">
					' . $__templater->button('Show all available upgrades', array(
			'href' => $__templater->func('link', array('account/upgrades', ), false),
			'class' => 'button--cta',
		), '', array(
		)) . '
				</div>
			</div>
		</div>
	';
	} else {
		$__finalCompiled .= '
		<div class="thmonetize_UpgradeButtons">
			<div class="block-container">
				<div class="block-footer">
					' . $__templater->button('Show all available upgrades', array(
			'href' => $__templater->func('link', array('register', ), false),
			'class' => 'button--cta',
			'xf-click' => 'overlay',
		), '', array(
		)) . '
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
	';
	if (!$__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after">
			<div class="block-outer-middle">
				' . 'Already have an account?' . ' ' . $__templater->button('', array(
			'href' => $__templater->func('link', array('login', ), false),
			'icon' => 'login',
		), '', array(
		)) . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['providers'], 'empty', array())) {
		$__finalCompiled .= '
		<div class="blocks-textJoiner"><span></span><em>' . 'or' . '</em><span></span></div>

		<div class="block">
			<div class="block-container">
				<div class="block-body">
					';
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['providers'])) {
			foreach ($__vars['providers'] AS $__vars['provider']) {
				$__compilerTemp2 .= '
								<li>
									' . $__templater->callMacro('connected_account_macros', 'button', array(
					'provider' => $__vars['provider'],
				), $__vars) . '
								</li>
							';
			}
		}
		$__finalCompiled .= $__templater->formRow('

						<ul class="listHeap">
							' . $__compilerTemp2 . '
						</ul>
					', array(
			'rowtype' => 'button',
			'label' => 'Log in using',
		)) . '
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);