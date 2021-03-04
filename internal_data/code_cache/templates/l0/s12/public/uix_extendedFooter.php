<?php
// FROM HASH: 639126f7af1261535836f2b3f6c4eb6a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				' . $__templater->filter($__vars['uix_footerWidgets'], array(array('raw', array()),), true) . '
			';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
<div class="uix_extendedFooter">
	<div class="pageContent">
		<div class="uix_extendedFooterRow">
			';
		$__templater->includeCss('uix_extendedFooter.less');
		$__finalCompiled .= '
			' . $__compilerTemp1 . '
		</div>
	</div>
</div>
';
	}
	return $__finalCompiled;
}
);