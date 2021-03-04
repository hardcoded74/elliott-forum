<?php
// FROM HASH: 0108107e5c34b1f984ecb7518ab7942f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Upgrade complete');
	$__finalCompiled .= '

';
	$__templater->wrapTemplate('account_wrapper', $__vars);
	$__finalCompiled .= '

<div class="blockMessage">' . 'Your account has been upgraded.' . '</div>';
	return $__finalCompiled;
}
);