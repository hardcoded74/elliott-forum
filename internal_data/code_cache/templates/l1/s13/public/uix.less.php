<?php
// FROM HASH: d0b878d24de8708fa3cb55f7e98756ea
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->includeTemplate('uix_icons.less', $__vars) . '
' . $__templater->includeTemplate('uix_buttonRipple.less', $__vars) . '
' . $__templater->includeTemplate('uix_pageAnimations.less', $__vars) . '
' . $__templater->includeTemplate('uix_canvas.less', $__vars) . '
' . $__templater->includeTemplate('uix_thirdParty.less', $__vars) . '
' . $__templater->includeTemplate('uix_bandaids.less', $__vars) . '

';
	if ($__templater->func('property', array('uix_onlineIndicator', ), false) == 'pulse') {
		$__finalCompiled .= '
@keyframes pulse {
	from {
		opacity: 1;
		border-width: 0px;
		transform: scale(1);
	}
	to {
		transform: scale(2);
		opacity: 0;
		border-width: 8px;
	}
}

.message-avatar-online {
	display: inline-flex;
	align-items: center;
	justify-content: center;

	&:after {
		content: \'\';
		position: absolute;
		height: 100%;
		width: 100%;
		border-radius: 100%;
		border: 1px solid rgb(127, 185, 0);
		height: 13px;
		width: 13px;
		opacity: 1;
		animation: pulse 1.5s;
		animation-iteration-count: infinite;
		animation-timing-function: ease;
		transform-origin: center;
	}
}
';
	}
	$__finalCompiled .= '

.fb-page {
	width: 100%;
}

.tabPanes .block-outer {padding-top: @xf-elementSpacer;}

.uix_node--transitioning {
	.m-uix_collapseOverflow();
}

.message .message-responses {
	.message-responseRow {
		&:first-child, &.is-active {
			margin-top: @xf-messagePaddingSmall;
		}
	}

	.comment .comment-actionBar.actionBar {
		padding: @xf-messagePadding 0 0;
		margin: 0;
	}
}

.p-nav-menuTrigger {
	position: relative; 

	&.badgeContainer:after {
		position: absolute;
		left: -5px;
		top: -5px;

		@media (min-width: @xf-responsiveNarrow) {
			display: none !important;	
		}
	}
}

.menu-footer-controls {
	display: flex;
	flex-wrap: wrap;

	.button:not(:last-child) {
		margin-right: 5px;
	}
}

.p-navEl .menu-linkRow:before {margin-right: @xf-paddingSmall; }

';
	if ($__templater->func('property', array('uix_sidebarMobileCanvas', ), false)) {
		$__finalCompiled .= '

@media (min-width: @xf-uix_sidebarBreakpoint) {
	.uix_sidebarCanvasTrigger {display: none;}
}

@media (max-width: @xf-uix_sidebarBreakpoint) {
	body .p-body-sidebar {
		position: fixed;
		top: 0;
		left: 0;
		bottom: 0;
		right: 0;
		transform: translatex(-100%);
		margin: 0;

		&.is-transitioning {
			transition: ease-in .25s .25s transform;

			.uix_sidebarInner {transition: transform 0.25s ease-in;}

			&.is-active {transition: ease-in .01s transform;}
		}

		.uix_sidebarInner {
			padding: 0;
			margin: 0;
			';
		if ($__templater->func('property', array('uix_sidebarLocation', ), false) == 'right') {
			$__finalCompiled .= '
			transform: translatex(200%);
			margin-left: auto;
			';
		} else {
			$__finalCompiled .= '
			transform: translatex(-100%);
			';
		}
		$__finalCompiled .= '
		}

		.block-container {box-shadow: none;}

		.uix_sidebar--scroller {margin: 0;}

		&.is-active {
			transform: translatex(0);

			.uix_sidebarInner.offCanvasMenu-content {
				transform: translatex(0);
			}
		}
	}
}
';
	} else {
		$__finalCompiled .= '
@media (max-width: @xf-uix_sidebarBreakpoint) {
	body .uix_sidebarInner .uix_sidebar--scroller {margin-top: 0;}	
}
';
	}
	$__finalCompiled .= '

// added to vertically center discussion list meta info

.structItem-cell--main .structItem-minor {
	display: flex;
	align-items: center;

	.structItem-parts {flex-grow: 1;}
	.structItem-extraInfo {order: 2; margin-left: auto;}
	.structItem-statuses {order: 1;}
}

// end

.uix_threadRepliesMobile {

	.pairs.pairs--justified > dt {margin-right: 2px;}

	dt:after {display: none;}
}

.uix_headerContainer .uix_mainTabBar {
	display: flex;
	justify-content: center;
	.xf-uix_mainTabsBarStyling();

	.block-tabHeader {border: none;}

	.tabs-tab:hover {background: none;}
}

.offCanvasMenu-content {min-height: 100vh;}

.offCanvasMenu-content .p-body-sideNavContent {
	.block {margin: 0;}

	.block-container {
		box-shadow: none;
	}
}

.p-body-header .uix_sidebarTrigger__component {margin-left: @xf-paddingMedium;}

.cover .memberHeader-blurb.pairs {
	border-color: rgba(255,255,255,.3);
}

.hScroller-scroll {
	&.th_scroller--start-active {
		-webkit-mask-image: linear-gradient(to right, transparent 3%, black 10%);
		mask-image: linear-gradient(to right, transparent 3%, black 10%);
	}

	&.th_scroller--end-active {
		-webkit-mask-image: linear-gradient(to left, transparent 3%, black 10%);
		mask-image: linear-gradient(to left, transparent 3%, black 10%);
	}

	&.th_scroller--end-active.th_scroller--start-active {
		-webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 3%,rgba(0,0,0,1) 10%,rgba(0,0,0,1) 90%,rgba(0,0,0,0) 97%);
		mask-image: linear-gradient(to right, rgba(0,0,0,0) 3%,rgba(0,0,0,1) 10%,rgba(0,0,0,1) 90%,rgba(0,0,0,0) 97%);
	}
}

.block[data-widget-definition="visitor_panel"] .contentRow {margin-bottom: @xf-paddingMedium;}

.block[data-widget-definition="th_widget_login_uix"] .formSubmitRow-controls {
	padding-left: 0;	
	text-align: center;
}

.uix_loginProvider__row {
	dt {display: none;}
	dd {text-align: center;}
}

.thBranding__pipe {display: none;}

.p-footer-copyright > * ~ .thBranding .thBranding__pipe {display: inline;}

.offCanvasMenu-link {padding: @xf-paddingMedium @xf-paddingLarge;}

.offCanvasMenu-subList {padding-bottom: 0;}

*::selection,
{
	.xf-uix_textSelection(); /* WebKit/Blink Browsers */
}
*::-moz-selection {
	.xf-uix_textSelection(); /* Gecko Browsers */
}

.block-container {
	.uix_newIndicator {
		.xf-uix_newNodeMarkerStyle();
	}
}

/* --- Sticky kit header fix --- */

.uix_headerContainer--stickyFix {
	height: 1px; 
	visibility: hidden;
}
.uix_headerContainer {margin-top: -1px !important;}

/* -- fixes sticky kit abs positioning for sticky header on covered themes -- */

';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered') {
		$__finalCompiled .= '
.has-no-hiddenscroll .is-modalOpen .is-sticky {
	margin-left: 0px;
}
';
	}
	$__finalCompiled .= '

.uix_adminTrigger {display: none !important;}

';
	if ($__templater->func('property', array('uix_collapseStaffbarLinks', ), false)) {
		$__finalCompiled .= '
@media (max-width: @xf-uix_viewportCollapseStaffLinks) {
	.p-staffBar-link {display: none !important;}
	.uix_adminTrigger {display: inline-block !important;}
}
';
	}
	$__finalCompiled .= '

/***** Abridged Signatures *******/

.uix_signatureExpand {display: none;}

.message-signature {
	overflow: hidden;
	transition: ease-in-out .2s height;

	&--expandable {
		height: @xf-uix_signatureMaxHeight + 0px; // adding 0 converts the number to px value}

		';
	if ($__templater->func('property', array('messageSignature--background-color', ), false)) {
		$__finalCompiled .= '
			@signatureBgColor: @xf-messageSignature--background-color;
		';
	} else if ($__templater->func('property', array('uix_message--background-color', ), false)) {
		$__finalCompiled .= '
			@signatureBgColor: @xf-uix_message--background-color;
		';
	} else if ($__templater->func('property', array('contentBase--background-color', ), false)) {
		$__finalCompiled .= '
			@signatureBgColor: @xf-contentBase--background-color;
		';
	} else {
		$__finalCompiled .= '
			@signatureBgColor: @xf-contentBg;
		';
	}
	$__finalCompiled .= '

		.uix_signatureExpand {
			padding-bottom: @xf-paddingLarge;
			background: linear-gradient(to bottom, fade(@signatureBgColor, 0%), @signatureBgColor 40%);
			text-align: center;
			display: flex;
			justify-content: center;
			align-items: flex-end;
			height: 60px;
			position: absolute;
			bottom: 0;
			left: 0;
			right: 0;
			color: @xf-textColorAttention;

			&:hover {cursor: pointer;}
		}
	}

	&.message-signature--expanded {
		height: auto;
		padding-bottom: @xf-paddingLarge;

		.bbWrapper {padding-bottom: (@xf-paddingLarge * 2);}

		.uix_signatureExpand {background: none;}
	}
}

/***** extra user info postbit collapse *******/

';
	if ($__templater->func('property', array('uix_collapseExtraInfo', ), false)) {
		$__finalCompiled .= '
.thThreads__userExtra--toggle {
	text-align: center;
	margin-top: @xf-paddingMedium;

	.thThreads__userExtra--trigger {
		transition: ease-in transform .2s .2s;
		display: inline-block;

		&:hover {cursor: pointer;}

		&:before {
			.m-faBase();
			.m-faContent(@fa-var-chevron-down);
		}
	}
}
';
	}
	$__finalCompiled .= '

.thThreads__message-userExtras {
	height: 0;
	overflow: hidden;
	transition: ease-in height .2s;
}

@media (max-width: @xf-messageSingleColumnWidth) {
	.thThreads__message-userExtras {display: none;}
	.thThreads__userExtra--toggle {display: none;}
}

.userExtra--expand  {
	.thThreads__message-userExtras {height: auto;}
	.thThreads__userExtra--trigger {transform: rotate(-180deg);}
}

/*------- sticky thread collapse ------- */

';
	if ($__templater->func('property', array('uix_collapsibleStickyThreads', ), false)) {
		$__finalCompiled .= '

.uix_threadCollapseTrigger {
	transition: ease transform .2s;
	margin-left: auto;
	font-size: 18px;

	&.is-active {transform: rotate(-180deg);}
}

.uix_stickyContainerOuter {
	transition: ease-in height 0.3s, ease-in opacity 0.3s;
	opacity: 1;

	display: none;
	opacity: 0;
	-webkit-transition: all 0.2s ease, -xf-opacity 0.2s ease;
	transition: all 0.2s ease, -xf-opacity 0.2s ease;
	overflow-y: hidden;
	height: 0;
	-webkit-transition-property: all, -xf-height;
	transition-property: all, -xf-height;

	&.is-active {
		display: block;
		opacity: 1;
		height: auto;
		overflow-y: visible;
	}
	&.is-transitioning {
		display: block;
		width: 100%;
	}
	&.is-transitioning {
		overflow-y: hidden;
	}

	.uix_stickyContainerInner {
		width: 100%;
		display: table;
	}
}

.uix_stickyContainerOuter.uix_threadListSeparator--collapsed {
	height: 0;
	opacity: 0;
	pointer-events: none;
	transition: ease-in height 0.3s, ease-in opacity 0.3s;

	.uix_block-body--outer {
		height: 0 !important;
		opacity: 0;
		pointer-events: none;
	}
}

';
	}
	$__finalCompiled .= '

/*------- category collapse ------- */

';
	if ($__templater->func('property', array('uix_categoryCollapse', ), false)) {
		$__finalCompiled .= '

@keyframes overflow {
	from {
		overflow: visible;
	}
	to {
		overflow: hidden;
	}
}

.category--collapsed.block--category {

	.uix_block-body--outer {
		height: 0 !important;
		opacity: 0;
		pointer-events: none;
		animation: overflow;
		animation-delay: .3s;
		aniamtion-fill-mode: backwards;
	}

	.categoryCollapse--trigger {transform: rotate(-180deg);}

	&:hover {text-decoration: none;}
}

.categoryCollapse--trigger {
	transition: @uix_move transform .2s;
	overflow: hidden;
	height: 18px;
	min-width: 24px;
	width: 24px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	text-decoration: none !important;
}

.block--category .uix_block-body--outer {
	transition: @uix_move height 0.3s, @uix_move opacity 0.3s;
	opacity: 1;
}

';
	}
	$__finalCompiled .= '

/*------- Sidebar collapse ------- */

a.uix_sidebarTrigger__component {
	display: inline-flex;
	align-items: center;

	&:hover {cursor: pointer;}

	';
	if ($__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false) != '100%') {
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('uix_sidebarTriggerPosition', ), false) == 'sectionLinks') {
			$__finalCompiled .= '
	@media (min-width: ' . ($__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false) + 1) . 'px) {
		.p-nav-inner & {display: none !important;}
	}
	';
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->func('property', array('uix_sidebarTriggerPosition', ), false) == 'titlebar') {
		$__finalCompiled .= '
	@media (min-width: ' . ($__templater->func('property', array('responsiveWide', ), false) + 1) . 'px) {
		.p-nav-inner & {display: none !important;}
	}
	@media (max-width: @xf-responsiveWide) {
		.p-body-header & {display: none !important;}
	}
	';
	}
	$__finalCompiled .= '

	.p-nav-inner & {
		color: inherit;
		color: @xf-publicNavTab--color;
		background: none !important;
		box-shadow: none;
		border: none;

		.uix_sidebarTrigger--phrase {
			display: none;
		}

		i {
			font-size: @xf-uix_iconSizeLarge;
			width: auto;
			padding: 0;
			margin: 0;

			&:before {
				display: inline-flex;
				justify-content: center;
				width: 10px;
			}
		}
	}

	&.uix_sidebarCanvasTrigger {
		@media (min-width: ' . ($__templater->func('property', array('uix_sidebarBreakpoint', ), false) + 1) . 'px) {
			display: none;
		}
	}

	&.uix_sidebarTrigger {
		@media (max-width: @xf-uix_sidebarBreakpoint) {
			display: none;
		}
	}

	i {
		// transform: rotate(0);
		// transition: ease-in transform .2s;
		font-size: @xf-uix_iconSize;
		margin-left: -7px;
	}

	';
	if ($__templater->func('property', array('uix_sidebarLocation', ), false) == 'left') {
		$__finalCompiled .= '
	span {order: 1;}

	/*
	i {
	transform: rotate(-180deg);
	.uix_sidebarCollapsed & {
	transform: rotate(0);
}
}
	*/

	';
	}
	$__finalCompiled .= '
}

';
	if ($__templater->func('property', array('uix_collapsibleSidebar', ), false)) {
		$__finalCompiled .= '

.p-body-sidebar {
	transition: ease-in width .2s, ease-in opacity .2s .3s, ease-in height .2s;
	opacity: 1;
	max-height: 100%;

	* {transition: ease-in font-size .01s;}
}

@media (min-width: @xf-responsiveWide) {
	.uix_sidebarCollapsed {
		.p-body-sidebar {
			transition: ease-in width 0.2s 0.2s, ease-in opacity 0.2s, ease-in height .2s .2s;
			width: 0;
			opacity: 0;
			height: 0;
		}

		.p-body-main--withSidebar .p-body-content {
			width: 100%;
			max-width: 100%;
			';
		if (($__templater->func('property', array('uix_sidebarLocation', ), false) == 'left')) {
			$__finalCompiled .= '
			transition: ease-in width 0.2s 0.2s, ease-in max-width 0.2s .2s
				';
		}
		$__finalCompiled .= '
		}

		.p-body-main--withSidebar.p-body-main--withSideNav .p-body-content {
			width: calc(~"100% - ' . ($__templater->func('property', array('sidebarWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)) . 'px");
			max-width: calc(~"100% - ' . ($__templater->func('property', array('sidebarWidth', ), false) + $__templater->func('property', array('elementSpacer', ), false)) . 'px");
			';
		if (($__templater->func('property', array('uix_sidebarLocation', ), false) == 'left')) {
			$__finalCompiled .= '
			transition: ease-in width 0.2s 0.2s, ease-in max-width 0.2s .2s
				';
		}
		$__finalCompiled .= '
		}
	}
}

';
	}
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('uix_sidebarLeft', ), false)) {
		$__finalCompiled .= '
.button.uix_sidebarTrigger__component {
	flex-direction: row-reverse;
}
';
	}
	$__finalCompiled .= '

/*------- navigation icons------- */

body .p-navEl-link, body .offCanvasMenu-link {
	font-family: inherit; 
	font-weight: inherit;
	-webkit-font-smoothing: initial;
}

';
	if ($__templater->func('property', array('uix_navTabIcons', ), false)) {
		$__finalCompiled .= '

.p-navEl-link:before, .offCanvasMenu-link:before {
	.m-faBase();
	.xf-uix_navTabIconStyle();
}

';
		if (!$__templater->func('property', array('uix_navTabIcons', ), false)) {
			$__finalCompiled .= '
.p-navEl-link:before {display: none !important;}
';
		}
		$__finalCompiled .= '

.p-navEl-link:not(.mdi):not(.fa), .offCanvasMenu-link:not(.mdi):not(.fa) {
	';
		if ($__templater->func('property', array('uix_iconFontFamily', ), false) == 'fontawesome') {
			$__finalCompiled .= '
	&[data-nav-id]:before {.m-faBase();}
	';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('uix_defaultNavIcon', ), false)) {
			$__finalCompiled .= '
	&[data-nav-id]:before {
		.m-faContent(@fa-var-folder);		
		' . $__templater->callMacro('uix_icons.less', 'content', array(
				'icon' => 'folder',
			), $__vars) . '
	}
	';
		}
		$__finalCompiled .= '

	.offCanvasMenu-link {dislay: none;}

	&[data-nav-id="thxpress"]:before {
		.m-faContent(@fa-var-newspaper);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => '\\f332',
		), $__vars) . '
	}
	&[data-nav-id="th_donate"]:before {
		.m-faContent(@fa-var-gift);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => '\\f2a1',
		), $__vars) . '
	}
	&[data-nav-id="home"]:before {
		.m-faContent(@fa-var-home);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'home',
		), $__vars) . '
	}
	&[data-nav-id="forums"]:before {
		.m-faContent(@fa-var-comments);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'forum',
		), $__vars) . '
	}
	&[data-nav-id="whatsNew"]:before {
		.m-faContent(@fa-var-bolt);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'whats-new',
		), $__vars) . '
	}
	&[data-nav-id="members"]:before {
		.m-faContent(@fa-var-users);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'user-multiple',
		), $__vars) . '
	}
	&[data-nav-id="profile"]:before {
		.m-faContent(@fa-var-user);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'user',
		), $__vars) . '
	}
	&[data-nav-id="alerts"]:before {
		.m-faContent(@fa-var-bell);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'alert',
		), $__vars) . '
	}
	&[data-nav-id="settings"]:before {
		.m-faContent(@fa-var-cog);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'settings',
		), $__vars) . '
	}
	&[data-nav-id="xfmg"]:before {
		.m-faContent(@fa-var-image);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'media',
		), $__vars) . '
	}
	&[data-nav-id="xfrm"]:before {
		.m-faContent(@fa-var-th-large);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'resource',
		), $__vars) . '
	}
	&[data-nav-id="EWRporta"]:before, &[data-nav-id="blog"]:before, &[data-nav-id="XPRESS"]:before {
		.m-faContent(@fa-var-newspaper);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'article',
		), $__vars) . '
	}
}

.p-sectionLinks .p-navEl-link:before,
.offCanvasMenu-subList .offCanvasMenu-link:before {display: none !important;}

';
	}
	$__finalCompiled .= '


/* ---Force header content fluid --- */

';
	if (($__templater->func('property', array('uix_navigationType', ), false) == 'sidebarNav') AND ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered')) {
		$__finalCompiled .= '
.p-staffBar .pageContent,
.p-header-inner,
.p-nav-inner,
.p-sectionLinks .pageContent {max-width: 100%;}
';
	}
	$__finalCompiled .= '

/* ---Sidebar Navigation --- */

.uix_headerContainer .p-nav-menuTrigger.uix_sidebarNav--trigger {display: none;}

';
	if (($__templater->func('property', array('uix_navigationType', ), false) == 'sidebarNav') AND ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered')) {
		$__finalCompiled .= '

@media (min-width: ' . ($__templater->func('property', array('publicNavCollapseWidth', ), false) + 1) . 'px ) {
	.uix_page--fluid.sidebarNav--active .p-body-inner {
		width: calc( ~"100% - ' . $__templater->func('property', array('uix_sidebarNavWidth', ), true) . ' - ' . ($__templater->func('property', array('elementSpacer', ), false) * 2) . 'px");
	}

	.uix_headerContainer .p-nav-menuTrigger.uix_sidebarNav--trigger {display: inline-block;}
	.uix_page--fixed.sidebarNav--active .p-body-inner {
		@media (max-width: @uix_sidebarNavBreakpoint) {
			width: calc( ~"100% - ' . $__templater->func('property', array('uix_sidebarNavWidth', ), true) . ' - ' . ($__templater->func('property', array('elementSpacer', ), false) * 2) . 'px");
		}

		@media (min-width: calc(@uix_sidebarNavBreakpoint + 1px) ) {
			left: -105px;
		}
	}
}
';
	}
	$__finalCompiled .= '

.uix_sidebarNav__inner__widgets {
	padding: @xf-paddingLarge;
}

.sidebarNav--active .uix_sidebarNav {
	margin-left: 0;
}

.uix_stickyBodyElement:not(.offCanvasMenu) {
	position: sticky;
	position: -webkit-sticky;

	' . '
}

.uix_stickyBottom .p-body-sidebar {display: flex;}

.uix_sidebarNav {
	.uix_sidebarNav__inner {
		overflow: hidden;
	}

	.uix_sidebar--scroller {
		overflow-y: auto;
		margin-right: -30px;
		padding-right: 32px;
	}
}

';
	if ($__templater->func('property', array('uix_stickySidebar', ), false) == 'top') {
		$__finalCompiled .= '
.uix_sidebarInner,
.p-body-sideNavInner:not(.offCanvasMenu) {
	position: static;
}
@media (min-width: ' . ($__templater->func('property', array('uix_sidebarBreakpoint', ), false) + 1) . 'px ) {
	.uix_sidebarInner,
	.p-body-sideNavInner {
		position: sticky;
		position: -webkit-sticky;
	}

	';
		if ($__templater->func('property', array('uix_scrollableSidebar', ), false)) {
			$__finalCompiled .= '
	.uix_sidebarInner, .p-body-sideNavInner {

		overflow: hidden;
		.block {
			margin-left: 2px;
			margin-rght: 2px;
		}

		.uix_sidebar--scroller {
			overflow-y: auto;
			max-height: 90vh;
			margin-right: -30px;
			padding-right: 32px;
			padding-top: 2px;
			padding-bottom: 2px;
		}
	}
	';
		}
		$__finalCompiled .= '
}
';
	}
	$__finalCompiled .= '

.offCanvasMenu .offCanvasMenu-link:before {
	font-size: @xf-uix_iconSizeLarge !important;
	padding-right: 1em;
}

.offCanvasMenu-link {
	display: flex;
	align-items: center;

	&.offCanvasMenu-link--splitToggle {font-size: 18px;}
}

.offCanvasMenu.is-active .uix_sidebar--scroller {
	width: 100vw !important;
}

.uix_sidebarNav {
	.xf-uix_sidebarNavigationStyle();

	width: @xf-uix_sidebarNavWidth;
	min-width: @xf-uix_sidebarNavWidth;
	padding-bottom: @xf-elementSpacer;
	margin-left: -' . $__templater->func('property', array('uix_sidebarNavWidth', ), true) . ';
';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'covered') {
		$__finalCompiled .= '
padding-top: @xf-elementSpacer;
';
	}
	$__finalCompiled .= '
z-index: 1;
transition: ease-in-out margin-left .3s;

@media (max-width: @xf-publicNavCollapseWidth) {
	margin-left: -' . $__templater->func('property', array('uix_sidebarNavWidth', ), true) . ' !important;
}

.uix_sidebarNavList {
	padding: 10px 0;
	margin: 0;
	border-bottom: 1px solid @xf-borderColor;
	line-height: 40px;

	&:first-child {padding-top: 0;}

	&:last-child {border-bottom: none;}

	.uix_sidebarNav__subNav {
		display: block;
		height: 0;
		overflow: hidden;
		transition: ease-in height .3s;

		&.subNav--expand {height: auto;}
	}

	.menu-linkRow {
		padding: 0 @xf-paddingLarge;
		white-space: nowrap;
		text-overflow: ellipsis;
		overflow: hidden;
		color: @xf-textColorDimmed;
		border: none;
		.xf-uix_canvasNavSubItem();

		&:hover {
			border: none;
			.xf-uix_canvasNavItemHover();
		}
	}

	.p-navEl-link span {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
		min-width: 0;
	}

	.uix_sidebarNavList__title {
		padding: 0 @xf-paddingLarge;
		font-size: @xf-fontSizeSmaller;
		color: @xf-textColorMuted;
	}

	> li {
		display: block;

		.p-navEl:before, .p-navEl:after {display: none;}

		.p-navEl__inner {
			display: flex;
			align-items: center;
			.xf-uix_canvasNavItem();
		}

		.p-navEl__inner:hover,
		.blockLink:hover {
			.xf-uix_canvasNavItemHover();
		}

		.is-selected .p-navEl__inner {
			.xf-uix_canvasNavItemActive();

			a, .p-navEl-link {
				color: inherit;
			}
		}

		.p-navEl {display: block;}

		.p-navEl-link,
		.p-navEl-linkHolder,
		.blockLink {
			display: flex;
			align-items: center;
			padding: 0 @xf-paddingLarge;
			color: @xf-textColorDimmed;
			background: none;
			width: 100%;
			text-decoration: none;
			float: none;

			&:before {
				font-size: @xf-uix_iconSizeLarge !important;
				width: @xf-uix_iconSizeLarge !important;
				padding-right: 1em;
			}
		}

		.p-navEl-linkHolder {padding: 0;}

		.blockLink {
			padding-left: 50px;
		}

		.uix_sidebarNav--trigger {
			font-size: @xf-uix_iconSize;
			color: inherit;
			padding-right: @xf-paddingLarge;

			.uix_icon {transition: ease-in transform .3s;}

			&.is-expanded .uix_icon {transform: rotate(-180deg);}
		}

		.p-navEl-splitTrigger, .p-navEl-link--menuTrigger:after {display: none;}
	}
}
}

/* ---VISITOR TABS --- */

.p-account {

	background-color: transparent;

	.p-navgroup-link {
		display: flex;
		align-items: center;
		border: none;
	}
}

.p-nav .p-navgroup-link:hover {.xf-publicNavTabHover();}

' . $__templater->includeTemplate('uix_searchComponent.less', $__vars) . '

/* ---UTILITIES --- */

.media__container {
	display: flex;
	.media--left {margin-right: @xf-paddingMedium;}
}

' . $__templater->includeTemplate('uix_footer.less', $__vars) . '

/* ---Login form sliding panel --- */

';
	if ($__templater->func('property', array('uix_loginStyle', ), false) == 'slidingPanel') {
		$__finalCompiled .= '

.uix__loginForm--panel {
	background: @xf-contentHighlightBg;
	overflow: hidden;
	position: absolute;
	z-index: 200;
	transition: ease-in .2s transform;
	left: 0;
	right: 0;
	transform: translateY(-100%);
	top: 0;
}

.uix__loginForm--mask {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	background-color: transparent;
	transition: ease-in .2s background-color;

}

.uix__loginForm.is-active .uix__loginForm--panel {transform: translateY(0);}

.uix__loginForm.is-active .uix__loginForm--mask {
	background-color: rgba(0,0,0,.4);
	bottom: 0;
}

.uix__loginForm--panel form {
	.m-pageWidth();
}

.uix__loginForm--panel .block-container,
.uix__loginForm--panel .formRow > dt,
.uix__loginForm--panel .formRow > dd,
.uix__loginForm--panel .formSubmitRow-bar {
	background: none;
	box-shadow: none;
	border: none;
}

.uix__loginForm--panel .block-outer {display: none;}

';
	}
	$__finalCompiled .= '

.uix_discussionList {
	.xf-uix_discussionList();
}

[type=checkbox], [type=radio], legend {margin-right: .5em;}

.structItem-extraInfo [type=checkbox] {margin-right: 0;}

form.structItem {
	display: flex;
	max-width: 100%;
}

.structItem-cell--newThread {flex-grow: 1; min-width: 0;}

.uix_messageContent {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	width: 100%;
}

.uix_socialMedia {
	display: inline-flex;
	flex-wrap: wrap;
	margin: 0 -6px;
	padding: 0;

	li {display: inline-block;}

	li a {
		margin: 6px;
		line-height: 1;
		display: inline-block;
		.xf-uix_socialMediaIcon();
	}
}

.uix_headerContainer {
	display: flex;
	flex-direction: column;

	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'wrapped') {
		$__finalCompiled .= '
	@media (min-width: ' . ($__templater->func('property', array('responsiveEdgeSpacerRemoval', ), false) + 1) . 'px) {
		margin-top: @xf-uix_headerWhiteSpace;
	}
	';
	}
	$__finalCompiled .= '

	@media (min-width: @xf-responsiveMedium) {
		> *:not(.p-nav) {
			margin-bottom: @xf-uix_headerWhiteSpace;
			&:last-child {margin-bottom: 0;}
		}
	}
}

';
	if ($__templater->func('property', array('uix_detachedNavigation', ), false)) {
		$__finalCompiled .= '
.p-body > * {padding-top: @xf-elementSpacer;}
';
	}
	$__finalCompiled .= '

.uix_pageWrapper--fixed {
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'wrapped') {
		$__finalCompiled .= '
	width: 100%;
	';
	}
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'wrapped') {
		$__finalCompiled .= '
	.m-pageWidth();
	.uix_page--fluid & {
		@media (min-width: @xf-pageWidthMax) {
			max-width: 95%;
		}
	}
	@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
		padding: 0 !important;

	}
	';
	}
	$__finalCompiled .= '
	position: relative;
	left: 0;
	transition: max-width .2s, ease-in left .2s;
}


/* --- post thread widget --- */

.uix_postThreadWidget {
	border-top: 4px solid @xf-uix_primaryColor;
	text-align: center;
	padding: @xf-paddingLarge;
	color: @xf-textColorDimmed;

	i {
		height: 50px;
		width: 50px;
		line-height: 50px;
		border-radius: 100%;
		background-color: fade(@xf-uix_primaryColor, 20%);
		color: @xf-uix_primaryColor;
		display: inline-block;
		font-size: @xf-uix_iconSizeLarge;
	}

	.uix_postThreadWidget__title {
		font-size: @xf-fontSizeLarger;
		font-weight: @xf-fontWeightHeavy;
		color: @xf-textColor;
	}

	.button {margin-top: @xf-paddingMedium;}
}

';
	if ($__templater->func('property', array('uix_borderRadiusJs', ), false)) {
		$__finalCompiled .= '

/* --- border radius javascript --- */

@media (min-width: @xf-responsiveEdgeSpacerRemoval) {
	.uix_smartBorder--noTop {
		border-bottom-left-radius: @xf-borderRadiusLarge !important;
		border-bottom-right-radius: @xf-borderRadiusLarge !important;
	}
	.uix_smartBorder--noBottom {
		border-top-left-radius: @xf-borderRadiusLarge !important;
		border-top-right-radius: @xf-borderRadiusLarge !important;
	}

	.uix_smartBorder--default {
		border-top-left-radius: @xf-borderRadiusLarge !important;
		border-top-right-radius: @xf-borderRadiusLarge !important;
		border-bottom-left-radius: @xf-borderRadiusLarge !important;
		border-bottom-right-radius: @xf-borderRadiusLarge !important;
	}
}
';
	}
	$__finalCompiled .= '

// overlay content

.block-footer.uix_conversationsFooterMobile {
	display: none;

	.overlay & {
		display: flex;
	}
}

.overlay .block-footer {
	position: sticky;
	bottom: 0;
}

';
	if ($__templater->func('property', array('uix_visitorTabsMobile', ), false) == 'tabbar') {
		$__finalCompiled .= '

/* -- tab bar -- */

.uix_tabBar {
	height: 46px;

	@media (min-width: @xf-responsiveNarrow) {
		display: none;
	}
}

.uix_tabList {
	z-index: 100;
	margin: 0;
	padding: 0;
	display: flex;
	background: @xf-uix_primaryColor;
	position: fixed;
	text-transform: capitalize;
	bottom: 0;
	left: 0;
	right: 0;
	box-shadow: 0 0 2px 0 rgba(0,0,0,0.14), 0 -2px 2px 0 rgba(0,0,0,0.12), 0 -1px 3px 0 rgba(0,0,0,.2);

	.uix_tabItem {
		flex-grow: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		padding: 5px;
		height: 46px;
		color: rgba(255,255,255,.5);
		line-height: 1;
		font-size: 10px;
		position: relative;

		&:hover {
			text-decoration: none; 
			color: #fff;
		}
	}

	.badgeContainer:after {
		position: absolute;
		top: 7px;
		right: 30%;
	}

	.uix_tabItem i {
		font-size: 24px;
	}
}

';
	}
	$__finalCompiled .= '

// remove border on macro components for mobile

@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
	.uix_headerContainer > *,
	.p-nav,
	.p-footer > * {border-radius: 0 !important;}
}

// nav edits for mobile

// staff bar

';
	if ($__templater->func('property', array('uix_staffBarBreakpoint', ), false) != '100%') {
		$__finalCompiled .= '
.uix_responsiveStaffBar {
	@media (max-width: @xf-uix_staffBarBreakpoint) {
		.p-staffBar {display: none;}
	}
}

@media (max-width: @xf-uix_staffBarBreakpoint) {
	.p-staffBar .p-nav-opposite {display: none;}
}

@media (min-width: ' . ($__templater->func('property', array('uix_staffBarBreakpoint', ), false) + 1) . 'px ) {
	';
		if ($__templater->func('property', array('uix_searchPosition', ), false) == 'staffBar') {
			$__finalCompiled .= '
	.p-nav .uix_searchBar {display: none;}
	';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('uix_userTabsPosition', ), false) == 'staffBar') {
			$__finalCompiled .= '
	.p-nav .p-navgroup--member, .p-nav .p-navgroup-link--whatsnew {display: none;}
	';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('uix_loginTriggerPosition', ), false) == 'staffBar') {
			$__finalCompiled .= '
	.p-nav .p-navgroup--guest {display: none;}
	';
		}
		$__finalCompiled .= '
}

';
	} else {
		$__finalCompiled .= '
.p-staffBar .p-navgroup--member, .p-nav .p-navgroup-link--whatsnew {display: none;}
.p-staffBar .p-navgroup--guest {display: none;}
.p-staffBar .uix_searchBar {display: none;}
';
	}
	$__finalCompiled .= '

/* --- show logoblock for desktop  --- */

';
	if ($__templater->func('property', array('uix_viewportShowLogoBlock', ), false) != '100%') {
		$__finalCompiled .= '

@media (max-width: ' . ($__templater->func('property', array('uix_viewportShowLogoBlock', ), false) - 1) . 'px ) {
	.p-header {display: none;}
}

@media (max-width: ' . ($__templater->func('property', array('uix_viewportShowLogoBlock', ), false) - 1) . 'px ) {
	.p-header .p-nav-opposite {display: none;}
}

@media (min-width: @xf-uix_viewportShowLogoBlock) {
	.p-nav-inner .p-header-logo {display: none;}

	';
		if ($__templater->func('property', array('uix_userTabsPosition', ), false) == 'header') {
			$__finalCompiled .= '
	.p-nav .p-navgroup--member, .p-nav .p-navgroup-link--whatsnew {display: none;}
	';
		}
		$__finalCompiled .= '	

	';
		if ($__templater->func('property', array('uix_searchPosition', ), false) == 'header') {
			$__finalCompiled .= '
	.uix_headerContainer > *:not(.p-header) .uix_searchBar {display: none;}
	';
		}
		$__finalCompiled .= '

	';
		if ($__templater->func('property', array('uix_searchPosition', ), false) == 'header') {
			$__finalCompiled .= '
	.uix_headerContainer > *:not(.p-header) .uix_searchBar {display: none;}
	';
		}
		$__finalCompiled .= '
}

';
	} else {
		$__finalCompiled .= '
.p-header {display: none;}
';
	}
	$__finalCompiled .= '

/* sub-navigation */

';
	if ($__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false) != '100%') {
		$__finalCompiled .= '
@media (max-width: @xf-uix_viewportWidthRemoveSubNav ) {
	';
		if (!$__templater->func('property', array('publicNavSelected--background-color', ), false)) {
			$__finalCompiled .= '
	.p-sectionLinks {display: none;}
	';
		} else {
			$__finalCompiled .= '
	.p-sectionLinks > * {display: none;}
	@media(max-width: @xf-publicNavCollapseWidth) {
		.p-sectionLinks {display: none;}
	}
	';
		}
		$__finalCompiled .= '
}

@media (min-width: ' . ($__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false) + 1) . 'px ) {
	';
		if ($__templater->func('property', array('uix_searchPosition', ), false) == 'tablinks') {
			$__finalCompiled .= '
	.p-nav .uix_searchBar {display: none;}
	';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('uix_userTabsPosition', ), false) == 'tablinks') {
			$__finalCompiled .= '
	.p-nav .p-navgroup--member, .p-nav .p-navgroup-link--whatsnew {display: none;}
	';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('uix_loginTriggerPosition', ), false) == 'tablinks') {
			$__finalCompiled .= '
	.p-nav .p-navgroup--guest {display: none;}
	';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('uix_sidebarTriggerPosition', ), false) == 'sectionLinks') {
			$__finalCompiled .= '
	.breadcrumb  a.uix_sidebarTrigger__component {display: none;}
	';
		}
		$__finalCompiled .= '
}

';
	} else {
		$__finalCompiled .= '
';
		if (!$__templater->func('property', array('publicNavSelected--background-color', ), false)) {
			$__finalCompiled .= '
.p-sectionLinks {display: none;}
';
		} else {
			$__finalCompiled .= '
.p-sectionLinks > * {display: none;}
@media(max-width: @xf-publicNavCollapseWidth) {
	.p-sectionLinks {display: none;}
}
';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

// search navigation

';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'navigationLeft') {
		$__finalCompiled .= '
.p-nav .p-nav-opposite .uix_searchBar {display: none;}
';
	}
	$__finalCompiled .= '


.menu--account .avatar-update a {
	max-width: 21px;
	overflow: hidden;
	font-size: 12px;
}

// label.iconic.iconic--labelled > input + i {margin-top: -2px; width: auto;}

.comment-reply-link:before {
	.m-faBase();
	.m-faContent(@fa-var-comment-alt);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'post',
	), $__vars) . '
	margin-right: 4px;
}

.sidePanel__tabs .badgeContainer:after {
	position: relative;
	top: -15px;
	left: -5px;
}

// notices

//scrolling notices

.lSSlideOuter.noticeScrollContainer {
	position: relative;

	.lSPager {
		margin-top: 0px;
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		height: 20px;
		background: none;	
	}	

	.notice-content {margin-bottom: 20px;}
}

// bottom notice

.notices--bottom_fixer .notice--cookie {
	.notice-content {
		display: flex; 

		@media (max-width: @xf-responsiveMedium) {
			text-align: center;
			align-items: center;
			flex-direction: column;
		}
	}

	.u-inputSpacer {margin-top: 0;}

	.uix_cookieButtonRow {
		margin: 0 -3px -3px @xf-paddingMedium;

		@media (max-width: @xf-responsiveMedium) {
			margin-top: @xf-paddingMedium;
			margin-left: -3px;
		}

		.button {margin: 3px;}
	}

	.notice-content > div:first-child {
		flex-grow: 1;
		justify-content: center;
		display: flex;
		flex-direction: column;
	}	
}

.uix_mobileNodeTitle {
	&:before {
		content: "\\00a0\\00B7\\00a0";
	}

	@media (min-width: @xf-uix_discussionListCollapseWidth) {
		display: none;
	}
}

// MENU

.menu {
	transition: @uix_move .26s transform, @uix_move .26s opacity;
	transform: translateY(-10px);
	opacity: 0;

	&.is-active {
		transform: translateY(0);
		opacity: 1;
	}
}

.input input {box-shadow: none !important;}

';
	if ($__templater->func('property', array('uix_clickableThreads', ), false)) {
		$__finalCompiled .= '
.structItem--thread:hover {cursor: pointer;}
';
	}
	$__finalCompiled .= '

.uix_sidebarNav .uix_sidebarNav__inner__widgets,
.offCanvasMenu-content .uix_sidebarNav__inner__widgets {

	.block-container,
	.block-minorHeader,
	.block-footer,
	.block-body
	.block-row {
		border: none;
		box-shadow: none;
		background: none;
		padding: 0;

		&:not(:last-child) {
			padding-bottom: @xf-paddingLarge;			
		}
	}

	.block:not(:last-child) .block-container {
		border-bottom: 1px solid @xf-borderColor;
		padding-bottom: @xf-paddingLarge;
	}

	.block-minorHeader {
		font-size: @xf-fontSizeSmaller;
		color: @xf-textColorMuted;

		&:before {display: none !important;}
	}

	[data-widget-definition="th_bookmarks"] {
		.bookmarkRow {
			line-height: 40px;
			display: flex;
			align-items: center;
			color: @xf-textColorDimmed;

			.contentRow-minor, .bookmarkRow-forum {display: none;}

			.bookmarkRow-content {
				overflow: hidden;	
				white-space: nowrap;
				text-overflow: ellipsis;
			}
		}		

		.block-row {padding: 0 !important;}

		.contentRow-main.contentRow-main--close {padding: 0;}

		i:before, a {color: inherit;}

		.block-footer, .contentRow-main:before {display: none;}

		.contentRow-figure {
			height: 24px;
			padding-right: 1em;
			font-size: 24px;

			.avatar, i {
				font-size: 24px;
				width: 24px;
				height: 24px;
				vertical-align: inherit;
			}

			.node-icon {
				width: auto;
			}
		}
	}
}

';
	if (!$__templater->func('property', array('uix_sidebarTriggerPhrase', ), false)) {
		$__finalCompiled .= '
.uix_sidebarTrigger--phrase {display: none;}
';
	}
	$__finalCompiled .= '

.uix_headerInner--opposite a.uix_sidebarTrigger__component {
	padding: 0 4px;
	i {margin: 0;}
}

.uix_originalPoster__icon {
	height: 19px;
	width: 19px;
	background: @xf-uix_primaryColor;
	display: inline-block;
	border-radius: 100%;
	.m-uix_whiteText(@xf-uix_primaryColor);
	text-align: center;
}';
	return $__finalCompiled;
}
);