<?php
// FROM HASH: 2ef6391dc427f004d4d78d47b21e504c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@_stripThumbSize: 50px;
@_thumbSize: xf-option(\'xfmgThumbnailDimensions.width\', px);

.itemList
{
	display: flex;
	flex-flow: row wrap;
	margin: @xf-paddingSmall;

	&.itemList--strip
	{
		justify-content: center;
		min-height: @_stripThumbSize + (@xf-paddingSmall * 2);

		@media (max-width: @xf-responsiveNarrow)
		{
			min-height: @_stripThumbSize / 1.3 + (@xf-paddingSmall * 2);
		}

		@media (max-width: 360px)
		{
			min-height: @_stripThumbSize / 1.9 + (@xf-paddingSmall * 2);
		}
	}

	&.itemList--slider
	{
		display: block;
		margin: 0;

		.itemList-item--slider
		{
			margin: 0;
			display: none;

			.lightSlider--loaded &
			{
				display: block;
			}

			.itemList-itemTypeIcon
			{
				display: none;
			}
		}
	}
}

.itemList-item
{
	flex: auto;
	width: (@_thumbSize) / 1.6;
	max-width: @_thumbSize;
	margin: @xf-paddingSmall;

	position: relative;
	overflow: hidden;

	.itemList--strip &
	{
		justify-items: center;
		width: (@_stripThumbSize) / 1.6;
		max-width: @_stripThumbSize;
		margin-top: auto;
		margin-bottom: auto;

		opacity: 1;
		.m-transition(opacity);

		&.itemList-item--fading
		{
			opacity: 0 !important;
		}
	}

	@media (max-width: @xf-responsiveNarrow)
	{
		width: (@_thumbSize) / 2;

		.itemList--strip && // for extra specificity
		{
			max-width: (@_stripThumbSize) / 1.3;
		}
	}

	@media (max-width: 360px)
	{
		.itemList--strip &&
		{
			max-width: (@_stripThumbSize) / 1.9;
		}
	}

	&.itemList-item--placeholder
	{
		margin-top: 0;
		margin-bottom: 0;
		height: 0;

		.itemList--strip &
		{
			.xf-contentAltBase();
			.xf-blockBorder();

			margin-top: inherit;
			margin-bottom: inherit;
			height: initial;

			opacity: 0.7;
		}
	}
}

.itemList-button
{
	.xf-contentAltBase();
	.xf-blockBorder();

	width: 25px;
	border-radius: @xf-borderRadiusMedium;
	color: @xf-linkColor;

	display: block;
	position: relative;
	margin: @xf-paddingSmall;

	cursor: pointer;

	&.is-disabled
	{
		display: none;
	}

	&.is-loading
	{
		.xf-buttonDisabled();
		pointer-events: none;
		cursor: default;
	}
}

.itemList-button-icon
{
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);

	.m-faBase();
	font-size: 1.2em;

	.itemList-button--next &
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

	.itemList-button--prev &
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

.itemList-itemTypeIcon
{
	position: absolute;
	top: @xf-paddingMedium;
	right: @xf-paddingMedium;
	z-index: @zIndex-1;

	width: 20px;
	height: 20px;

	display: flex;
	align-items: center;
	justify-content: center;

	.m-textOutline(white, black, thin);
	color: #fff;
	opacity: 0.6;

	.has-touchevents &,
	.itemList-item:hover &,
	.itemList-item.is-mod-selected &
	{
		opacity: 1;
	}

	.m-faBase();

	&::after
	{
		font-size: @xf-fontSizeLargest;
	}
}

@_overlayHeight: 52px;

.itemList-itemOverlay
{
	width: 100%;
	height: @_overlayHeight;
	bottom: -@_overlayHeight;
	position: absolute;

	background-color: rgba(0, 0, 0, 0.6);
	.m-hiddenLinks();
	.m-transition();

	padding: 4px;
	overflow: hidden;

	.has-touchevents &,
	.itemList-item:hover &
	{
		bottom: 0;
	}

	.itemList-item.is-mod-selected &
	{
		bottom: 0;
		background: @xf-inlineModHighlightColor;
	}

	a:hover
	{
		text-decoration: none;
	}
}

.itemList-itemOverlayTop
{
	cursor: pointer;

	top: 3px;
	left: 3px;
	z-index: @zIndex-2 + 5; // above the state icon but below the filter menu

	width: 25px;
	height: 25px;
	border-radius: @xf-borderRadiusMedium;

	background: rgba(0, 0, 0, 0.4);

	display: flex;
	align-items: center;
	justify-content: center;

	opacity: 0;

	.has-touchevents &,
	.itemList-item:hover &
	{
		opacity: 1;
	}

	.itemList-item.is-mod-selected &
	{
		opacity: 1;
		background: @xf-inlineModHighlightColor;

		&.iconic
		{
			> input
			{
				+ i:before
				{
					color: @xf-textColorDimmed;
				}

				&:hover
				{
					+ i:before
					{
						color: @xf-textColorDimmed;
					}
				}
			}
		}
	}

	&&.iconic
	{
		display: flex;
		position: absolute;

		> input
		{
			+ i
			{
				&:before
				{
					color: #fff;
				}

				position: absolute;
				top: 2px;
				left: 6px;
			}

			&:hover
			{
				+ i:before
				{
					color: xf-intensify(#fff, 15%);
				}
			}
		}
	}
}

.itemInfoRow
{
	display: flex;
	align-items: center;
}

.itemInfoRow-avatar
{
	white-space: nowrap;
	word-wrap: normal;
	text-align: center;
}

.itemInfoRow-main
{
	flex: 1;
	min-width: 0;
	vertical-align: top;

	text-shadow: 0 0 2px rgba(0, 0, 0, 0.6);
	color: #fff;

	.itemList-item.is-mod-selected &
	{
		text-shadow: none;
		color: @xf-textColorDimmed;
	}

	&:before
	{
		// because of line height, there appears to be extra space at the top of this
		content: \'\';
		display: block;
		margin-top: -.18em;
	}
}

.itemInfoRow-title
{
	margin: 0;
	padding: 0 0 0 @xf-paddingSmall;
	font-size: @xf-fontSizeSmall;
	font-weight: @xf-fontWeightNormal;
	.m-overflowEllipsis();
}

.itemInfoRow-status
{
	margin: 0;
	padding: 0 0 0 @xf-paddingSmall;
	font-size: @xf-fontSizeSmallest;
	overflow: hidden;
	white-space: nowrap;
}';
	return $__finalCompiled;
}
);