<?php
// FROM HASH: 89982f013f10faaf79e21cf1d59801cc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.porta-features
{
	.xf-blockBorder();
	border-radius: @xf-blockBorderRadius;
	position: relative;

	.porta-features-item
	{
		background-position: center center;
		background-repeat: no-repeat;
		background-size: auto 100%;

		>a
		{
			display: block;
			position: relative;
			z-index: 90;
		}

		.porta-features-summary
		{
			position: absolute; bottom: 0; left: 0; right: 0;
			background-color: rgba(0,0,0,0.5);
			padding: 15px 20px 20px;
			color: white;

			>div { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
			.title { font-size: 1.5em; font-weight: bold; }
		}

		.porta-features-media
		{
			position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 80;
			margin: @xf-EWRporta_feature_media_margin auto;
			width: @xf-EWRporta_feature_media_width;
			height: (@xf-EWRporta_feature_height - (@xf-EWRporta_feature_media_margin * 2));
		}
	}

	.has-media .porta-features-summary { display: none; }
	.has-media .bx-controls-volume { display: initial; }
}

.porta-features-fix
{
	height: @xf-EWRporta_feature_height;
	overflow: hidden;
}

@media (max-width: @xf-responsiveWide)
{
	.porta-features-fix { height: @xf-EWRporta_feature_wide; }
	.porta-features .porta-features-item .porta-features-media
	{
		margin: (@xf-EWRporta_feature_media_margin / 2) auto;
		width: @xf-EWRporta_feature_media_wide;
		height: (@xf-EWRporta_feature_wide - @xf-EWRporta_feature_media_margin);
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.porta-features-fix { height: @xf-EWRporta_feature_medium; }
	.porta-features .porta-features-item .porta-features-media
	{
		margin: 0 auto;
		width: @xf-EWRporta_feature_media_medium;
		height: @xf-EWRporta_feature_medium;
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.porta-features-fix { height: @xf-EWRporta_feature_narrow; }
	.porta-features .porta-features-item .porta-features-media
	{
		width: @xf-EWRporta_feature_media_narrow;
		height: @xf-EWRporta_feature_narrow;
	}
}

@media (max-width: @xf-responsiveEdgeSpacerRemoval)
{
	.porta-features
	{
		margin-left: -@xf-pageEdgeSpacer / 2;
		margin-right: -@xf-pageEdgeSpacer / 2;
	}
}

/*---------------------------------------*/
/*           BXSLIDER LAYOUT             */
/*---------------------------------------*/

/* ITEM CONTROLS */

.porta-features
{
	.bx-pager
	{
		position: absolute;
		top: 10px;
		text-align: center;
		width: 100%;
	}
	.bx-pager .bx-pager-item { position: relative; z-index: 100; display: inline-block; }
	.bx-pager.bx-default-pager a
	{
		background: rgba(128, 128, 128, 0.5);
		margin: 0 3px;
		border-radius: 8px;
		display: block;
		text-indent: -9999px;
		box-shadow: 0px 0px 2px #FFF inset;
		width: 16px;
		height: 16px;
	}
	.bx-pager.bx-default-pager a:hover
	{
		background-color: rgb(128, 128, 128);
	}
	.bx-pager.bx-default-pager a.active
	{
		background-color: rgb(0, 0, 0);
	}
}

/* PREV/NEXT CONTROLS */

.porta-features
{
	.bx-controls-direction
	{
	}

	.bx-controls-direction a
	{
		background-image: url(\'styles/8wayrun/porta/_slider.png\');
		background-repeat: no-repeat;
		margin-top: -16px;
		position: absolute;
		text-indent: -9999px;
		z-index: 100;
		width: 32px;
		height: 32px;
	}

	.bx-controls-direction .bx-prev
	{
		top: 50%;
		left: 10px;
	}

	.bx-controls-direction .bx-next
	{
		top: 50%;
		right: 10px;
	}

	.bx-controls-direction .bx-prev { background-position: 0 -32px; }
	.bx-controls-direction .bx-prev:hover { background-position: 0 0; }
	.bx-controls-direction .bx-next { background-position: -32px -32px; }
	.bx-controls-direction .bx-next:hover { background-position: -32px 0; }
	.bx-controls-direction .disabled { display: none; }
}

/* START/STOP CONTROLS */

.porta-features
{
	.bx-controls-auto
	{
		position: absolute;
		top: 10px;
		right: 10px;
		z-index: 100;
	}
	.bx-controls-auto a
	{
		background-image: url(\'styles/8wayrun/porta/_slider.png\');
		background-repeat: no-repeat;
		display: block;
		text-indent: -9999px;
		width: 32px;
		height: 32px;
	}

	.bx-controls-auto .active { display: none; }
	.bx-controls-auto .bx-start { background-position: -64px -32px; }
	.bx-controls-auto .bx-start:hover { background-position: -64px 0; }
	.bx-controls-auto .bx-stop { background-position: -96px -32px; }
	.bx-controls-auto .bx-stop:hover { background-position: -96px 0; }
}


/* VOLUME BUTTON */

.porta-features
{
	.bx-controls-volume
	{
		display: none;
		position: absolute;
		right: 10px;
		bottom: 10px;
		z-index: 100;
	}
	.bx-controls-volume a
	{
		background-image: url(\'styles/8wayrun/porta/_slider.png\');
		background-repeat: no-repeat;
		display: block;
		text-indent: -9999px;
		width: 64px;
		height: 64px;
	}

	.bx-controls-volume .active { display: none; }
	.bx-controls-volume .bx-unmute { background-position: -128px 0; }
	.bx-controls-volume .bx-unmute:hover { background-position: -192px 0; }
	.bx-controls-volume .bx-mute { background-position: -192px 0; }
	.bx-controls-volume .bx-mute:hover { background-position: -128px 0; }
}

/* PROGRESS BAR */

.porta-features
{
	.bx-progress
	{
		background-color: red;
		border-top: 1px solid black;
		position: absolute;
		bottom: 0;
		z-index: 100;
		width: 0;
		height: 5px;
	}
}';
	return $__finalCompiled;
}
);