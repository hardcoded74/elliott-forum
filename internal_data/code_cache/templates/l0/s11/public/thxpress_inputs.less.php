<?php
// FROM HASH: c94c6fea6f97e4cad004cffe9b82de2a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ###################################### INPUTS ##########################

@_input-numberWidth: 150px;
@_input-numberNarrowWidth: 90px;
@_input-textColor: xf-default(@xf-input--color, @xf-textColor);
@_input-elementSpacer: @xf-paddingMedium;
@_input-checkBoxSpacer: 1.5em;

.m-inputReadOnly()
{
	color: mix(xf-default(@xf-input--color, @xf-textColor), xf-default(@xf-inputDisabled--color, @xf-textColorMuted));
	background: mix(xf-default(@xf-input--background-color, @xf-contentBg), xf-default(@xf-inputDisabled--background-color, @xf-paletteNeutral1));
}

body .p-pageWrapper .p-body {
	input[type="search"], input[type="text"], input[type="email"], input[type="url"], select, textarea {
		.xf-input();
		display: block;
		width: 100%;
		line-height: @xf-lineHeightDefault;
		text-align: left; // this will be flipped in RTL
		word-wrap: break-word;
		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
		.m-transition();
		.m-placeholder({color: fade(@_input-textColor, 40%); });	

		.m-inputZoomFix();

		&:focus,
		{
			outline: 0;
			.xf-inputFocus();
			.m-placeholder({color: fade(@_input-textColor, 50%); });
		}

		&[readonly],
		&.is-readonly
		{
			.m-inputReadOnly();
		}

		&[disabled]
		{
			.xf-inputDisabled();
		}

		&[type=number]
		{
			text-align: right;
			max-width: @_input-numberWidth;

			&.input--numberNarrow
			{
				width: @_input-numberNarrowWidth;
			}
		}
	}

	textarea
	{
		min-height: 0;
		max-height: 400px;
		max-height: 75vh;
		resize: vertical;

		&.input--fitHeight
		{
			resize: none;

			&.input--fitHeight--short
			{
				max-height: 200px;
				max-height: 35vh;
			}
		}

		&.input--code
		{
			overflow-x: auto;
			-ltr-rtl-text-align: left; // force blocks of code back to left align
		}

		&.input--maxHeight-300px
		{
			max-height: 300px;
		}

		.has-js &[rows="1"][data-single-line]
		{
			overflow: hidden;
			resize: none;
		}
	}

	// this makes select inputs consistent across all browsers and OSes
	select
	{
		padding-right: 1em !important;
		.m-selectGadgetColor(@_input-textColor);
		background-size: 1em !important;
		background-repeat: no-repeat !important;
		-ltr-background-position: 100% !important;
		white-space: nowrap;
		word-wrap: normal;
		-webkit-appearance: none !important;
		-moz-appearance: none !important;
		appearance: none !important;

		overflow-x: hidden; // iOS seems to require this to prevent overflow with long options...
		overflow-y: auto; // ...and Firefox seems to require this to prevent the above from breaking vertical scroll...

		&[disabled]
		{
			.m-selectGadgetColor(xf-default(@xf-inputDisabled--color, @xf-textColor));
		}

		&[size],
		&[multiple]
		{
			background-image: none !important;
			padding-right: xf-default(@xf-input--padding, 5px) !important;
		}
	}
}';
	return $__finalCompiled;
}
);