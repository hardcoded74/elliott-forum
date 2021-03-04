<?php
// FROM HASH: 78317deef9aec15f3bda17d0d1136b30
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.p-quickSearch .input {
	color: @xf-uix_searchBar--color;

	&::placeholder {color: @xf-uix_searchBar--color;}
}

body .uix_searchBar {
	display: inline-flex;
	@media (min-width: @xf-uix_search_maxResponsiveWidth) {
		position: relative;	
	}
	flex-shrink: 10;

	';
	if (!$__templater->func('property', array('uix_searchButton', ), false)) {
		$__finalCompiled .= '
	.uix_searchIcon {pointer-events: none;}
	';
	}
	$__finalCompiled .= '

	@media (max-width: @xf-uix_search_maxResponsiveWidth) {
		';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'navigationLeft') {
		$__finalCompiled .= '
		order: 1;
		';
	}
	$__finalCompiled .= '
	}

	@media (min-width: ' . ($__templater->func('property', array('uix_search_maxResponsiveWidth', ), false) + 1) . 'px ) {
		max-width: @xf-uix_searchBarWidth;
		width: 1000px;
		display: flex;
		// min-width: @xf-uix_searchBarWidth;
		';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'navigationLeft') {
		$__finalCompiled .= '
		margin: 0 @xf-paddingMedium;
		';
	} else {
		$__finalCompiled .= '
		margin-left: .5em;
		';
	}
	$__finalCompiled .= '
	}

	.uix_searchBarInner {
		display: inline-flex;
		pointer-events: none;
		align-items: center;
		left: 20px;
		right: 20px;

		justify-content: flex-end;
		bottom: 0;
		top: 0;
		transition: ease-in background-color .3s;
		flex-grow: 1;
		left: 0px;
		right: 0px;

		@media (min-width: @xf-uix_search_maxResponsiveWidth) {
			';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'navigationLeft') {
		$__finalCompiled .= '
			width: 100%;
			';
	}
	$__finalCompiled .= '
		}


		.uix_searchIcon {
			position: absolute;
			bottom: 0;
			top: 0;
			';
	if (!$__templater->func('property', array('uix_searchButton', ), false)) {
		$__finalCompiled .= '
			left: 0;
			';
	} else {
		$__finalCompiled .= '
			right: 0;
			';
	}
	$__finalCompiled .= '
		}


		';
	if ($__templater->func('property', array('uix_searchIconBehavior', ), false) == 'expandMobile') {
		$__finalCompiled .= '
		@media (max-width: min(@xf-responsiveMedium, @xf-uix_search_maxResponsiveWidth) )  {
			position: absolute;
		}
		';
	} else if ($__templater->func('property', array('uix_searchIconBehavior', ), false) == 'expand') {
		$__finalCompiled .= '
		@media (max-width: @xf-uix_search_maxResponsiveWidth) {
			position: absolute;
		}
		';
	}
	$__finalCompiled .= '


		.uix_searchForm {
			display: inline-flex;
			align-items: center;
			transition: ease-in flex-grow .3s, ease-in max-width .3s, ease-in background-color .2s;
			flex-grow: 0;
			max-width: @xf-uix_searchBarWidth;
			width: 100%;
			pointer-events: all;
			position: relative;
			.xf-uix_searchBar();	

			&.uix_searchForm--focused {
				.xf-uix_searchBarFocus();
				.input {
					&::placeholder {color: @xf-uix_searchBarPlaceholderFocusColor;}
				}

				i {color: @xf-uix_searchIconFocusColor;}
			}

			.uix_search--settings i,
			.uix_search--close i {display: none;}

			i {
				.xf-uix_searchIcon();
				height: @xf-uix_searchBarHeight;
				display: inline-flex;
				align-items: center;
				transition: ease-in color .2s;
			}

			.input {
				height: @xf-uix_searchBarHeight;
				border: none;
				transition: ease-in color .2s;
				background: none;
				&::placeholder {color: @xf-uix_searchBarPlaceholderColor;}
				color: inherit;
				';
	if (!$__templater->func('property', array('uix_searchButton', ), false)) {
		$__finalCompiled .= '
				text-indent: 30px;
				';
	}
	$__finalCompiled .= '
			}
		}
	}

	.p-navgroup-link {display: none;}

	@media(max-width: @xf-uix_search_maxResponsiveWidth) {
		.uix_searchBarInner .uix_searchForm {max-width: 0; overflow: hidden; border: none;}
	}

	';
	if ($__templater->func('property', array('uix_searchIconBehavior', ), false) != 'expandMobile') {
		$__finalCompiled .= '
	@media (max-width: @xf-uix_search_maxResponsiveWidth) {
		.p-navgroup-link {display: inline-flex;}
		.minimalSearch--detailed & .p-navgroup-link {display: inline-flex;}
	}
	';
	} else if ($__templater->func('property', array('uix_searchIconBehavior', ), false) == 'expandMobile') {
		$__finalCompiled .= '
	@media(max-width: @xf-uix_search_maxResponsiveWidth) and (min-width: @xf-responsiveMedium) {
		.p-navgroup-link {display: inline-flex;}
		.p-navgroup-link.uix_searchIconTrigger {display: none;}
	}

	@media (max-width: @xf-uix_search_maxResponsiveWidth) and (max-width: @xf-responsiveMedium) {
		.p-navgroup-link.uix_searchIconTrigger {display: inline-flex;}
		.p-navgroup-link {display: none;}

		.minimalSearch--detailed & .p-navgroup-link.uix_searchIconTrigger {display: none;}
		.minimalSearch--detailed & .p-navgroup-link {display: inline-flex;}

	}
	';
	}
	$__finalCompiled .= '

}

.uix_searchBar .uix_searchDropdown__menu {
	display: none;
	position: absolute;
	top: @xf-uix_searchBarHeight;
	right: 0;
	opacity: 0;
	width: @xf-uix_searchBarWidth;
	max-width: @xf-uix_searchBarWidth;
	@media(max-width: @xf-uix_search_maxResponsiveWidth) {
		width: 100%;
		max-width: 100%;
	}

	&.uix_searchDropdown__menu--active {
		display: block;
		opacity: 1;
		pointer-events: all;
		transform: translateY(0);
	}

	[name="constraints"] {
		flex-grow: 1 !important;
		widxth: auto !important;
	}
}

.uix_search--submit:hover {cursor: pointer;}

.uix_search--close {
	cursor: pointer;
}

@media(max-width: @xf-uix_search_maxResponsiveWidth) {

	.minimalSearch--active .uix_searchBar .uix_searchBarInner {
		position: absolute;
	}

	.minimalSearch--active .uix_searchBar .uix_searchBarInner .uix_searchForm {
		flex-grow: 1;
		display: inline-flex !important;
		padding: 0 @xf-paddingMedium;
		max-width: 100%;
	}

	.minimalSearch--active .uix_searchBar .uix_searchBarInner .uix_searchForm {
		i.uix_icon {
			display: inline-block;
			padding: 0;
			line-height: @xf-uix_searchBarHeight;
		}

		.uix_searchIcon i {display: none;}
		.uix_searchInput {text-indent: 0;}
	}

	.p-navgroup-link--search,
	.uix_sidebarCanvasTrigger,
	.p-navgroup-link {transition: ease opacity .2s .3s; opacity: 1;}

	.minimalSearch--active {
		.p-navgroup-link--search,
		.uix_sidebarCanvasTrigger,
		.p-navgroup-link {
			opacity: 0;
			transition: ease opacity .2s;
			pointer-events: none;
		}

		// .uix_searchBar {position: static;}
	}

	@media(max-width: @xf-uix_search_maxResponsiveWidth) {
		.p-nav-inner > * {transition: ease-in opacity .2s; opacity: 1;}

		.minimalSearch--active.p-nav-inner > *:not(.uix_searchBar),
		.minimalSearch--active.p-nav-inner .p-account,
		.minimalSearch--active.p-nav-inner .uix_searchBar .uix_searchIconTrigger,
		.minimalSearch--active.p-nav-inner .p-discovery > *:not(.uix_searchBar) {opacity: 0;}

		.minimalSearch--active.p-nav-inner .p-discovery,
		.minimalSearch--active.p-nav-inner .p-nav-opposite {opacity: 1;}
	}

	';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'navigation') {
		$__finalCompiled .= '

	.p-nav-inner > * {transition: ease-in opacity .2s; opacity: 1;}

	.minimalSearch--active.p-nav-inner > *,
	.minimalSearch--active.p-nav-inner .p-account,
	.minimalSearch--active.p-nav-inner .uix_searchBar .uix_searchIconTrigger,
	.minimalSearch--active.p-nav-inner .p-discovery > *:not(.uix_searchBar) {opacity: 0;}

	.minimalSearch--active.p-nav-inner .p-discovery,
	.minimalSearch--active.p-nav-inner .p-nav-opposite {opacity: 1;}
	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'navigationLeft') {
		$__finalCompiled .= '

	.p-nav-inner > *:not(.uix_searchBar),
	.p-nav-inner .uix_searchBar .uix_searchIconTrigger {transition: ease-in opacity .2s; opacity: 1;}

	.minimalSearch--active.p-nav-inner > *:not(.uix_searchBar),
	.minimalSearch--active.p-nav-inner .p-account,
	.minimalSearch--active.p-nav-inner .uix_searchBar .uix_searchIconTrigger,
	.minimalSearch--active.p-nav-inner .p-discovery,
	.minimalSearch--active.p-nav-inner .p-nav-opposite {opacity: 0;}

	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'tablinks') {
		$__finalCompiled .= '

	.p-sectionLinks .pageContent > * {transition: ease opacity .2s; opacity: 1;}

	.minimalSearch--active.p-sectionLinks .pageContent > * {opacity: 0;}

	.p-sectionLinks .pageContent .uix_searchBar {opacity: 1;}

	.minimalSearch--active.p-sectionLinks .p-discovery,
	.minimalSearch--active.p-sectionLinks .p-nav-opposite {opacity: 1;}

	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'header') {
		$__finalCompiled .= '

	';
		if ($__templater->func('property', array('uix_viewportShowLogoBlock', ), false) == '100%') {
			$__finalCompiled .= '

	.p-header-content > * {transition: ease opacity .2s; opacity: 1;}

	.minimalSearch--active.p-header-content > *:not(.p-nav-opposite) {opacity: 0;}

	.minimalSearch--active.p-header-content .uix_searchBar {opacity: 1;}

	';
		} else {
			$__finalCompiled .= '

	@media (min-width: @xf-uix_viewportShowLogoBlock) {

		.p-header-content > * {transition: ease opacity .2s; opacity: 1;}

		.minimalSearch--active.p-header-content > *:not(.p-nav-opposite) {opacity: 0;}

		.minimalSearch--active.p-header-content .uix_searchBar {opacity: 1;}
	}

	';
		}
		$__finalCompiled .= '

	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->func('property', array('uix_searchPosition', ), false) == 'staffBar') {
		$__finalCompiled .= '

	.p-staffBar .pageContent > * {transition: ease opacity .2s; opacity: 1;}

	.minimalSearch--active.p-staffBar .pageContent > * {opacity: 0;}

	.minimalSearch--active.p-staffBar .pageContent .uix_searchBar {opacity: 1;}

	.minimalSearch--active.p-staffBar .p-discovery,
	.minimalSearch--active.p-staffBar .p-nav-opposite {opacity: 1;}
	';
	}
	$__finalCompiled .= '
}';
	return $__finalCompiled;
}
);