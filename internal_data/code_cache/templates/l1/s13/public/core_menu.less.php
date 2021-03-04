<?php
// FROM HASH: 30dc5d59aeaca6e47bb36c15705a9e07
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ###################################### MENUS ######################

@_menu-arrowSize: 8px;
@_menu-edgePadding: @xf-pageEdgeSpacer;
@_menu-closePaddingH: @xf-paddingLarge;
@_menu-paddingH: 8px;
@_menu-paddingV: 8px;

.menuTrigger
{
	cursor: pointer;

	&:after
	{
		.m-menuGadget(true);
	}
}

.menuOutsideClicker
{
	position: fixed;
	display: none;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 2; // above non set stuff but below anything intentionally set

	&.is-active
	{
		display: block;
		-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
	}
}

.menu
{
	.m-transitionFade();
	
	@media (max-width: @xf-responsiveNarrow) {
		width: 90% !important;
		max-width: 90% !important;
		left: 0;
		right: 0;
		margin-left: auto;
		margin-right: auto;
	}

	position: absolute;
	z-index: @zIndex-2;
	margin: @_menu-arrowSize 0 0;
	min-width: 240px;
	max-width: 320px;
	border-radius: @xf-menuBorderRadius;
	// .m-dropShadow(0, 5px, 10px, 0, .35);
	box-shadow: @xf-uix_elevation2;
	.xf-contentBase();
	
	.block-container {
		box-shadow: none;
		// border-radius: 0;
		margin: 0;
		border: none;
		padding: 0;
	}

	&.menu--structural
	{
		margin-top: 0;
		
		// when menus nudge up against structure, the joined corner should not be radiused
		&.menu--left
		{
			// border-top-left-radius: 0;
		}
		&.menu--right
		{
			// border-top-right-radius: 0;
		}
	}

	&.menu--superWide
	{
		width: 75%;
		max-width: ~"calc(100% - @{xf-pageEdgeSpacer})";
	}

	&.menu--veryWide
	{
		width: 500px;
		max-width: ~"calc(100% - @{xf-pageEdgeSpacer})";
	}

	&.menu--wide
	{
		width: 350px;
		max-width: ~"calc(100% - @{xf-pageEdgeSpacer})";
	}

	&.menu--medium
	{
		width: 300px;
		max-width: ~"calc(100% - @{xf-pageEdgeSpacer})";
	}

	&.menu--potentialFixed
	{
		z-index: @zIndex-4;
	}
	
	.menu-minorHeader--small {
		padding: @_menu-paddingV @_menu-paddingH;
		font-size: calc(@xf-menu--font-size + 1);
	}
}

.menu-arrow
{
	position: absolute;
	top: 0;
	width: 0;
	height: 0;
	border: 0 solid transparent;
	display: none;

	top: -@_menu-arrowSize;
	-ltr-rtl-left: 50%;
	-ltr-rtl-margin-left: -@_menu-arrowSize;
	.m-triangleUp(@xf-menuFeatureBorderColor, @_menu-arrowSize);

	.menu--structural &
	{
		top: -@_menu-arrowSize;
	}

	.menu--up &
	{
		display: none;
	}
}

.menu-content
{
	margin: 0;
	padding: 0;
	list-style: none;
	text-align: left;
	.xf-menu();
	border-radius: @xf-menuBorderRadius;
	// border-top: @xf-borderSizeFeature solid @xf-menuFeatureBorderColor;

	// when menus nudge up against structure, the joined corner should not be radiused
	.menu--structural.menu--left &
	{
		// border-top-left-radius: 0;
	}
	.menu--structural.menu--right &
	{
		// border-top-right-radius: 0;
	}

	// potentially fixed menus
	.menu--potentialFixed &
	{
		overflow: auto;
		max-height: 450px;
		max-height: 80vh;
	}
	> :last-child
	{
		border-bottom-left-radius: @xf-menuBorderRadius;
		border-bottom-right-radius: @xf-menuBorderRadius;
	}
}

.menu--pageJump
{
	width: auto;
	min-width: 0;
}

.menu-header
{
	padding: @_menu-paddingH;
	margin: 0;
	font-weight: @xf-fontWeightNormal;
	text-decoration: none;
	.xf-menuHeader();

	.m-clearFix();
	.m-hiddenLinks();
}

.menu-tabHeader
{
	padding: 0;
	margin: 0;
	font-weight: @xf-fontWeightNormal;
	text-decoration: none;
	.xf-menuTabHeader();
	.m-tabsTogether(xf-default(@xf-menuTabHeader--font-size, @xf-fontSizeNormal));

	.tabs-tab
	{
		padding: @xf-blockPaddingV @_menu-paddingH max(0px, @xf-blockPaddingV - @xf-borderSizeFeature);
		border-bottom: @xf-borderSizeFeature solid transparent;

		&:hover
		{
			color: @xf-blockMinorTabHeaderSelected--color;
		}

		&.is-active
		{
			.xf-menuTabHeaderSelected();
		}
	}

	.hScroller-action
	{
		.m-hScrollerActionColorVariation(
			xf-default(@xf-menuTabHeader--background-color, transparent),
			xf-default(@xf-menuTabHeader--color, ~""),
			xf-default(@xf-blockMinorTabHeaderSelected--color, ~"")
		);
	}
}

.menu-scroller
{
	overflow: auto;
	max-height: 300px;
	max-height: 60vh;
	-webkit-overflow-scrolling: touch;

	.menu-row + &
	{
		border-top: @xf-borderSize solid @xf-borderColorLight;
	}
}

.menu-row
{
	margin: 0;
	padding: @_menu-paddingH;
	.m-clearFix();
	
	// causing issues when menu-row is not the first item
	// &:not(:first-of-type):not(.menu-row--separated) {padding-top: 0;}

	&.menu-row--alt
	{
		.xf-contentAltBase();
	}

	&.menu-row--highlighted
	{
		.xf-contentHighlightBase();
	}

	&.menu-row--close
	{
		padding-left: @_menu-closePaddingH;
		padding-right: @_menu-closePaddingH;
	}

	&.menu-row--separated
	{
		+ .menu-row
		{
			border-top: @xf-borderSize solid @xf-borderColorLight;
		}
	}

	&.menu-row--clickable:hover
	{
		background: @xf-contentHighlightBg;
	}

	&:empty
	{
		padding: 0;
	}
}

.menu-linkRow
{
	display: block;
	padding: @_menu-paddingV @_menu-paddingH;
	// text-indent: @_menu-paddingH;
	border-left: @xf-borderSizeFeature solid transparent;
	.xf-menuLinkRow();
	
	&.menu-linkRow--alt
	{
		.xf-contentAltBase();
	}

	&.is-selected,
	&:hover,
	&:focus
	{
		.xf-menuLinkRowSelected();

		// border-left-color: fade(@xf-borderColorFeature, 50%);

		&:focus
		{
			outline: 0;
		}

		&.is-selected
		{
			border-left-color: @xf-borderColorFeature;
		}
	}

	i[aria-hidden=true]
	{
		font-size: @xf-fontSizeSmall;
		display: inline-block;
		width: 23px; // about 1.75em at this font size

		&:after
		{
			.m-faBase();
			color: @xf-textColorMuted;
			left: @xf-blockPaddingH;
		}

		& ~ .menu-linkRow-hint
		{
			padding-left: 23px;
		}
	}

	&:hover i[aria-hidden=true]:after
	{
		color: @xf-linkHoverColor;
	}
}

.menu-linkRow-hint
{
	font-style: inherit;
	font-size: @xf-fontSizeSmallest;
	color: @xf-textColorMuted;
	display: block;
}

.menu-separator
{
	margin: 0 (@_menu-paddingH) / 2;
	padding: 0;
	border: none;
	border-top: @xf-borderSize solid @xf-borderColorLight;

	&.menu-separator--hard
	{
		margin: 0;
	}

	& + .menu-separator,
	&:last-child
	{
		display: none;
	}
}

.menu-footer
{
	padding: @_menu-paddingV @_menu-paddingH;
	.xf-menuFooter();
	.m-clearFix();

	&.menu-footer--close
	{
		padding-left: @_menu-closePaddingH;
		padding-right: @_menu-closePaddingH;
	}

	&:not(.menu-footer--split)
	{
		.menu-footer-counter
		{
			float: left;
		}

		.menu-footer-controls
		{
			float: right;
			
			.button {.xf-uix_buttonSmall();}
		}
	}

	&.menu-footer--split
	{
		display: flex;
		align-items: center;

		.menu-footer-main,
		.menu-footer-counter
		{
			flex-grow: 1;
		}

		.menu-footer-select:not(:last-child)
		{
			margin: 0 1em;
		}

		.menu-footer-opposite,
		.menu-footer-controls
		{
			margin-left: auto;
		}
	}
}';
	return $__finalCompiled;
}
);