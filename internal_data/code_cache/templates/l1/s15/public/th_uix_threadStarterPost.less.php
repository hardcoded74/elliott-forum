<?php
// FROM HASH: 73870c94600b0bc1e02542c8a23cbc11
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.message-threadStarterPost {
	.xf-th_uix_threadStarterMessageBlock();

	.message-cell
	{
		&.message-cell--user,
		&.message-cell--action
		{
			position: relative;
			.xf-th_uix_threadStarterMessageUserBlock;
			min-width: 0;
		}
	}

	.message-newIndicator
	{
		.xf-th_uix_threadStarterMessageNewIndicator();
	}

	.message-signature
	{
		.xf-th_uix_threadStarterMessageSignature();
	}
}';
	return $__finalCompiled;
}
);