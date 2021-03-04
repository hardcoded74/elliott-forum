<?php
// FROM HASH: 7a6969ec515bdfb8e817ee7b5548d66a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@_memberHeader-padding: @xf-paddingLarge;
@_memberHeader-avatarSize: @avatar-l;
@_memberHeader-avatarSizeShrunk: @avatar-m;

.memberHeader
{
	.m-clearFix();
}

.memberHeader-main
{
	display: flex;
	flex-direction: column;
	justify-content: flex-end;
	.xf-memberHeader();

	.memberHeader--withBanner &
	{
		min-height: 250px;
	}
}

.memberHeader-mainContent
{
	position: relative;
}

.memberHeader-avatar
{
	position: absolute;
	padding: @_memberHeader-padding;
}

.memberHeader-content
{
	padding: @_memberHeader-padding;
	padding-left: ((@_memberHeader-padding) * 2 + (@_memberHeader-avatarSize));
}

.memberHeader-actionTop
{
	float: right;
}

.memberHeader-name
{
	margin: 0;
	margin-top: -.15em;
	padding: 0;
	font-weight: @xf-fontWeightNormal;
	.xf-memberHeaderName();

	.memberHeader--withBanner &
	{
		.xf-memberHeaderNameBanner();
	}
}

.memberHeader-nameChangeIndicator
{
	color: @xf-textColorMuted;
	font-size: 75%;

	&:hover
	{
		color: @xf-textColorMuted;
	}

	.memberHeader--withBanner &
	{
		color: darken(xf-default(@xf-memberHeaderNameBanner--color, white), 20%);

		&:hover
		{
			color: darken(xf-default(@xf-memberHeaderNameBanner--color, white), 20%);
		}
	}
}

// Emulate outer text stroke by stacking a stroked element with the original text.
// This is more complex but gives a better result
@_member-textStroke: 2px #000;

.memberHeader--withBanner
{
	.is-stroked
	{
		position: relative;

		&:before
		{
			content: attr(data-stroke);
			position: absolute;
			white-space: nowrap;
			color: #000;
			-webkit-text-stroke: @_member-textStroke;
		}

		span
		{
			position: relative;
		}
	}

	.memberHeader-nameChangeIndicator .fa-history
	{
		position: relative;

		&:before
		{
			position: relative;
			z-index: 1;
		}

		&:after
		{
			position: absolute;
			left: 0;
			white-space: nowrap;
			content: @fa-var-history;
			-webkit-text-stroke: @_member-textStroke;
		}
	}
}

.memberHeader-blurbContainer
{
	.memberHeader--withBanner &
	{
		.xf-memberHeaderBlurbContainerBanner();

		.memberHeader-blurb
		{
			&:first-child
			{
				margin-top: 0;
			}

			.pairs dt, a
			{
				color: darken(xf-default(@xf-memberHeaderBlurbContainerBanner--color, white), 20%);
			}
		}
	}
}

.memberHeader-banners,
.memberHeader-blurb
{
	margin-top: @xf-paddingSmall;
}

.memberHeader-separator
{
	margin: @_memberHeader-padding 0;
	border: none;
	border-top: @xf-borderSize solid @xf-borderColorLight;
}

.memberHeader-stats
{
	font-size: @xf-fontSizeLarge;

	.pairs.pairs--row
	{
		min-width: 100px;
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.memberHeader-avatar .avatar
	{
		.m-avatarSize(@_memberHeader-avatarSizeShrunk);
	}

	.memberHeader-content
	{
		padding-left: ((@_memberHeader-padding) * 2 + (@_memberHeader-avatarSizeShrunk));
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.memberHeader-avatar
	{
		display: block;
		position: static;
		float: none;
		padding-bottom: 0;
		text-align: center;
	}

	.memberHeader-avatar .avatarWrapper-update
	{
		font-size: @xf-fontSizeSmall;
	}

	.memberHeader-content
	{
		padding-left: @_memberHeader-padding;
	}

	.memberHeader-main .memberHeader-content
	{
		display: flex;
		flex-direction: column;
		padding-top: 0;
		min-height: 0;
		text-align: center;
	}

	.memberHeader-name
	{
		text-align: center;
		margin-top: 0;
	}

	.memberHeader-actionTop
	{
		float: none;
		order: 2;
		margin-top: @xf-paddingSmall;
	}

	.memberHeader-buttons
	{
		text-align: center;
	}

	.memberHeader-banners,
	.memberHeader-blurb
	{
		text-align: inherit;
	}
}

.block-tabHeader.block-tabHeader--memberTabs
{
	border-bottom: none;
}



.memberOverviewBlocks
{
	.m-listPlain();

	display: flex;
	flex-wrap: wrap;
	align-items: stretch;

	> li
	{
		width: 33.3%;
		max-width: 100%;
		padding: @xf-blockPaddingV @xf-blockPaddingH;

		@media (max-width: 1150px)
		{
			width: 50%;
		}

		@media (max-width: 580px)
		{
			width: 100%;
		}
	}
}

.memberOverviewBlock
{
	display: flex;
	flex-direction: column;
}
.memberOverviewBlock-list
{
	.m-listPlain();

	> li
	{
		margin: @xf-paddingMedium 0;
	}
}
.memberOverviewBlock-seeMore
{
	.xf-minorBlockContent();
	padding: @xf-paddingSmall;
	text-align: center;

	// pushes this to the bottom with flex box
	margin-top: auto;
}';
	return $__finalCompiled;
}
);