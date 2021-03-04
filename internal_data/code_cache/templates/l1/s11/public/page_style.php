<?php
// FROM HASH: 0ebce89300c153af53d38527921ce7ce
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<style>
	.uix_headerContainer .p-navSticky.is-sticky {
		top: ' . $__templater->escape($__vars['uix_stickyStaffBarHeight']) . ' !important;
	}

	';
	if ($__vars['uix_responsiveStaffBar'] AND (!$__vars['uix_alwaysStaffBar'])) {
		$__finalCompiled .= '
		@media (max-width: ' . $__templater->func('property', array('uix_staffBarBreakpoint', ), true) . ') {
			.uix_headerContainer .p-navSticky.is-sticky {
				top: 0 !important;
			}
		}
	';
	}
	$__finalCompiled .= '

	';
	$__vars['uix_stickyTabsHeight'] = '0';
	$__finalCompiled .= '
	';
	if ($__vars['uix_hasMainTabs'] AND (($__templater->func('property', array('uix_tabBarLocation', ), false) == 'header') AND $__templater->func('property', array('uix_mainTabsSticky', ), false))) {
		$__finalCompiled .= '
	';
		$__vars['uix_stickyTabsHeight'] = $__templater->func('property', array('uix_mainTabsHeight', ), false);
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	$__vars['uix_stickyNavHeightTotal'] = '0';
	$__finalCompiled .= '
	';
	$__vars['uix_stickyNavPrimary'] = '0';
	$__finalCompiled .= '
	';
	if (!$__vars['uix_hideNavigation']) {
		$__finalCompiled .= '
		';
		if ($__templater->func('property', array('publicNavSticky', ), false) == 'primary') {
			$__finalCompiled .= '
			';
			$__vars['uix_stickyNavHeightTotal'] = $__templater->func('property', array('uix_stickyNavHeight', ), false);
			$__finalCompiled .= '
			';
			$__vars['uix_stickyNavPrimary'] = $__templater->func('property', array('uix_stickyNavHeight', ), false);
			$__finalCompiled .= '
		';
		} else if ($__templater->func('property', array('publicNavSticky', ), false) == 'all') {
			$__finalCompiled .= '
			';
			$__vars['uix_stickyNavPrimary'] = $__templater->func('property', array('uix_stickyNavHeight', ), false);
			$__finalCompiled .= '
			';
			if ($__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false) != '100%') {
				$__finalCompiled .= '
				';
				$__vars['uix_stickyNavHeightTotal'] = ($__templater->func('property', array('uix_stickyNavHeight', ), false) + $__templater->func('property', array('uix_stickySectionLinkHeight', ), false));
				$__finalCompiled .= '
			';
			} else {
				$__finalCompiled .= '
				';
				$__vars['uix_stickyNavHeightTotal'] = $__templater->func('property', array('uix_stickyNavHeight', ), false);
				$__finalCompiled .= '
			';
			}
			$__finalCompiled .= '
		';
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	$__vars['uix_subNavBreakpoint'] = $__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false);
	$__finalCompiled .= '

	';
	if (!$__vars['uix_subNavContentStatic']) {
		$__finalCompiled .= '
		';
		$__vars['uix_subNavBreakpoint'] = '$uix_sidebarBreakpoint';
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	if ((!$__vars['uix_subNavContentStatic']) OR $__vars['uix_subNavContentStatic']) {
		$__finalCompiled .= '	
		';
		$__vars['uix_stickyTotal'] = ((($__vars['uix_stickyStaffBarHeight'] + $__vars['uix_stickyNavHeightTotal']) + $__templater->func('property', array('elementSpacer', ), false)) + $__vars['uix_stickyTabsHeight']);
		$__finalCompiled .= '
		.uix_mainTabBar {top: ' . ($__vars['uix_stickyStaffBarHeight'] + $__vars['uix_stickyNavHeightTotal']) . 'px !important;}
		.uix_stickyBodyElement:not(.offCanvasMenu) {
			top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;
			min-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px) !important;
		}
		.uix_sidebarInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
		.uix_sidebarInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
		.p-body-sideNavInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
		.p-body-sideNavInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
		.uix_stickyCategoryStrips {top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;}
		#XF .u-anchorTarget {
			height: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
			margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
		}
	
		';
		if ($__vars['uix_responsiveStaffBar'] AND (!$__vars['uix_alwaysStaffBar'])) {
			$__finalCompiled .= '
			
			@media(max-width: ' . $__templater->func('property', array('uix_staffBarBreakpoint', ), true) . ') {
				';
			$__vars['uix_stickyTotal'] = (($__vars['uix_stickyNavHeightTotal'] + $__templater->func('property', array('elementSpacer', ), false)) + $__vars['uix_stickyTabsHeight']);
			$__finalCompiled .= '
				.uix_mainTabBar {top: ' . $__templater->escape($__vars['uix_stickyNavHeightTotal']) . 'px !important;}
				.uix_stickyBodyElement:not(.offCanvasMenu) {
					top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;
					min-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px) !important;
				}
				.uix_sidebarInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.uix_sidebarInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.p-body-sideNavInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.p-body-sideNavInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.uix_stickyCategoryStrips {top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;}
				#XF .u-anchorTarget {
					height: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
					margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
				}
			}
		';
		}
		$__finalCompiled .= '

		';
		if ($__vars['uix_subNavBreakpoint'] != '100%') {
			$__finalCompiled .= '
			@media (max-width: ' . $__templater->escape($__vars['uix_subNavBreakpoint']) . ') {
		';
		}
		$__finalCompiled .= '
				.p-sectionLinks {display: none;}

				';
		$__vars['uix_stickyTotal'] = ((($__vars['uix_stickyStaffBarHeight'] + $__vars['uix_stickyNavPrimary']) + $__templater->func('property', array('elementSpacer', ), false)) + $__vars['uix_stickyTabsHeight']);
		$__finalCompiled .= '

				.uix_mainTabBar {top: ' . ($__vars['uix_stickyStaffBarHeight'] + $__vars['uix_stickyNavPrimary']) . 'px !important;}
				.uix_stickyBodyElement:not(.offCanvasMenu) {
					top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;
					min-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px) !important;
				}
				.uix_sidebarInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.uix_sidebarInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.p-body-sideNavInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.p-body-sideNavInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.uix_stickyCategoryStrips {top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;}
				#XF .u-anchorTarget {
					height: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
					margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
				}

				';
		if ($__vars['uix_responsiveStaffBar'] AND (!$__vars['uix_alwaysStaffBar'])) {
			$__finalCompiled .= '
					@media(max-width: ' . $__templater->func('property', array('uix_staffBarBreakpoint', ), true) . ') {
						';
			$__vars['uix_stickyTotal'] = (($__vars['uix_stickyNavPrimary'] + $__templater->func('property', array('elementSpacer', ), false)) + $__vars['uix_stickyTabsHeight']);
			$__finalCompiled .= '

						.uix_mainTabBar {top: ' . $__templater->escape($__vars['uix_stickyNavPrimary']) . ' !important;}
						.uix_stickyBodyElement:not(.offCanvasMenu) {
							top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;
							min-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px) !important;
						}
						.uix_sidebarInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
						.uix_sidebarInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
						.p-body-sideNavInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
						.p-body-sideNavInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
						.uix_stickyCategoryStrips {top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;}
						#XF .u-anchorTarget {
							height: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
							margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
						}
					}
				';
		}
		$__finalCompiled .= '
		';
		if ($__vars['uix_subNavBreakpoint'] != '100%') {
			$__finalCompiled .= '
			}
		';
		}
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
		';
		$__vars['uix_stickyTotal'] = ((($__vars['uix_stickyStaffBarHeight'] + $__vars['uix_stickyNavPrimary']) + $__templater->func('property', array('elementSpacer', ), false)) + $__vars['uix_stickyTabsHeight']);
		$__finalCompiled .= '

		.uix_mainTabBar {top: ' . ($__vars['uix_stickyStaffBarHeight'] + $__vars['uix_stickyNavPrimary']) . 'px !important;}
		.uix_stickyBodyElement:not(.offCanvasMenu) {
			top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;
			min-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px) !important;
		}
		.uix_sidebarInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
		.uix_sidebarInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
		.p-body-sideNavInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
		.p-body-sideNavInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
		.uix_stickyCategoryStrips {top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;}
		#XF .u-anchorTarget {
			height: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
			margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
		}
	
		';
		if ($__vars['uix_responsiveStaffBar'] AND (!$__vars['uix_alwaysStaffBar'])) {
			$__finalCompiled .= '
			';
			if ($__templater->func('property', array('uix_staffBarBreakpoint', ), false) != '100%') {
				$__finalCompiled .= '
			@media(max-width: ' . $__templater->func('property', array('uix_staffBarBreakpoint', ), true) . ') {
			';
			}
			$__finalCompiled .= '
				';
			$__vars['uix_stickyTotal'] = (($__vars['uix_stickyNavHeightTotal'] + $__templater->func('property', array('elementSpacer', ), false)) + $__vars['uix_stickyTabsHeight']);
			$__finalCompiled .= '
				.uix_mainTabBar {top: ' . $__templater->escape($__vars['uix_stickyNavHeightTotal']) . ' !important;}
				.uix_stickyBodyElement:not(.offCanvasMenu) {
					top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;
					min-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px) !important;
				}
				.uix_sidebarInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.uix_sidebarInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.p-body-sideNavInner .uix_sidebar--scroller {margin-top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.p-body-sideNavInner {margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;}
				.uix_stickyCategoryStrips {top: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px !important;}
				#XF .u-anchorTarget {
					height: ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
					margin-top: -' . $__templater->escape($__vars['uix_stickyTotal']) . 'px;
				}
			';
			if ($__templater->func('property', array('uix_staffBarBreakpoint', ), false) != '100%') {
				$__finalCompiled .= '
			}
			';
			}
			$__finalCompiled .= '
		';
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	.uix_sidebarNav .uix_sidebar--scroller {max-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px);}
	';
	if ($__templater->func('property', array('uix_scrollableSidebar', ), false)) {
		$__finalCompiled .= '
		.uix_sidebarInner .uix_sidebar--scroller {max-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px);}
	';
	}
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('uix_scrollableSidenav', ), false)) {
		$__finalCompiled .= '
		.p-body-sideNavInner .uix_sidebar--scroller {max-height: calc(100vh - ' . $__templater->escape($__vars['uix_stickyTotal']) . 'px);}
	';
	}
	$__finalCompiled .= '
</style>

';
	if ($__templater->func('property', array('uix_stickySidebar', ), false) == 'sticky') {
		$__finalCompiled .= '
';
		$__compilerTemp1 = '';
		if ($__templater->func('property', array('uix_stickySidebarCalcDelay', ), false) != '0') {
			$__compilerTemp1 .= '
		$(document).ready(function() {
			window.setTimeout(function() {
				themehouse.common[20180112].resizeFire()
			}, ' . $__templater->func('property', array('uix_stickySidebarCalcDelay', ), false) . ');
		})
	';
		}
		$__templater->inlineJs('
	$(".uix_sidebarInner .uix_sidebar--scroller").stick_in_parent({
		parent: \'.p-body-main\',
	});

	$(".p-body-sideNavInner .uix_sidebar--scroller").stick_in_parent({
		parent: \'.p-body-main\',
	});

	' . $__compilerTemp1 . '
');
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);