<?php
// FROM HASH: ef68986cc16f0781f309c9f732c8c97a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// MAIN HEADER ROW

.p-header
{
	background-color: #fff;

	.p-header-logo img {width: 400px;}

	a
	{
		color: inherit;
	}
}

.p-header-inner
{
	.m-pageInset();
	';
	if ($__templater->func('property', array('uix_pageStyle', ), false) != 'fixed') {
		$__finalCompiled .= '
		.m-pageWidth();
	';
	}
	$__finalCompiled .= '
	position: relative;
}

.p-header-content
{
	// padding: @xf-paddingMedium 0;

	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	align-items: center;
	max-width: 100%;
	
	';
	if ($__templater->func('property', array('uix_viewportCenterLogo', ), false) == '100%') {
		$__finalCompiled .= '
		justify-content: center;
		flex-direction: column;
		
		.p-nav-opposite {margin-top: @xf-paddingLarge; margin-left: 0;}
		.p-header-logo {margin-right: 0;}
	';
	}
	$__finalCompiled .= '
	
	@media (max-width: @xf-uix_viewportCenterLogo) {
		justify-content: center;
		flex-direction: column;
		
		.p-nav-opposite {margin-top: @xf-paddingLarge; margin-left: 0;}
		.p-header-logo {margin-right: 0;}
	}
}

.p-header-logo
{
	vertical-align: middle;
	// margin-right: auto;

	a
	{
		color: inherit;
		text-decoration: none;
	}

	&.p-header-logo--text
	{
		font-size: @xf-fontSizeLargest;
	}

	&.p-header-logo--image
	{
		img
		{
			// vertical-align: bottom;
			// max-width: 100%;
			// max-height: 200px;
		}
	}

	.uix_logoIcon {.xf-uix_logoIconStyle();}
}


@media (max-width: @xf-responsiveNarrow)
{
	.p-header-logo
	{
		// max-width: 100px;

		&.p-header-logo--text
		{
			font-size: @xf-fontSizeLarge;
			font-weight: @xf-fontWeightNormal;
			.m-overflowEllipsis();
		}
	}

	.p-navgroup-link
	{
		.p-navGroup--member &
		{
			margin-left: 5px;

			&:first-of-type
			{
				margin-left: 0;
			}
		}

	}
}';
	return $__finalCompiled;
}
);