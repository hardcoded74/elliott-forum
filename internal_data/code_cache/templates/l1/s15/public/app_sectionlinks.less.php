<?php
// FROM HASH: 5681211b413bda23c12cffb5a916b391
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// SUB SECTION LINKS

.p-sectionLinks
{
	.xf-publicSubNav();
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'fixed') {
		$__finalCompiled .= '
		.m-pageWidth();
	';
	}
	$__finalCompiled .= '
	
	transition: ease-in .15s all;
	
	.p-sectionLinks--group {
		display: flex; 
		align-items: center;
		
		';
	if ($__templater->func('property', array('uix_rightAlignNavigation', ), false)) {
		$__finalCompiled .= '
			&:first-child {
				margin-left: auto;
			}
		';
	}
	$__finalCompiled .= '
	}

	.hScroller-action
	{
		.m-hScrollerActionColorVariation(
			xf-default(@xf-publicSubNav--background-color, transparent),
			xf-default(@xf-publicSubNav--color, ~""),
			xf-default(@xf-publicSubNavElHover--color, ~"")
		);
	}
	
	.p-nav-opposite a:not(.button)
	{
		color: inherit;

		&:hover {
			.xf-publicSubNavElHover();
		}
	}

	&.p-sectionLinks--empty
	{
		height: 10px;
		display: none;
	}

	.pageContent {
		display: flex;
		align-items: center;
		position: relative;
		justify-content: space-between;
		';
	if ($__templater->func('property', array('publicNavSticky', ), false)) {
		$__finalCompiled .= '
			min-height: @xf-uix_stickySectionLinkHeight;
			height: @xf-uix_stickySectionLinkHeight;
		';
	}
	$__finalCompiled .= '
		';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered') {
		$__finalCompiled .= '
			.m-pageWidth();
		';
	}
	$__finalCompiled .= '
	}

	.p-navgroup .p-navgroup-link i:after,
	.p-navSearch-trigger i:after {font-size: @xf-uix_iconFont;}
	.p-navgroup-link.p-navgroup-link--user .avatar {
		font-size: @xf-uix_iconSizeLarge;
		.m-avatarSize(@xf-uix_iconSize);
	}
	.p-navSearch-trigger,
	.p-navgroup .p-navgroup-link {padding: @xf-paddingSmall;}
}

.p-sectionLinks-inner
{
	// .m-clearFix();
	// .m-pageWidth();

	@defaultPadding:  max(0px, @xf-pageEdgeSpacer - @xf-publicSubNavPaddingH);
	.m-pageInset(@defaultPadding);
	margin-right: auto;
}


.p-sectionLinks-list
{
	.m-listPlain();

	font-size: 0;

	a
	{
		color: inherit;
	}

	> li
	{
		display: inline-block;
	}

	.m-navElHPadding(@xf-publicSubNavPaddingH);

	.p-navEl
	{
		font-size: @xf-publicSubNav--font-size;
		// padding-top: @xf-paddingMedium;
		// padding-bottom: @xf-paddingMedium;

		&:hover
		{
			.xf-publicSubNavElHover();

			a
			{
				text-decoration: @xf-publicSubNavElHover--text-decoration;
			}
		}
		' . '
	}

	.p-navEl-link,
	.p-navEl-splitTrigger
	{
		padding-top: @xf-publicSubNavPaddingV;
		padding-bottom: @xf-publicSubNavPaddingV;
	}
}

';
	if (($__templater->func('property', array('uix_searchPosition', ), false) != 'tablinks') AND (($__templater->func('property', array('uix_loginTriggerPosition ', ), false) != 'tablinks') AND ($__templater->func('property', array('uix_userTabsPosition ', ), false) != 'tablinks'))) {
		$__finalCompiled .= '

	@media (max-width: @xf-publicNavCollapseWidth) {
		.has-js .p-sectionLinks-inner
		{
			display: none;
		}
	}

';
	}
	return $__finalCompiled;
}
);