<?php
// FROM HASH: b9e3ac4e9df88571367e444a44a695a1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Account upgrades');
	$__finalCompiled .= '

<div class="block">
	';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['upgrade']['thmonetize_custom_amount']) {
		$__compilerTemp2 .= '
							';
		$__compilerTemp3 = '';
		if (($__vars['xf']['language']['currency_format'] === '{symbol}{value}') OR ($__vars['xf']['language']['currency_format'] === '{symbol} {value}')) {
			$__compilerTemp3 .= '
										<span class="inputGroup-text">' . $__templater->escape($__templater->method($__vars['upgrade'], 'getThMonetizeCostCurrencySymbol', array(false, ))) . '</span>
									';
		}
		$__compilerTemp4 = '';
		if (!($__vars['xf']['language']['currency_format'] === '{symbol}{value}') OR ($__vars['xf']['language']['currency_format'] === '{symbol} {value}')) {
			$__compilerTemp4 .= '
										<span class="inputGroup-text">' . $__templater->escape($__templater->method($__vars['upgrade'], 'getThMonetizeCostCurrencySymbol', array(false, ))) . '</span>
									';
		}
		$__compilerTemp2 .= $__templater->formRow('
								<div class="inputGroup">
									' . $__compilerTemp3 . '
									' . $__templater->formTextBox(array(
			'name' => 'thmonetize_cost_amount',
			'value' => $__vars['upgrade']['cost_amount'],
			'style' => 'width: 120px',
		)) . '
									' . $__compilerTemp4 . '
									<span class="inputGroup-text">' . $__templater->escape($__templater->method($__vars['upgrade'], 'getThMonetizeLengthPhraseLong', array(false, ))) . '</span>
								</div>
							', array(
			'rowtype' => 'input',
			'label' => 'Custom amount',
			'explain' => 'You may enter any amount for this upgrade.',
		)) . '
						';
	}
	$__compilerTemp2 .= '
					';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
				<div class="block-body">
					' . $__compilerTemp2 . '
				</div>
			';
	}
	$__finalCompiled .= $__templater->form('

		<div class="block-container">
			<h2 class="block-header">' . $__templater->escape($__vars['upgrade']['title']) . '</h2>

			' . $__compilerTemp1 . '
			
			' . $__templater->formHiddenVal('payment_profile_id', $__vars['paymentProfile']['payment_profile_id'], array(
	)) . '
			
			' . $__templater->formSubmitRow(array(
		'icon' => 'purchase',
		'sticky' => 'true',
	), array(
	)) . '
		</div>
	', array(
		'action' => $__templater->func('link', array('purchase', $__vars['upgrade'], array('user_upgrade_id' => $__vars['upgrade']['user_upgrade_id'], ), ), false),
		'ajax' => 'true',
		'data-xf-init' => 'payment-provider-container',
	)) . '
</div>';
	return $__finalCompiled;
}
);