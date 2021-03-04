<?php
// FROM HASH: 73da6074e68a6ef75a5822bff64aef51
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Alert log');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => 'Any',
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['logUsers']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body block-row">
			<span>
				' . 'Filter by user' . $__vars['xf']['language']['label_separator'] . '
				' . $__templater->formSelect(array(
		'name' => 'user_id',
		'value' => $__vars['userId'],
		'class' => 'input--inline',
	), $__compilerTemp1) . '
			</span>

			' . $__templater->button('Filter', array(
		'type' => 'submit',
	), '', array(
	)) . '
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('logs/monetize-alert', ), false),
		'class' => 'block',
	)) . '

';
	if (!$__templater->test($__vars['entries'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['entries'])) {
			foreach ($__vars['entries'] AS $__vars['entry']) {
				$__compilerTemp2 .= '
						' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => '
								<a href="' . $__templater->func('link', array('logs/monetize-alert', $__vars['entry'], ), true) . '" data-xf-click="overlay">
									' . $__templater->func('date_dynamic', array($__vars['entry']['log_date'], array(
				))) . '
								</a>
							',
				),
				array(
					'_type' => 'cell',
					'html' => $__templater->func('username_link', array($__vars['entry']['User'], false, array(
					'href' => $__templater->func('link', array('users/edit', $__vars['entry']['User'], ), false),
				))),
				),
				array(
					'_type' => 'cell',
					'html' => '
								<a href="' . $__templater->func('link', array('monetize-alerts', $__vars['entry']['Alert'], ), true) . '">' . $__templater->escape($__vars['entry']['Alert']['title']) . '</a>
							',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'_type' => 'cell',
			'html' => 'Date',
		),
		array(
			'_type' => 'cell',
			'html' => 'User',
		),
		array(
			'_type' => 'cell',
			'html' => 'Alert',
		))) . '
					' . $__compilerTemp2 . '
				', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['entries'], $__vars['total'], ), true) . '</span>
			</div>
		</div>
		' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'logs/monetize-alert',
			'params' => $__vars['linkFilters'],
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No entries have been logged.' . '</div>
';
	}
	return $__finalCompiled;
}
);