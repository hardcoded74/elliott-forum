<?php
// FROM HASH: 9ccb2c40369dd91f8664e9036be5e1c6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.label
{

	display: inline-block;
	padding: 1px .35em;
	border: 1px solid transparent;
	border-radius: @xf-borderRadiusMedium;
	font-size: 80%;
	line-height: ((@xf-lineHeightDefault) * .9);
	text-decoration: none;

	&:hover,
	a:hover &
	{
		text-decoration: none;
	}

	&.label--fullSize
	{
		font-size: 100%;
	}

	&.label--small
	{
		font-size: @xf-fontSizeSmall;
	}

	&.label--smallest
	{
		font-size: @xf-fontSizeSmallest;
	}

	// Label variations

	&.label--hidden
	{
		// this has to essentially undo all the adjustments made by .label
		padding: inherit;
		border: none;
		font-size: inherit;
		line-height: inherit;
		text-decoration: inherit;

		&:hover
		{
			text-decoration: underline;
		}
	}

	&.label--subtle
	{
		.m-labelVariation(@xf-textColorMuted, @xf-contentAltBg);
	}

	&.label--primary
	{
		.m-labelVariation(white, @xf-uix_prefixPrimary);
	}

	&.label--accent
	{
		.m-labelVariation(white, @xf-uix_prefixAccent);
	}

	&.label--red { .m-labelVariation(white, @xf-uix_prefixRed); }
	&.label--green { .m-labelVariation(white, @xf-uix_prefixGreen); }
	&.label--olive { .m-labelVariation(white, @xf-uix_prefixOlive); }
	&.label--lightGreen { .m-labelVariation(white, @xf-uix_prefixLightGreen); }
	&.label--blue { .m-labelVariation(black, @xf-uix_prefixBlue); }
	&.label--royalBlue { .m-labelVariation(white, @xf-uix_prefixRoyalBlue); }
	&.label--skyBlue { .m-labelVariation(white, @xf-uix_prefixRedSkyBlue); }
	&.label--gray { .m-labelVariation(white, @xf-uix_prefixGray); }
	&.label--silver { .m-labelVariation(black, @xf-uix_prefixSilver); }
	&.label--yellow { .m-labelVariation(black, @xf-uix_prefixYellow); }
	&.label--orange { .m-labelVariation(black, @xf-uix_prefixOrange); }

        &.label--error { .m-labelVariation(#c84448, #fde9e9, #c84448); }
}

.label-append
{
	display: inline-block;
}

.labelLink,
.labelLink:hover
{
	text-decoration: none;
}';
	return $__finalCompiled;
}
);