<?php
// FROM HASH: 06b468937f19914b4b9b69403abb408c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// This should be used for additional LESS setup code (that does not output anything).
// setup.less customizations should be avoided when possible.

// ######################## UI.X Variables ######################

// ANIMATIONS

// use for incoming elements, or growing elements
@uix_moveIn: cubic-bezier(0.0, 0.0, 0.2, 1);
// use for exiting elements, or shrinking elements
@uix_moveOut: cubic-bezier(0.4, 0.0, 1, 1);
// use for growing or moving elements that dont exit/enter the page
@uix_move: cubic-bezier(0.4, 0.0, 0.2, 1);

// UI.X browser query variables

@isIe: ~"(-ms-high-contrast: none), (-ms-high-contrast: active)";

@uix_sidebarNavBreakpoint: ' . ($__templater->func('property', array('pageWidthMax', ), false) + (2 * ($__templater->func('property', array('uix_sidebarNavWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)))) . 'px;

';
	$__vars['uix_pageEdgeSpacer'] = $__templater->preEscaped(($__templater->func('property', array('pageEdgeSpacer', ), false) * 2) . 'px');
	$__finalCompiled .= '

@uix_navigationPaddingV: 8px;

// UI.X MIXINS

.m-uix_whiteText (@background-color, @color: #fff) when (luma(@background-color) <= 43%) {
	color: @color;
}

.m-uix_collapseOverflow() {
	clip-path: inset(-2px -2px -2px -2px);
	
	@media @isIe {
		overflow: hidden;
	}
}

.m-uix_removePageSpacer() {
	@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
		margin-left: -@xf-pageEdgeSpacer * .5;
		margin-right: -@xf-pageEdgeSpacer * .5;
		border-radius: 0;
		border-left: none;
		border-right: none;
	}
}

.m-pageSpacerPadding(@defaultPadding: @xf-pageEdgeSpacer) {
	
	padding-left: @defaultPadding;
	padding-right: @defaultPadding;

	// iPhone X/Xr/Xs support
	/*
	@supports(padding: max(0px))
	{
		&
		{
			padding-left: ~"max(@{defaultPadding}, env(safe-area-inset-left))";
			padding-right: ~"max(@{defaultPadding}, env(safe-area-inset-right))";
		}
	}
	*/
	
	@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
		@defaultPadding: @xf-pageEdgeSpacer / 2;
			
		padding-left: @defaultPadding;
		padding-right: @defaultPadding;

		// iPhone X/Xr/Xs support
		@supports(padding: max(0px))
		{
			&
			{
				padding-left: ~"max(@{defaultPadding}, env(safe-area-inset-left))";
				padding-right: ~"max(@{defaultPadding}, env(safe-area-inset-right))";
			}
		}		
	}
	
	/*
	padding-left: @xf-pageEdgeSpacer;
	padding-right: @xf-pageEdgeSpacer;

	@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
		padding-left: ' . ($__templater->func('property', array('pageEdgeSpacer', ), false) / 2) . 'px;
		padding-right: ' . ($__templater->func('property', array('pageEdgeSpacer', ), false) / 2) . 'px;	
		padding-left: ~"max(10px, env(safe-area-inset-left)) !important";
		padding-right: ~"max(10px, env(safe-area-inset-right)) !important";
	}
	*/
}

.m-pageSpacer() {
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'wrapped') {
		$__finalCompiled .= '
		width: calc(~"100% - ' . $__templater->escape($__vars['uix_pageEdgeSpacer']) . '");
	';
	}
	$__finalCompiled .= '

	@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
		';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered') {
		$__finalCompiled .= '
			width: calc(~"100% - @xf-pageEdgeSpacer");
		';
	} else {
		$__finalCompiled .= '
			width: 100%;
		';
	}
	$__finalCompiled .= '
	}
}

.m-pageWidth()
{
	max-width: @xf-pageWidthMax;
	margin-left: auto;
	margin-right: auto;
	width: 100%;
	.m-pageSpacer();
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'covered') {
		$__finalCompiled .= '
		.m-pageSpacerPadding();
	';
	}
	$__finalCompiled .= '
	transition: max-width 0.2s;
	
	@media (max-width: @xf-responsiveWide) {
		';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered') {
		$__finalCompiled .= '
			padding-left: env(safe-area-inset-left) !important;
			padding-right: env(safe-area-inset-right) !important;
		';
	}
	$__finalCompiled .= '
	}

	.uix_page--fluid & {
		';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'wrapped') {
		$__finalCompiled .= '
		@media (min-width: @xf-pageWidthMax) {
			max-width: 100%;
		}
		';
	} else {
		$__finalCompiled .= '
			max-width: 100%;
		';
	}
	$__finalCompiled .= '
	}

	';
	if (($__templater->func('property', array('uix_navigationType', ), false) == 'sidebarNav') AND ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered')) {
		$__finalCompiled .= '
	@media (max-width: @uix_sidebarNavBreakpoint)  {
		.uix_page--fixed & {max-width: 100%;}
		#uix_widthToggle--trigger {display: none;}
	}
	';
	}
	$__finalCompiled .= '
}

.m-pageInset(@defaultPadding: @xf-pageEdgeSpacer)
{
	// here to satisfy global scope only
}

// Sticky sidebar/sidenav

';
	if (($__templater->func('property', array('uix_searchPosition', ), false) == 'tablinks') AND (($__templater->func('property', array('uix_loginTriggerPosition ', ), false) == 'tablinks') AND ($__templater->func('property', array('uix_userTabsPosition ', ), false) == 'tablinks'))) {
		$__finalCompiled .= '
	';
		$__vars['uix_sectionLinkHeight'] = $__templater->preEscaped($__templater->func('property', array('uix_sectionLinkHeight', ), true));
		$__finalCompiled .= '
';
	} else if ($__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false) == '100%') {
		$__finalCompiled .= '
	';
		$__vars['uix_sectionLinkHeight'] = $__templater->preEscaped('0px');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['uix_sectionLinkHeight'] = $__templater->preEscaped($__templater->func('property', array('uix_sectionLinkHeight', ), true));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('publicNavSticky', ), false) == 'none') {
		$__finalCompiled .= '
	@uix_navHeight: ' . $__templater->func('property', array('elementSpacer', ), true) . ';
';
	} else if ($__templater->func('property', array('publicNavSticky', ), false) == 'primary') {
		$__finalCompiled .= '
	@uix_navHeight: ' . ($__templater->func('property', array('elementSpacer', ), false) + $__templater->func('property', array('uix_stickyNavHeight', ), false)) . ';
';
	} else if ($__templater->func('property', array('publicNavSticky', ), false) == 'all') {
		$__finalCompiled .= '
	@uix_navHeight: ' . ($__templater->func('property', array('elementSpacer', ), false) + $__templater->func('property', array('uix_stickyNavHeight', ), false)) . 'px + ' . $__templater->escape($__vars['uix_sectionLinkHeight']) . ';
';
	}
	return $__finalCompiled;
}
);