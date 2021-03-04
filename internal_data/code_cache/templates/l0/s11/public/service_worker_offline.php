<?php
// FROM HASH: 57464de258bf760b3bb0594f1976e0db
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->setPageParam('template', 'OFFLINE_CONTAINER');
	$__finalCompiled .= '
';
	$__templater->setPageParam('css', $__templater->filter($__vars['css'], array(array('raw', array()),), true));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Offline');
	$__finalCompiled .= '

<div class="p-offline-main">
	' . 'You appear to have lost connection to the internet. Check your connection and then <a href="javascript:window.location.reload();">reload the page</a>.' . '
</div>

<script>
	window.addEventListener(\'online\', function()
	{
		window.location.reload();
	});
</script>';
	return $__finalCompiled;
}
);