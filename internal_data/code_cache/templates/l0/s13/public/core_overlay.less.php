<?php
// FROM HASH: 42a23781712d0d96b7c5608b2168a68b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// #################################### OVERLAYS ################################

.overlay-container
{
	display: none;
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	z-index: @zIndex-9;
	background: @xf-overlayMaskColor;
	-webkit-overflow-scrolling: touch;
	opacity: 0;
	.m-transition(opacity);
	
	@media (max-width: @xf-responsiveNarrow) {
		transition: transform ease-in .25s;
		transform: translatey(100%);
		display: block;
		opacity: 1;
		
		.overlay-title {
			position: sticky;
			top: 0;
		}
	}

	&.is-transitioning
	{
		display: block;
	}

	&.is-active
	{
		display: block;
		opacity: 1;
		@media (max-width: @xf-responsiveNarrow) {
			transform: translatey(0);
		}
	}
}

.overlay
{
	position: relative;
	margin: 40px auto 10px;
	margin-top: @xf-overlayTopMargin;
	width: 100%;
	max-width: 800px;
	background: @xf-contentBg;
	color: @xf-textColor;
	.xf-blockBorder();
	border-radius: @xf-blockBorderRadius;
	.m-dropShadow(0, 5px, 15px, 0, .5);
	outline: none;

	> .overlay-title:first-child,
	.overlay-firstChild
	{
		border-top-left-radius: @xf-blockBorderRadius;
		border-top-right-radius: @xf-blockBorderRadius;
	}

	> .overlay-content > *:last-child,
	.overlay-lastChild
	{
		border-bottom-left-radius: @xf-blockBorderRadius;
		border-bottom-right-radius: @xf-blockBorderRadius;
	}
	
	.block-container {box-shadow: none;}
}

@media (max-width: @xf-responsiveWide)
{
	.overlay
	{
		max-width: ~"calc(100% - 20px)";
		max-width: ~"calc(100% - env(safe-area-inset-left) - env(safe-area-inset-right))";
	}
}

@media (max-width: @xf-responsiveNarrow) {
	.overlay {
		max-width: 100%;
		margin: 0;
		height: 100vh;
		border-radius: 0;
		overflow-y: scroll;
		
		.overlay-title {z-index: 1;}
		
		.formSubmitRow--sticky {
			position: sticky;
			bottom: 0;
		}
	}
}

.overlay-title
{
	.m-clearFix();

	display: none;
	margin: 0;
	font-weight: @xf-fontWeightNormal;
	.xf-overlayHeader();

	.overlay &
	{
		display: block;
	}
}

.overlay-titleCloser
{
	float: right;
	cursor: pointer;
	margin-left: 5px;
	text-decoration: none;
	// opacity: .5;
	.m-transition();

	&:after
	{
		.m-faBase();
		.m-faContent(@fa-var-times);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'close',
	), $__vars) . '
	}

	&:hover
	{
		text-decoration: none;
		opacity: 1;
	}
}

.overlay-content
{
	.m-clearFix();
}

// when displaying a modal, prevent scrolling on the main content but allow it on the overlay
body.is-modalOpen
{
	overflow: hidden !important;

	.overlay-container,
	.offCanvasMenu
	{
		overflow-y: scroll !important;
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.overlay-title
	{
		font-size: @xf-fontSizeLarger;
	}
}

// ############################### OVERLAY/BLOCK NESTING ##############################

.block-container > .tabPanes > li,
.block-container > .block-body,
.block-container > .block-body > .block-row,
.overlay-content
{
	> .blocks > .block > .block-container,
	> .block > .block-container,
	> .blockMessage
	{
		margin-left: 0;
		margin-right: 0;
		border-radius: @xf-blockBorderRadius; // restore the radius that might be removed for some viewports as we\'re within the overlay
		border-left: none;
		border-right: none;
	}

	> .blocks > .block:first-child,
	> .block:first-child,
	> .blockMessage:first-child
	{
		margin-top: 0;

		.block-outer:not(.block-outer--after)
		{
			border-bottom: @xf-borderSize solid @xf-borderColorLight;
			padding: @xf-blockPaddingV;
		}
	}

	> .blocks > .block:last-child,
	> .block:last-child,
	> .blockMessage:last-child
	{
		margin-bottom: 0;

		.block-outer.block-outer--after
		{
			border-top: @xf-borderSize solid @xf-borderColorLight;
			padding: @xf-blockPaddingV;
		}
	}

	> .blocks > .block:first-child > .block-container,
	> .block:first-child > .block-container,
	> .blockMessage:first-child
	{
		border-top: none;
	}

	> .blocks > .block:last-child > .block-container,
	> .block:last-child > .block-container,
	> .blockMessage:last-child
	{
		border-bottom: none;
	}

	> .block:not(:first-child) > .block-container,
	> .blockMessage:not(:first-child)
	{
		.m-borderTopRadius(0);
	}

	> .blocks > .block:not(:last-child) > .block-container,
	> .block:not(:last-child) > .block-container,
	> .blockMessage:not(:last-child)
	{
		.m-borderBottomRadius(0);
	}
}

.overlay {
	transition: cubic-bezier(0.4, 0, 0.2, 1) 0.26s top;
	top: 20px;
	position: relative;

	.is-active & {
		top: 0px;
	}
}

.overlay-content
{
	> .blocks > .block > .block-container,
	> .block > .block-container,
	> .blockMessage
	{
		.m-borderTopRadius(0);
	}

	> .blocks > .block > .block-container,
	> .block > .block-container
	{
		> :first-child
		{
			.m-borderTopRadius(0);
		}

		> .block-body:first-child > .formRow:first-child
		{
			> dt { border-top-left-radius: 0; }
			> dd { border-top-right-radius: 0; }
		}

		> .dataList:first-child
		{
			tbody:first-child .dataList-row:first-child,
			thead:first-child .dataList-row:first-child
			{
				> .dataList-cell:first-child { border-top-left-radius: 0; }
				> .dataList-cell:last-child { border-top-right-radius: 0; }
			}
		}
	}

	> .block:last-child > .block-container > .formSubmitRow:not(.is-sticky):last-child .formSubmitRow-bar
	{
		border-bottom-left-radius: @block-borderRadius-inner;
		border-bottom-right-radius: @block-borderRadius-inner;
	}
}';
	return $__finalCompiled;
}
);