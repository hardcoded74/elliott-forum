<?php
// FROM HASH: afc2fc0d27c2f8dc8c8016498111d337
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ######################################### MAIN BODY #################################

.p-body
{
	display: flex;
	align-items: stretch;
	flex-grow: 1;
	min-height: 1px; // IE11 workaround - related to #139187
	position: relative;
}

.p-body-inner
{
	width: 100%;
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'wrapped') {
		$__finalCompiled .= '
	.m-pageWidth();
	.m-pageInset();
	';
	} else {
		$__finalCompiled .= '
	@media (min-width: ' . ($__templater->func('property', array('responsiveEdgeSpacerRemoval', ), false) + 1) . 'px ) {
		flex-grow: 1;
	}
	@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
		.m-pageWidth();
		.m-pageInset(); 
	}
	';
	}
	$__finalCompiled .= '
	transition: ease-in-out max-width .3s, ease-in-out left .3s, ease-in-out width .3s;
	display: flex;
	flex-direction: column;
	.m-clearFix();
	position: relative;
	left: 0;
	padding-left: 0;
	padding-right: 0;
	padding-bottom: @xf-elementSpacer;

	.uix_page--fluid & {
		transition: @uix_moveIn max-width .3s, @uix_moveIn left .3s, @uix_moveIn width .3s;
	}

	&.p-body-inner-none {
		max-width: 100%;
		width: 100%;
		margin: 0;
		padding: 0;
	}

	/*
	> * {
	margin-bottom: 20px;

	&:last-child {margin-bottom: 0;}
}
	*/

	.p-body-header
	{
		margin-bottom: @xf-elementSpacer;
	}

}

.uix_contentWrapper {
	// margin-bottom: @xf-elementSpacer;
	flex-grow: 1;
	';
	if ($__templater->func('property', array('uix_contentWrapper', ), false) == 1) {
		$__finalCompiled .= '
	padding: @xf-pageEdgeSpacer;
	.xf-uix_contentWrapperStyle();
	@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
		padding: ' . ($__templater->func('property', array('pageEdgeSpacer', ), false) / 2) . 'px;
	margin-left: -' . ($__templater->func('property', array('pageEdgeSpacer', ), false) / 2) . 'px;
margin-right: -' . ($__templater->func('property', array('pageEdgeSpacer', ), false) / 2) . 'px;
border: none;
box-shadow: none;
}
';
	}
	$__finalCompiled .= '
}

.p-body-main
{
	// display: table;
	table-layout: fixed;
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	width: 100%;
	max-width: 100%;
	margin-bottom: auto;
	min-height: 1px; // IE11 workaround - related to #139187
}

.p-body-content
{
	// display: table-cell;
	vertical-align: top;
	@media ( min-width: ' . ($__templater->func('property', array('uix_sidebarBreakpoint', ), false) + 1) . 'px ) {
		';
	if (($__templater->func('property', array('uix_sidebarLocation', ), false) == 'right')) {
		$__finalCompiled .= '
		transition: ease-in width .2s, ease-in max-width .2s;

		.uix_sidebarCollapsed & {
			transition: ease-in width .2s .2s, ease-in max-width .2s .2s;
		}
		';
	} else {
		$__finalCompiled .= '
		transition: @uix_moveOut width .2s, ease-in max-width .2s;
		.uix_sidebarCollapsed & {
			transition: ease-in width .2s .2s, ease-in max-width .2s .2s;
		}
		';
	}
	$__finalCompiled .= '
	}
	flex-grow: 1;
	max-width: 100%;
	width: 100%;

	.p-body-main--withSidebar &,
	.p-body-main--withSideNav &
	{
		// don\'t let the ad overflow the sidebar area -- this can happen due to how the Adsense JS works
		ins.adsbygoogle
		{
			// -10px gives a little buffer or helps account for no scrollbar being considered
			max-width: ~"calc(100vw - 10px - @{xf-pageEdgeSpacer} - @{xf-pageEdgeSpacer} - @{xf-sidebarWidth} - @{xf-sidebarSpacer})";

			@media (min-width: ' . ($__templater->func('property', array('uix_sidebarBreakpoint', ), false) + 1) . 'px )
			{
				// window wider than the max width, so limit to the display area without the sidebar
				max-width: ~"calc(@{xf-pageWidthMax} - @{xf-pageEdgeSpacer} - @{xf-pageEdgeSpacer} - @{xf-sidebarWidth} - @{xf-sidebarSpacer})";
			}

			@media (max-width: @xf-uix_sidebarBreakpoint)
			{
				// sidebar/sidenav have been moved/hidden
				max-width: 100vw;
			}
		}
	}

	.p-body-main--withSideNav &
	{
		@media (min-width: ' . ($__templater->func('property', array('uix_sidebarBreakpoint', ), false) + 1) . 'px ) {
			width: calc(~"100% - ' . ($__templater->func('property', array('sidebarWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)) . 'px");
			max-width: calc(~"100% - ' . ($__templater->func('property', array('sidebarWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)) . 'px");
			display: inline-block;
		}
	}

	.p-body-main--withSidebar &
	{
		@media (min-width: ' . ($__templater->func('property', array('uix_sidebarBreakpoint', ), false) + 1) . 'px ) {
			width: calc(~"100% - ' . ($__templater->func('property', array('sidebarWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)) . 'px");
			max-width: calc(~"100% - ' . ($__templater->func('property', array('sidebarWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)) . 'px");
			display: inline-block;
		}
	}

	@media (min-width: ' . ($__templater->func('property', array('uix_sidebarBreakpoint', ), false) + 1) . 'px ) {
		.p-body-main--withSidebar.p-body-main--withSideNav & {
			width: calc(~"100% - ' . (($__templater->func('property', array('sidebarWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)) * 2) . 'px");
			max-width: calc(~"100% - ' . (($__templater->func('property', array('sidebarWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)) * 2) . 'px");
			display: inline-block;
		}
	}
}

.p-body-pageContent
{
	> .tabs--standalone:first-child
	{
		margin-bottom: (@xf-elementSpacer) / 2;
	}
}

.p-body-pageContent {
	';
	if ($__templater->func('property', array('uix_contentWrapper', ), false) == 2) {
		$__finalCompiled .= '
	.xf-uix_contentWrapperStyle();
	padding: @xf-pageEdgeSpacer;

	@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
		padding: ' . ($__templater->func('property', array('pageEdgeSpacer', ), false) / 2) . 'px;
	margin-left: -' . ($__templater->func('property', array('pageEdgeSpacer', ), false) / 2) . 'px;
margin-right: -' . ($__templater->func('property', array('pageEdgeSpacer', ), false) / 2) . 'px;
border: none;
box-shadow: none;
}
';
	}
	$__finalCompiled .= '
}

.p-body-sideNav
{
	display: table-cell;
	vertical-align: top;
	width: @xf-sidebarWidth;
	float: left;
}

.p-body-sideNavTrigger
{
	display: none;
}

.p-body-sidebar
{
	// display: table-cell;
	display: inline-block;
	vertical-align: top;
	width: @xf-sidebarWidth;

	.contentRow-figure .avatar--m
	{
		// make these avatars a bit smaller in the sidebar so the content has more space
		.m-avatarSize(@avatar-m * 2 / 3);
	}
}

.block[data-widget-id],
.p-body-sideNav,
.p-body-sidebar,
.uix_extendedFooterRow,
.columnContainer-sidebar {
	.block-container {
		.xf-uix_sidebarWidgetWrapper();
	}

	.block-minorHeader,
	.block-header
	{
		display: flex;
		align-items: center;
		padding: @xf-uix_widgetPadding;
		.xf-uix_sidebarWidgetHeading();
	}

	.block-minorHeader:before,
	.block-header:before {
		';
	if (($__templater->func('property', array('uix_iconFontFamily', ), false) == 'fontawesome')) {
		$__finalCompiled .= '
		.m-faBase();
		';
	}
	$__finalCompiled .= '
		font-size: @xf-uix_iconSize !important;
		padding-right: @xf-paddingMedium;
		color: @xf-textColorMuted;
	}

	.block-footer {
		padding: @xf-uix_widgetPadding;
		.xf-uix_sidebarWidgetFooter();
	}

	.block-row {
		padding: @xf-uix_widgetPadding;
		.xf-uix_sidebarBlockRow();
	}
}

// ----  Widget icons -----

.p-body-sidebar .block .block-minorHeader:before,
.p-body-sideNavContent .block .block-minorHeader:before, 
.p-body-pageContent .block .block-minorHeader:before,
.p-body-sidebar .block .block-header:before,
.p-body-sideNavContent .block .block-header:before, 
.p-body-pageContent .block .block-header:before {
	';
	if (($__templater->func('property', array('uix_iconFontFamily', ), false) == 'fontawesome')) {
		$__finalCompiled .= '
	.m-faBase();
	';
	}
	$__finalCompiled .= '
}

.block[data-widget-definition],
.block[data-widget-key], .p-body-sideNav, .p-body-sidebar {
	';
	if ($__templater->func('property', array('uix_defaultSidebarIcon', ), false)) {
		$__finalCompiled .= '
	.block-minorHeader:before,
	.block-header:before, {
		.m-faContent(@fa-var-file-alt);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'article',
		), $__vars) . '
	}
	';
	}
	$__finalCompiled .= '
}
.block[data-widget-definition="th_userNavigation"] .block-minorHeader:before {
	.m-faContent(@fa-var-user);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'user',
	), $__vars) . '
}
.block[data-widget-definition="members_online"] .block-minorHeader:before {
	.m-faContent(@fa-var-users);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'user-multiple',
	), $__vars) . '
}
.block[data-widget-definition="board_totals"] .block-minorHeader:before,
.block[data-widget-definition="online_statistics"] .block-minorHeader:before,
.block[data-widget-definition="forum_statistics"] .block-minorHeader:before {
	.m-faContent(@fa-var-chart-bar);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'statistics',
	), $__vars) . '
}
.block[data-widget-definition="share_page"] .block-minorHeader:before {
	.m-faContent(@fa-var-share);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'share',
	), $__vars) . '
}
.block[data-widget-definition="most_messages"] .block-minorHeader:before {
	.m-faContent(@fa-var-comments);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'messages',
	), $__vars) . '
}
.block[data-widget-definition="find_member"] .block-minorHeader:before {
	.m-faContent(@fa-var-search);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'search-member',
	), $__vars) . '
}
.block[data-widget-definition="new_threads"] .block-minorHeader:before,
.block[data-widget-definition="new_profile_posts"] .block-minorHeader:before,
.block[data-widget-definition="new_posts"] .block-minorHeader:before{
	.m-faContent(@fa-var-comment);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'post',
	), $__vars) . '
}
.block[data-widget-definition="birthdays"] .block-minorHeader:before{
	.m-faContent(@fa-var-birthday-cake);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'birthday',
	), $__vars) . '
}
.block[data-widget-definition="th_navigation"] .block-minorHeader:before{
	.m-faContent(@fa-var-list);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'list',
	), $__vars) . '
}
body .block[data-widget-key="thuix_footer_facebookWidget"] .block-minorHeader:before {
	.m-faBase(\'Brands\');
	.m-faContent(@fa-var-facebook);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'facebook',
	), $__vars) . '
}
body .block[data-widget-definition="thuix_footer_twitterWidget"] .block-minorHeader:before{
	.m-faBase(\'Brands\');
	.m-faContent(@fa-var-twitter);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'twitter',
	), $__vars) . '
}
form[data-xf-init*="poll-block"] .block-minorHeader:before {
	.m-faContent(@fa-var-poll);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'poll',
	), $__vars) . '
}

// xpress WP widget support

.p-body-sidebar .block-xpress {
	.block-minorHeader:before {
		.m-faContent(@fa-var-file-alt);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'article',
	), $__vars) . '
	}
	&.widget_media_gallery .block-minorHeader:before,
	&.widget_media_audio .block-minorHeader:before {
		.m-faContent(@fa-var-image);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'media',
	), $__vars) . '
	}
	&.widget_calendar .block-minorHeader:before {
		.m-faContent(@fa-var-calendar);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => '\\f0ed',
	), $__vars) . '
	}
	&.widget_recent_comments .block-minorHeader:before {
		.m-faContent(@fa-var-comments);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'comment-multiple',
	), $__vars) . '}

	&.widget_search .block-minorHeader:before {
		.m-faContent(@fa-var-search);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'search',
	), $__vars) . '}
}

';
	if (!$__templater->func('property', array('uix_sidebarIcons', ), false)) {
		$__finalCompiled .= '
.p-body-sidebar .block .block-minorHeader:before,
.p-body-sideNavContent .block .block-minorHeader:before, 
.p-body-pageContent .block .block-minorHeader:before,
.p-body-sidebar .block .block-header:before,
.p-body-sideNavContent .block .block-header:before, 
.p-body-pageContent .block .block-header:before {display: none !important;}
';
	}
	$__finalCompiled .= '

';
	if (!$__templater->func('property', array('uix_footerIcons', ), false)) {
		$__finalCompiled .= '
.uix_extendedFooterRow .block .block-minorHeader:before {display: none !important;}
';
	}
	$__finalCompiled .= '

.uix_extendedFooterRow .block-minorHeader:before {
	.m-faBase();
	font-size: @xf-uix_iconSize !important;
	padding-right: @xf-paddingMedium;
}

';
	if ($__templater->func('property', array('uix_visitorPanelIcons', ), false)) {
		$__finalCompiled .= '
.block[data-widget-definition="visitor_panel"]

.pairs {
	dt:after {display: none;}
}
';
	}
	$__finalCompiled .= '

.p-body-content,
.p-body-sideNav,
.p-body-sideNavContent,
.uix_sidebarInner
{
	> :first-child
	{
		margin-top: 0;
	}

	> :last-child
	{
		margin-bottom: 0;
	}
}

@media (max-width: @xf-uix_sidebarBreakpoint )
{

	/*
	.p-body-main,
	.p-body-content
	{
	display: block;
}
	*/

	.p-body-content {flex-grow: 1; width: 100%;}

	.p-body-sideNav
	{
		display: block;
		width: 100%;
	}

	.p-body-sideNavTrigger
	{
		margin-bottom: ((@xf-elementSpacer) / 2);
		text-align: center;

		.button:before
		{
			.m-faBase();
			font-size: 120%;
			vertical-align: middle;
			display: inline-block;
			margin: -4px 6px -4px 0;
			.m-faContent(@fa-var-bars, .86em);
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'menu',
	), $__vars) . '
		}

		.has-js &
		{
			display: block;
		}
	}

	.has-js .p-body-sideNavInner:not(.offCanvasMenu)
	{
		display: none;

		.m-transitionFadeDown();
	}

	.has-no-js .p-body-sideNavInner
	{
		margin-bottom: @xf-elementSpacer;
	}

	.p-body-sidebar
	{
		width: 100%;
		float: none;
		order: 1;
		flex-grow: 1;
		display: block;
		margin-top: @xf-elementSpacer;

		.uix_sidebarInner {
			display: flex;
			flex-wrap: wrap;
			align-items: stretch;
			flex-grow: 1;
			margin: 0 -((@xf-pageEdgeSpacer) / 2);
		}

		.uix_sidebarInner .uix_sidebar--scroller > *
		{
			margin: 0 ((@xf-pageEdgeSpacer) / 2) @xf-elementSpacer;
			min-width: @xf-sidebarWidth;
			flex: 1 1 @xf-sidebarWidth;

			.block-container {
				margin-left: 0;
				margin-right: 0;
			}

			&:last-child
			{
				margin-bottom: @xf-elementSpacer;
			}
		}

		.uix_sidebarInner.offCanvasMenu-content .uix_sidebar--scroller > * {
			flex: auto;
			min-width: 0;
		}

		// add an invisible block to ensure that the last row has the correct widths
		&:after
		{
			display: block;
			content: \'\';
			height: 0;
			margin: 0 ((@xf-pageEdgeSpacer) / 2);
			min-width: @xf-sidebarWidth;
			flex: 1 1 @xf-sidebarWidth;
		}

		.block-container
		{
			display: flex;
			flex-direction: column;
			height: 100%;

			.block-footer
			{
				margin-top: auto;
			}
		}
	}

	.p-body-main--withSideNav,
	.p-body-main--withSidebar
	{
		.p-body-content { padding: 0; }
	}
}

.uix_sidebarCollapsed .uix_sidebarInner {
	overflow: hidden;
}

@media (max-width: @xf-responsiveEdgeSpacerRemoval)
{
	.p-body-sideNavContent
	{
		// this is likely to contain blocks that overflow the container so account for that
		margin: 0 -@xf-pageEdgeSpacer;
		padding: 0 @xf-pageEdgeSpacer;

		.offCanvasMenu &
		{
			margin: 0;
			padding: 0;
		}
	}

	.p-body-sidebar
	{
		display: block;
		margin-left: 0;
		margin-right: 0;

		.uix_sidebarInner > *
		{
			// margin-left: 0;
			// margin-right: 0;
			min-width: 0;
		}
	}
}';
	return $__finalCompiled;
}
);