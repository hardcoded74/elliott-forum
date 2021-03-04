<?php
// FROM HASH: 48ff288e4540c6da41cac4632943d1f9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@_nav-elTransitionSpeed: @xf-animationSpeed;
@_navAccount-hPadding: @xf-paddingLarge;
@uix_sidebarNavBreakpoint: ' . (($__templater->func('property', array('pageWidthMax', ), false) + 1) + (2 * ($__templater->func('property', array('uix_sidebarNavWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)))) . 'px;

.m-pageWidth(@min-width: @xf-pageEdgeSpacer)
{
	// max-width: @xf-pageWidthMax;
	// padding: 0 @xf-pageEdgeSpacer;
	// margin: 0 auto;
}

.m-pageInset(@defaultPadding: @xf-pageEdgeSpacer)
{
	/*
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
	*/
}

.u-anchorTarget
{
	.m-stickyHeaderConfig(@xf-publicNavSticky);
	height: (@_stickyHeader-height + @_stickyHeader-offset);
	margin-top: -(@_stickyHeader-height + @_stickyHeader-offset);
}

.uix_pageWidth--wrapped {
	width: 100%;
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'wrapped') {
		$__finalCompiled .= '
		.m-pageWidth();
	';
	}
	$__finalCompiled .= '
}

.p-pageWrapper
{
	position: relative;
	display: flex;
	flex-direction: column;
	min-height: 100vh;
	// .xf-pageBackground();

	.is-modalOverlayOpen &
	{
		& when (unit(xf-default(@xf-overlayMaskBlur, 0)) > 0)
		{
			filter: blur(@xf-overlayMaskBlur);
		}
	}
	flex-grow: 1;
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'wrapped') {
		$__finalCompiled .= '
		.xf-uix_pageWrapper();
		@media (min-width: ' . ($__templater->func('property', array('responsiveEdgeSpacerRemoval', ), false) + 1) . 'px) {
			margin-top: @xf-pageEdgeSpacer;
			margin-bottom: @xf-pageEdgeSpacer;
			padding: @xf-pageEdgeSpacer;
		}
	
		@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
			border: none;
			box-shadow: none;
		}
	';
	}
	$__finalCompiled .= '
}

.uix_headerContainer {
	.xf-uix_headerWrapper();
}

// RESPONSIVE HEADER

.p-offCanvasAccountLink
{
	display: none;
	
	.avatar {margin-right: 24px;}
}

@media (max-width: @xf-responsiveNarrow)
{
	.p-offCanvasAccountLink
	{
		display: block;
	}
}

@media (max-width: 359px)
{
	.p-offCanvasRegisterLink
	{
		display: block;
	}
}

@media (max-width: @xf-pageWidthMax) {
	#uix_widthToggle--trigger {display: none;}
}

#uix_widthToggle--trigger:hover {cursor: pointer;}

.uix_page--fixed #uix_widthToggle--trigger .uix_icon:before {
	.m-faBase();
	.m-faContent(@fa-var-expand-alt);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'expand',
	), $__vars) . '
}

' . $__templater->includeTemplate('app_staffbar.less', $__vars) . '
';
	if ($__templater->func('property', array('uix_viewportShowLogoBlock', ), false) != '100%') {
		$__finalCompiled .= '
	' . $__templater->includeTemplate('app_header.less', $__vars) . '
';
	}
	$__finalCompiled .= '
' . $__templater->includeTemplate('app_stickynav.less', $__vars) . '
' . $__templater->includeTemplate('app_nav.less', $__vars) . '
' . $__templater->includeTemplate('app_sectionlinks.less', $__vars) . '
' . $__templater->includeTemplate('app_body.less', $__vars) . '
' . $__templater->includeTemplate('app_breadcrumbs.less', $__vars) . '
' . $__templater->includeTemplate('app_title.less', $__vars) . '
' . $__templater->includeTemplate('app_footer.less', $__vars) . '
' . $__templater->includeTemplate('app_inlinemod.less', $__vars) . '
' . $__templater->includeTemplate('app_ignored.less', $__vars) . '
' . $__templater->includeTemplate('app_username_styles.less', $__vars) . '
' . $__templater->includeTemplate('app_user_banners.less', $__vars) . '
' . $__templater->includeTemplate('uix.less', $__vars);
	return $__finalCompiled;
}
);