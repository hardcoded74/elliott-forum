<?php
// FROM HASH: bf8e209c5a4cd23839b70193f90067e1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['extras']['th_best_answers_qaforums']) {
		$__finalCompiled .= '
	<dl class="pairs pairs--justified">
		';
		if (!$__templater->func('property', array('uix_postBitIcons', ), false)) {
			$__finalCompiled .= '
			<dt>' . 'th_best_answers_qaForums' . '</dt>
		';
		} else {
			$__finalCompiled .= '
			<dt data-xf-init="tooltip" title="' . 'th_best_answers_qaForums' . '">
				' . $__templater->fontAwesome('fa-comment-exclamation', array(
				'class' => 'mdi mdi-message-alert',
			)) . '
			</dt>			
		';
		}
		$__finalCompiled .= '
		<dd>' . $__templater->filter($__vars['user']['th_best_answers_qaforum'], array(array('number', array()),), true) . '</dd>
	</dl>
';
	}
	return $__finalCompiled;
}
);