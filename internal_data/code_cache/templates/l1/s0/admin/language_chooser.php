<?php
// FROM HASH: 05fea3c5d45ad13e11bb769d97399f36
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Language chooser');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			<a href="' . $__templater->func('link', array('account/language', null, array('language_id' => 0, '_xfRedirect' => $__vars['redirect'], 't' => $__templater->func('csrf_token', array(), false), ), ), true) . '" class="menu-linkRow">' . 'Use forum language preference' . '</a>

			<hr class="block-separator" />

			<ul class="listPlain listColumns">
				';
	$__compilerTemp1 = $__templater->method($__vars['languageTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['treeEntry']) {
			$__finalCompiled .= '
					<li>
						<a href="' . $__templater->func('link', array('account/language', null, array('language_id' => $__vars['treeEntry']['record']['language_id'], '_xfRedirect' => $__vars['redirect'], 't' => $__templater->func('csrf_token', array(), false), ), ), true) . '" class="menu-linkRow" dir="auto">' . $__templater->escape($__vars['treeEntry']['record']['title']) . '</a>
					</li>
				';
		}
	}
	$__finalCompiled .= '
			</ul>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);