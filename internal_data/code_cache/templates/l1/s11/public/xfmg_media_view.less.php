<?php
// FROM HASH: 1e316201ac301355c5532c80627aa2fe
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.media
{
	position: relative;
	margin-bottom: 10px;
}


@_buttonWidth: 30px;
@_buttonHeight: 50px;

.media-button
{
	position: absolute;
	top: ~"calc(50% - "(@_buttonHeight / 2) + 2~")";
	z-index: @zIndex-1;

	width: @_buttonWidth;
	height: @_buttonHeight;

	background: fade(mix(@xf-paletteNeutral2, @xf-paletteNeutral3), 70%);
	border-radius: @xf-borderRadiusMedium;

	opacity: 0.2;
	.m-transition(opacity);

	cursor: pointer;

	.has-touchevents &,
	.media:hover &
	{
		opacity: 0.6;
	}

	&&:hover
	{
		text-decoration: none;
		opacity: 1;
	}

	.media-button-icon
	{
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);

		color: #FFF;
		.m-textOutline(white, black);

		.m-faBase();
		font-size: 1.75em;
	}

	&.media-button--next
	{
		right: 5px;

		.media-button-icon
		{
			&:before
			{
				.m-faContent(@fa-var-chevron-right, .71em, ltr);
				.m-faContent(@fa-var-chevron-left, .71em, rtl);
				' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-right',
	), $__vars) . '
			}
		}
	}

	&.media-button--prev
	{
		left: 5px;

		.media-button-icon
		{
			&:before
			{
				.m-faContent(@fa-var-chevron-left, .71em, ltr);
				.m-faContent(@fa-var-chevron-right, .71em, rtl);
				' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-left',
	), $__vars) . '
			}
		}
	}
}

.media-container
{
	display: flex;
	justify-content: center;
	align-items: center;

	border: 1px solid transparent;

	min-height: @_buttonHeight;

	img
	{
		max-width: 100%;
		max-height: 80vh;
	}
	
	.bbWrapper
	{
		width: 100%;
		text-align: center;
	}

	.bbMediaWrapper
	{
		width: 100%;
		text-align: center; // helps center some embeds

		.bbMediaWrapper-inner
		{
			&.bbMediaWrapper-inner--thumbnail
			{
				padding-bottom: xf-option(\'xfmgThumbnailDimensions.width\', px);

				.video-js
				{
					&.vjs-audio
					{
						margin: 0;
					}
				}
			}
		}
	}

	.video-js
	{
		&audio, &video
		{
			display: none;
		}

		&.vjs-audio
		{
			border: none;
			margin: 0 (@_buttonWidth * 2);
		}
	}

	.fb-video
	{
		iframe
		{
			background-color: @xf-contentBg;
		}
	}

	.fb_iframe_widget
	{
		margin-left: auto;
		margin-right: auto;
	}
}

@media (max-width: @xf-responsiveEdgeSpacerRemoval)
{
	.media-container
	{
		margin-left: -@xf-pageEdgeSpacer;
		margin-right: -@xf-pageEdgeSpacer;
	}
}

' . $__templater->includeTemplate('xfmg_media_note.less', $__vars) . '
' . $__templater->includeTemplate('xfmg_cropper.less', $__vars) . '
' . $__templater->includeTemplate('xfmg_item_view.less', $__vars);
	return $__finalCompiled;
}
);