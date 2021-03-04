<?php
// FROM HASH: fef3f2249bb15eb2ef587732934c4086
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->func('property', array('uix_pageAnimation', ), false)) {
		$__finalCompiled .= '
@media (min-width: @xf-responsiveWide) {

		// ANIMATION VARIABLES

		@macroAnimationSpeed: .15s;

		// MACRO ANIMATIONS

		@keyframes macroAnimation {
			from {
				opacity: 0;
			};
			to {
				opacity: 1;
			};
		}

		.m-uix_macroAnimation(@iteration) {
			animation: macroAnimation @macroAnimationSpeed ease;
			animation-delay: @iteration * @macroAnimationSpeed;
			animation-fill-mode: backwards;
		}

		';
		if ($__templater->func('property', array('uix_pageAnimation', ), false)) {
			$__finalCompiled .= '
		// .uix_headerContainer {.m-uix_macroAnimation(1);}
		.uix_sidebarNav {.m-uix_macroAnimation(1);}
		.p-body-inner {.m-uix_macroAnimation(2);}
		.p-body-sidebar {.m-uix_macroAnimation(3);}
		.p-footer {.m-uix_macroAnimation(4);}
		';
		}
		$__finalCompiled .= '

		// NODE ANIMATIONS

		@keyframes slideUp {
			from {
				transform: translatey(30px);
				opacity: 0;
			};
			to {
				transform: translatey(0px);
				opacity: 1;
			};
		}

		.m-uix_nodeAnimation(@iteration) {
			animation: slideUp .4s cubic-bezier(0.4, 0, 0.2, 1);
			animation-delay: (@iteration * .15s) + (@macroAnimationSpeed * 2);
			animation-fill-mode: backwards;
		}
	
		.uix_nodeLoop (@i) when (@i > 0) {
			&:nth-child(@{i}) {
				.m-uix_nodeAnimation(@i)
			}
			.uix_nodeLoop(@i - 1);
		}

		';
		if ($__templater->func('property', array('uix_nodeAnimations', ), false)) {
			$__finalCompiled .= '
		.block--category {
			.uix_nodeLoop(15);
		}
		';
		}
		$__finalCompiled .= '

		// SIDEBAR ANIMATIONS

		@sideNavItemDelay: .05s;

		@keyframes scootRight {
			from {
				transform: translatex(-30px);
				opacity: 0;
			};
			to {
				transform: translatex(0px);
				opacity: 1;
			};
		}

		.m-uix_sideNavAnimation(@iteration, @offset) {
			animation: scootRight .5s ease;
			animation-delay: (@iteration * @sideNavItemDelay) + (@macroAnimationSpeed) + (@offset * @sideNavItemDelay) - @sideNavItemDelay;
			animation-fill-mode: backwards;
		}

		.uix_sideNavLoop (@i) when (@i > 0) {
			&:nth-child(@{i}) {
				.uix_sideNavItemLoop(15);
			}
			.uix_sideNavLoop(@i - 1);
		}

		.uix_sideNavItemLoop (@i) when (@i > 0) {
			&.uix_sidebarNavList > li:nth-child(@{i}),
			.topic-filter-item:not(.th_topics_clearTopics):nth-child(@{i}) {
				.m-uix_sideNavAnimation(@i, @i);
			}
			.uix_sideNavItemLoop(@i - 1);
		}
	
		';
		if ($__templater->func('property', array('uix_sideNavigationAnimation', ), false)) {
			$__finalCompiled .= '

		.uix_sidebarNav__inner .uix_sidebar--scroller > * { 
			.uix_sideNavLoop(15);
		}
	
		';
		}
		$__finalCompiled .= '

		// SIDEBAR ANIMATION
	
		';
		if ($__templater->func('property', array('uix_sidebarWidgetAnimations', ), false)) {
			$__finalCompiled .= '

		@sidebarItemDelay: .1s;

		@keyframes scootLeft {
			from {
				transform: translatex(30px);
				opacity: 0;
			};
			to {
				transform: translatex(0px);
				opacity: 1;
			};
		}

		.m-uix_sidebarAnimation(@iteration) {
			animation: scootLeft .5s ease;
			animation-delay: (@iteration * @sidebarItemDelay) + (@macroAnimationSpeed * 3);
			animation-fill-mode: backwards;
			animation-iteration-count: 1;
		}

		.uix_sidebarLoop (@i) when (@i > 0) {
			&:nth-child(@{i}) {
				.m-uix_sidebarAnimation(@i)
			}
			.uix_sidebarLoop(@i - 1);
		}

		';
			if ($__templater->func('property', array('uix_stickySidebar', ), false) != 'sticky') {
				$__finalCompiled .= '
		// sticky kit breaks this animation
		.uix_sidebarInner .block {
			.uix_sidebarLoop(15);
		}
		';
			}
			$__finalCompiled .= '
	
		';
		}
		$__finalCompiled .= '

		// DISCUSSION LIST

		.uix_scootUpLoop (@i) when (@i > 0) {
			&:nth-child(@{i}) {
				animation: slideUp .3s ease;
				animation-delay: (@i * .05s) + (@macroAnimationSpeed * 2);
				animation-fill-mode: backwards;
			}
			.uix_scootUpLoop(@i - 1);
		}
	
		';
		if ($__templater->func('property', array('uix_discussionListAnimation', ), false)) {
			$__finalCompiled .= '

		.structItemContainer .structItem {
			.uix_scootUpLoop(20);
		}
	
		';
		}
		$__finalCompiled .= '
	
		';
		if ($__templater->func('property', array('uix_messageAnimation', ), false)) {
			$__finalCompiled .= '

		.block--messages .block-body > .message--post {
			.uix_scootUpLoop(20);
		}
	
		// ARTICLES LIST

		.xpress_articleList .article {
			.uix_scootUpLoop(20);
		}
	
		';
		}
		$__finalCompiled .= '
}
';
	}
	return $__finalCompiled;
}
);