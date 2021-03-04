<?php
// FROM HASH: 58d0285162cfe55db906284f690d1663
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@_notice-darkBg: @xf-paletteColor4;
@_notice-lightBg: #fff;
@_notice-floatingFade: 100%;
@_notice-imageSize: 48px;
@_notice-padding: @xf-paddingLarge;

.notices
{
	.m-listPlain();

	&.notices--block
	{
		.notice
		{
			margin-bottom: @xf-elementSpacer;
		}
	}

	&.notices--floating
	{
		// assumed to be within u-bottomFixer
		margin: 0 auto 0 @xf-elementSpacer;
		width: 300px;
		max-width: 100%;
		z-index: @zIndex-8;

		@media (max-width: 340px)
		{
			margin-right: 10px;
		}

		.notice
		{
			margin-bottom: 20px;
		}
	}
	
	.uix_noticeInner {
		display: flex;
	}
	
	.uix_noticeIcon {
		display: flex;
		align-items: center;
		padding: 0 8px;
		color: rgba(255,255,255,.5);
		font-size: 24px;
	}

	&.notices--scrolling
	{
		display: flex;
		align-items: stretch;
		overflow: hidden;
		.xf-blockBorder();
		margin-bottom: ((@xf-elementSpacer) / 2);

		&.notices--isMulti
		{
			margin-bottom: ((@xf-elementSpacer) / 2) + 20px;
		}

		.notice
		{
			width: 100%;
			flex-grow: 0;
			flex-shrink: 0;
			border: none;
			box-shadow: none;
		}
	}
}

.noticeScrollContainer
{
	margin-bottom: ((@xf-elementSpacer) / 2);
	
	box-shadow: @xf-uix_elevation1;
	';
	if ($__templater->func('property', array('uix_similarScrollNotice', ), false)) {
		$__finalCompiled .= '
		border: 2px solid @xf-uix_primaryColor;
	';
	}
	$__finalCompiled .= '
	
	';
	if ($__templater->func('property', array('uix_similarScrollNotice', ), false)) {
		$__finalCompiled .= '
		.uix_noticeIcon {background: @xf-uix_primaryColor;}
	';
	}
	$__finalCompiled .= '

	.lSSlideWrapper
	{
		.xf-blockBorder();
	}

	.notices.notices--scrolling
	{
		border: none;
		margin-bottom: 0;
	}
	
	';
	if ($__templater->func('property', array('uix_similarScrollNotice', ), false)) {
		$__finalCompiled .= '
	.notice {
		&.notice--primary,
		&.notice--accent,
		&.notice--dark,
		&.notice--light {.xf-contentBase();}
		
		a {color: @xf-linkColor;}
	}
	';
	}
	$__finalCompiled .= '
	
	.lSPager {.xf-contentBase();}
}

.notice
{
	.m-clearFix();
	position: relative;

	.xf-blockBorder();
	.xf-contentBase();
	border: 2px solid @xf-borderColor;

	&.notice--primary
	{
		.xf-contentBase();
		border: 2px solid #5d6063;
		
		.uix_noticeIcon {background: #5d6063;}
	}

	&.notice--accent
	{
		border: 2px solid @xf-uix_secondaryColor;
		
		.uix_noticeIcon {background: @xf-uix_secondaryColor;}

		a:not(.button--notice)
		{
			.xf-contentAccentLink();
		}
	}

	&.notice--dark
	{
		//background: @_notice-darkBg;
		background: @xf-uix_primaryColor;
		color: #fff;
		border-color: lighten(@xf-uix_primaryColor, 30%);

		a:not(.button--notice)
		{
			color: @xf-linkColor;
		}
		
		a.notice-dismiss {color: inherit;}
	}

	&.notice--light
	{
		color: rgb(20, 20, 20);
		background: @_notice-lightBg;
		
		.uix_noticeIcon {background: @xf-borderColor; color: @xf-textColorMuted;}

		a:not(.button--notice)
		{
			color: rgb(130, 130, 130);
		}
	}

	&.notice--enablePush
	{
		display: none;

		@media (max-width: @xf-responsiveWide)
		{
			padding: @xf-paddingSmall @xf-paddingSmall @xf-paddingLarge;
			font-size: @xf-fontSizeSmall;
		}
	}

	&.notice--cookie
	{
		@media (max-width: @xf-responsiveWide)
		{
			.notice-content
			{
				padding: @xf-paddingSmall @xf-paddingSmall;
				font-size: @xf-fontSizeSmaller;

				.button--notice
				{
					font-size: @xf-fontSizeSmaller;
					padding: @xf-paddingSmall @xf-paddingMedium;

					.button-text
					{
						font-size: @xf-fontSizeSmaller;
					}
				}
			}
		}
	}

	.notices--block &
	{
		font-size: @xf-fontSizeNormal;
		border-radius: @xf-blockBorderRadius;
	}

	.notices--floating &
	{
		font-size: @xf-fontSizeSmallest;
		border-radius: @xf-borderRadiusMedium;
		box-shadow: @xf-uix_elevation1;

		' . '

		.has-js &
		{
			display: none;
		}
	}

	&.notice--hasImage
	{
		.notice-content
		{
			min-height: ((@_notice-imageSize) + (@_notice-padding) * 2);
		}
	}

	// note: visibility hidden is used by the JS to detect when responsiveness is hiding a notice

	@media (max-width: @xf-responsiveWide)
	{
		&.notice--hidewide:not(.is-vis-processed)
		{
			display: none;
			visibility: hidden;
		}
	}
	@media (max-width: @xf-responsiveMedium)
	{
		&.notice--hidemedium:not(.is-vis-processed)
		{
			display: none;
			visibility: hidden;
		}
	}
	@media (max-width: @xf-responsiveNarrow)
	{
		&.notice--hidenarrow:not(.is-vis-processed)
		{
			display: none;
			visibility: hidden;
		}
	}
}

.notice-image
{
	float: left;
	padding: @_notice-padding 0 @_notice-padding @_notice-padding;

	img
	{
		max-width: @_notice-imageSize;
		max-height: @_notice-imageSize;
	}
}

.notice-content
{
	padding: @_notice-padding;
	flex-grow: 1;
	// color: @xf-textColorDimmed;

	a.notice-dismiss
	{
		&:before
		{
			.m-faBase();

			.m-faContent(@fa-var-times, .69em);
		}

		float: right;

		color: inherit;
		font-size: 16px;
		line-height: 1;
		height: 1em;
		box-sizing: content-box;
		padding: 0 0 5px 5px;

		opacity: .5;
		.m-transition(opacity);

		cursor: pointer;

		&:hover
		{
			text-decoration: none;
			opacity: 1;
		}

		.notices--floating &
		{
			font-size: 14px;
		}
	}
}';
	return $__finalCompiled;
}
);