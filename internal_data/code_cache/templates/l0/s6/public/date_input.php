<?php
// FROM HASH: 6f28e884258960dd9ab80d1a83b32ccf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeJs(array(
		'prod' => 'xf/date_input-compiled.js',
		'dev' => 'vendor/pikaday/pikaday.js, xf/date_input.js',
	));
	$__finalCompiled .= '

<div class="inputGroup inputGroup--date inputGroup--joined inputDate">
	<input type="text" class="input input--date ' . $__templater->escape($__vars['class']) . '" autocomplete="off" data-xf-init="date-input ' . $__templater->escape($__vars['xfInit']) . '"
		data-week-start="' . $__templater->escape($__vars['weekStart']) . '"
		' . ($__vars['readOnly'] ? 'readonly' : '') . '
		' . $__templater->filter($__vars['attrsHtml'], array(array('raw', array()),), true) . ' />
	<span class="inputGroup-text inputDate-icon js-dateTrigger"></span>
</div>';
	return $__finalCompiled;
}
);