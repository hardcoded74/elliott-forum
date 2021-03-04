<?php
// FROM HASH: 83dfbc6a6a6c257781d920f0e278a377
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.uix_headerContainer {
	.breadcrumb {
		';
	if ($__templater->func('property', array('uix_pageStyle', ), false) == 'fixed') {
		$__finalCompiled .= '
			.m-pageWidth();
		';
	}
	$__finalCompiled .= '
		
		.pageContent {
			';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'fixed') {
		$__finalCompiled .= '
				.m-pageWidth();
			';
	}
	$__finalCompiled .= '
			
		}
	}
}

.breadcrumb {
	@media (min-width: @xf-responsiveNarrow) {
		.xf-uix_breadcrumbWrapper();
	}
	
	&.p-breadcrumb--bottom {
		.xf-uix_breadcrumbWrapperBottom();
	}
	
	.pageContent {
		display: flex;
		align-items: center;
		
		> *:not(:last-child) {margin-right: @xf-paddingMedium;}
	}
	
	.uix_breadcrumb--opposite {
		margin-left: auto;
		display: inline-flex;
		align-items: center;
		
		> *:not(:last-child) {margin-right: 5px;}
	}
}

.uix_headerContainer .breadcrumb .pageContent {
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'fixed') {
		$__finalCompiled .= '
		.m-pageWidth();
	';
	}
	$__finalCompiled .= '
}

.p-breadcrumbs
{
	.m-listPlain();
	.m-clearFix();

	// margin-bottom: 5px;
	// line-height: 1.5;
	display: flex;
	align-items: center;
	flex-grow: 1;
	@media (min-width: @xf-responsiveNarrow) {
		.xf-uix_breadcrumbStyle();
	}
	
	i {font-size: @xf-uix_iconSize;}

	&.p-breadcrumbs--bottom
	{
		// margin-top: @xf-elementSpacer;
		margin-bottom: 0;
	}

	> li
	{
		float: left;
		margin-right: .5em;
		font-size: @xf-fontSizeSmall;
		display: flex;
		align-items: center;
		font-size: inherit;

		a
		{
			display: inline-block;
			vertical-align: bottom;
			max-width: 300px;
			.m-overflowEllipsis();
			.xf-uix_breadcrumbItem();
		}

		&:after,
		&:before
		{
			.m-faBase();
			// font-size: 90%;
			// color: @xf-textColorMuted;
		}

		&:after
		{
			.m-faContent(@fa-var-angle-right, .36em, ltr);
			.m-faContent(@fa-var-angle-left, .36em, rtl);
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-right',
	), $__vars) . '
			font-size: @xf-uix_iconSize;
			margin-left: .5em;
		}
		
		&:first-child {
			padding-left: 0;
		}

		&:last-child
		{
			margin-right: 0;
			
			&:after {display: none;}

			a
			{
				font-weight: @xf-fontWeightHeavy;
				.xf-uix_breadcrumbItem__active();
				
				
			}
		}
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.p-breadcrumbs > li a
	{
		max-width: 200px;
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.p-breadcrumbs
	{
		> li
		{
			font-size: @xf-fontSizeSmallest;
			display: none;
			padding-left: 0;
			
			a {
				display: inline-flex;
				align-items: center;				
			}

			&:last-child
			{
				// display: block;
				display: flex;
			}

			a
			{
				max-width: 90vw;
				color: inherit !important;
				&:hover {text-decoration: none;}
			}

			&:after
			{
				display: none;
			}

			a:before
			{
				.m-faBase();
				.m-faContent(@fa-var-chevron-left, .72em, ltr);
				.m-faContent(@fa-var-chevron-right, .72em, rtl);
				// margin-right: .5em;
				' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-left',
	), $__vars) . '
				font-size: @xf-uix_iconSizeLarge !important;
				color: inherit;
			}
		}
	}
}';
	return $__finalCompiled;
}
);