<?php
// FROM HASH: b18ac4bb3054188b6684552f03fad4d9
return array(
'macros' => array('upgrade_option' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'upgrade' => '!',
		'profiles' => '!',
		'featured' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('thmonetize_upgrade_page.less');
	$__finalCompiled .= '
	';
	$__templater->includeCss('thmonetize_user_upgrade_cache.less');
	$__finalCompiled .= '
	<div class="thmonetize_UpgradeOption ' . ($__vars['featured'] ? 'thmonetize_UpgradeOption--featured' : '') . '">
		<div class="block-container thmonetize_upgrade thmonetize_upgrade--' . $__templater->escape($__vars['upgrade']['user_upgrade_id']) . ($__vars['upgrade']['thmonetize_style_properties']['color'] ? ' thmonetize_upgrade--hasColor' : '') . '">
			<h2 class="block-header">' . $__templater->escape($__vars['upgrade']['title']) . '</h2>
			<div class="block-header thmonetize_upgradeHeader' . ($__vars['upgrade']['thmonetize_style_properties']['shape'] ? (' thmonetize_upgradeHeader--shape thmonetize_upgradeHeader--' . $__templater->escape($__vars['upgrade']['thmonetize_style_properties']['shape'])) : '') . '">
				<div class="thmonetize_upgradeHeader__price">
					<div class="thmonetize_upgradeHeader__priceRow">
						';
	if (($__vars['xf']['language']['currency_format'] === '{symbol}{value}') OR ($__vars['xf']['language']['currency_format'] === '{symbol} {value}')) {
		$__finalCompiled .= '
							<span class="thmonetize_upgrade__currency">' . $__templater->escape($__vars['upgrade']['thmonetize_cost_currency_symbol']) . '</span>
						';
	}
	$__finalCompiled .= '
						<span class="thmonetize_upgrade__price">' . $__templater->escape($__vars['upgrade']['thmonetize_cost_amount_formatted']) . '</span>
						';
	if (!($__vars['xf']['language']['currency_format'] === '{symbol}{value}') OR ($__vars['xf']['language']['currency_format'] === '{symbol} {value}')) {
		$__finalCompiled .= '
							<span class="thmonetize_upgrade__currency">' . $__templater->escape($__vars['upgrade']['thmonetize_cost_currency_symbol']) . '</span>
						';
	}
	$__finalCompiled .= '
						';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= $__templater->escape($__vars['upgrade']['thmonetize_length_phrase_short']);
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
							<span class="thmonetize_upgrade__occurrence">/' . $__compilerTemp1 . '</span>
						';
	}
	$__finalCompiled .= '
					</div>
					<span class="thmonetize_upgrade__length">' . $__templater->escape($__vars['upgrade']['thmonetize_length_phrase_long']) . '</span>
				</div>
			</div>
			<div class="block-body">
				';
	if ($__vars['upgrade']['description']) {
		$__finalCompiled .= '
					<div class="block-row">
						<span>' . $__templater->filter($__vars['upgrade']['description'], array(array('raw', array()),), true) . '</span>
					</div>
				';
	}
	$__finalCompiled .= '
				' . $__templater->callMacro(null, 'features', array(
		'features' => $__vars['upgrade']['thmonetize_features'],
		'styleProperties' => $__vars['upgrade']['thmonetize_style_properties'],
	), $__vars) . '
			</div>
			<div class="block-footer">
				';
	if (!$__templater->method($__vars['upgrade'], 'canPurchase', array())) {
		$__finalCompiled .= '
					' . $__templater->button('Already purchased', array(
			'icon' => 'purchase',
			'class' => 'is-disabled',
		), '', array(
		)) . '
				';
	} else {
		$__finalCompiled .= '
					';
		$__compilerTemp2 = '';
		if (($__templater->func('count', array($__vars['upgrade']['payment_profile_ids'], ), false) > 1)) {
			$__compilerTemp2 .= '
							';
			$__compilerTemp3 = array(array(
				'label' => $__vars['xf']['language']['parenthesis_open'] . 'Choose a payment method' . $__vars['xf']['language']['parenthesis_close'],
				'_type' => 'option',
			));
			if ($__templater->isTraversable($__vars['upgrade']['payment_profile_ids'])) {
				foreach ($__vars['upgrade']['payment_profile_ids'] AS $__vars['profileId']) {
					$__compilerTemp3[] = array(
						'value' => $__vars['profileId'],
						'label' => $__templater->escape($__vars['profiles'][$__vars['profileId']]),
						'_type' => 'option',
					);
				}
			}
			$__compilerTemp2 .= $__templater->formSelect(array(
				'name' => 'payment_profile_id',
			), $__compilerTemp3) . '

							<span class="inputGroup-splitter"></span>

							' . $__templater->button('', array(
				'type' => 'submit',
				'icon' => 'purchase',
				'class' => 'button',
			), '', array(
			)) . '
							';
		} else {
			$__compilerTemp2 .= '
							' . $__templater->button('', array(
				'type' => 'submit',
				'icon' => 'purchase',
				'class' => 'button',
			), '', array(
			)) . '

							' . $__templater->formHiddenVal('payment_profile_id', $__templater->filter($__vars['upgrade']['payment_profile_ids'], array(array('first', array()),), false), array(
			)) . '
						';
		}
		$__finalCompiled .= $__templater->form('
						' . $__compilerTemp2 . '
					', array(
			'action' => $__templater->func('link', array('purchase', $__vars['upgrade'], array('user_upgrade_id' => $__vars['upgrade']['user_upgrade_id'], ), ), false),
			'ajax' => 'true',
			'data-xf-init' => 'payment-provider-container',
		)) . '
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'upgrade_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'upgradePage' => '!',
		'upgrades' => '!',
		'profiles' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('thmonetize_upgrade_page.less');
	$__finalCompiled .= '
	<div class="block thmonetize_UpgradeOptionsList">
		';
	if ($__templater->isTraversable($__vars['upgrades'])) {
		foreach ($__vars['upgrades'] AS $__vars['userUpgrade']) {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'upgrade_option', array(
				'upgrade' => $__vars['userUpgrade'],
				'profiles' => $__vars['profiles'],
				'featured' => $__vars['upgradePage']['Relations'][$__vars['userUpgrade']['user_upgrade_id']]['featured'],
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '
	</div>

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				';
	if ($__templater->isTraversable($__vars['upgradePage']['UpgradePageLinks'])) {
		foreach ($__vars['upgradePage']['UpgradePageLinks'] AS $__vars['upgradePageLink']) {
			$__compilerTemp1 .= '
					<a href="' . $__templater->func('link', array('account/upgrades', null, array('upgrade_page_id' => $__vars['upgradePageLink']['upgrade_page_id'], ), ), true) . '">' . $__templater->escape($__vars['upgradePageLink']['title']) . '</a>
				';
		}
	}
	$__compilerTemp1 .= '
				';
	if ($__vars['upgradePage']['accounts_page_link']) {
		$__compilerTemp1 .= '
					<span class="thmonetize_UpgradePageLinks-more">
						<a href="' . $__templater->func('link', array('account/upgrades', null, array('show_all' => 1, ), ), true) . '">' . 'More' . $__vars['xf']['language']['ellipsis'] . '</a>
					</span>
				';
	}
	$__compilerTemp1 .= '
			';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="thmonetize_UpgradePageLinks">
			' . $__compilerTemp1 . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'features' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'features' => '!',
		'styleProperties' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['features'])) {
		foreach ($__vars['features'] AS $__vars['feature']) {
			$__finalCompiled .= '
		<div class="block-row">
			';
			if ($__vars['styleProperties']['icon']) {
				$__finalCompiled .= '
					<i class="fa ' . $__templater->escape($__vars['styleProperties']['icon']) . ' fa-' . $__templater->escape($__vars['styleProperties']['icon']) . '"></i>
			';
			} else if ($__vars['styleProperties']['shape']) {
				$__finalCompiled .= '
				<i class="fa fa-' . $__templater->escape($__vars['styleProperties']['shape']) . '"></i>
			';
			} else {
				$__finalCompiled .= '
				<i class="fa fa-check-circle"></i>
			';
			}
			$__finalCompiled .= '
			<span>' . $__templater->escape($__vars['feature']) . '</span>
		</div>
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);