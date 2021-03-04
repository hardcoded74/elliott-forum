<?php
// FROM HASH: 1c3c9568f038d117754056375ac10012
return array(
'macros' => array('sponsor' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'sponsor' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                    ';
	if ($__vars['sponsor']['title']) {
		$__compilerTemp1 .= '
                        <h2 class="block-header"><a href="' . $__templater->escape($__vars['sponsor']['url']) . '">' . $__templater->escape($__vars['sponsor']['title']) . '</a></h2>
                    ';
	}
	$__compilerTemp1 .= '
                    ';
	if ($__vars['sponsor']['image']) {
		$__compilerTemp1 .= '
                        <div class="block-body">
                            <div class="block-row">
                                <a href="' . $__templater->escape($__vars['sponsor']['url']) . '"><img src="' . $__templater->escape($__vars['sponsor']['image']) . '" width="' . $__templater->escape($__vars['sponsor']['width']) . '" height="' . $__templater->escape($__vars['sponsor']['height']) . '"></a>
                            </div>
                        </div>
                    ';
	}
	$__compilerTemp1 .= '
                ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
        ';
		$__templater->includeCss('thmonetize_sponsor.less');
		$__finalCompiled .= '
        <div class="thmonetize_Sponsor">
            <div class="block-container">
                ' . $__compilerTemp1 . '
            </div>
        </div>
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'sponsors_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'sponsors' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('thmonetize_sponsor.less');
	$__finalCompiled .= '
	<div class="thmonetize_SponsorsList">
		';
	if ($__templater->isTraversable($__vars['sponsors'])) {
		foreach ($__vars['sponsors'] AS $__vars['sponsor']) {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'sponsor', array(
				'sponsor' => $__vars['sponsor'],
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Sponsors');
	$__finalCompiled .= '

' . $__templater->callMacro(null, 'sponsors_list', array(
		'sponsors' => $__vars['sponsors'],
	), $__vars) . '

' . '

';
	return $__finalCompiled;
}
);