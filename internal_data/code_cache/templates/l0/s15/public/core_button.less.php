<?php
// FROM HASH: 405fd35548a89e67217f8827b15648a1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ############################ BUTTONS #################

.button,
a.button // needed for specificity over a:link
{
	.m-buttonBase();

	.button-text {display: inline-flex; align-items: center;}

	a
	{
		color: inherit;
		text-decoration: none;
	}

	&:hover,
	&:focus {
		.xf-uix_buttonHover();
	}

	&:active {
		.xf-uix_buttonActive();
	}

	.xf-buttonDefault();
	.m-buttonBlockColorVariationSimple(xf-default(@xf-buttonDefault--background-color, transparent));

	&.button--primary
	{
		.xf-buttonPrimary();
		.m-buttonBlockColorVariationSimple(xf-default(@xf-buttonPrimary--background-color, transparent));

		&:hover,
		&:focus {.xf-uix_buttonPrimaryHover();}

		&:active {.xf-uix_buttonPrimaryActive();}
	}

	&.button--cta
	{
		.xf-buttonCta();
		.m-buttonBlockColorVariationSimple(xf-default(@xf-buttonCta--background-color, transparent));

		&:hover,
		&:focus {.xf-uix_buttonCtaHover();}

		&:active {.xf-uix_buttonCtaActive();}
	}

	&.button--link
	{
		// block colors
		// background: @xf-contentBg;
		// color: @xf-linkColor;
		// .m-buttonBorderColorVariation(@xf-borderColor);
		.xf-uix_buttonSmall();
		.xf-uix_buttonLink();

		&:hover,
		&:focus
		{
			text-decoration: none;
			// background: @xf-contentHighlightBg;
			.xf-uix_buttonHover();
		}

		&:active {
			.xf-uix_buttonActive();
		}
	}
	&.button--plain
	{
		background: none;
		color: @xf-linkColor;
		border: none;

		&:hover,
		&:active,
		&:focus
		{
			text-decoration: none;
			background: none;
		}
	}

	&.button--alt
	{
		// block colors
		background-color: @xf-contentHighlightBg;
		color: @xf-linkColor;
		.m-buttonBorderColorVariation(@xf-paletteColor2);

		&:hover,
		&:active,
		&:focus
		{
			background-color: @xf-paletteColor1;
			color: @xf-linkColor;
		}
	}

	&.button--longText
	{
		.m-overflowEllipsis();
		max-width: 100%;
		display: inline-block;
	}

	&.is-disabled
	{
		.xf-buttonDisabled();
		// .m-buttonBorderColorVariation(xf-default(@xf-buttonDisabled--background-color, transparent));

		&:hover,
		&:active,
		&:focus
		{
			.xf-buttonDisabled();
			// background: xf-default(@xf-buttonDisabled--background-color, transparent) !important;
		}
	}

	&.button--scroll
	{
		// background: fade(xf-default(@xf-buttonDefault--background-color, transparent), 75%);
		.xf-buttonPrimary();
		padding: 5px 8px;
		.m-dropShadow();

		&:hover,
		&:focus {.xf-uix_buttonPrimaryHover();}

		&:active {.xf-uix_buttonPrimaryActive();}
	}

	&.button--normal
	{
		font-size: @xf-fontSizeNormal;
	}

	&.button--small
	{
		font-size: @xf-fontSizeSmall;
		padding: 3px 6px;
		.xf-uix_buttonSmall();
	}

	&.button--smaller
	{
		font-size: @xf-fontSizeSmaller;
		padding: 2px 5px;
	}

	&.button--fullWidth
	{
		display: block;
		width: 100%;
		box-sizing: border-box;
	}

	&.button--wrap
	{
		white-space: normal;
	}

	&.button--icon
	{
		> .button-text:before,
		.button-icon
		{
			.m-faBase();
		}

		> .button-text:before,
		> .fa--xf:before,
		.button-icon
		{
			// font-size: 120%;
			font-size: @xf-uix_iconSize;
			vertical-align: -.1em;
			display: inline-block;
			// margin: -.255em 6px -.255em 0;
			margin-right: .2em;
			// content: \'\';
		}

		> .fa--xf
		{
			// helps fix a button alignment issue (Chrome only)
			line-height: inherit;
		}

		/*
		> .button-text:before
		{
		.m-faBase();
		// font-size: 120%;
		font-size: @xf-uix_iconSize;
		vertical-align: middle;
		display: inline-block;
		// margin: -.255em 6px -.255em 0;
		margin-right: .2em;
	}
		*/

		.button-icon
		{
			height: 1em;
			vertical-align: 0;
		}

		&.button--iconOnly
		{
			> .button-text:before,
			> i.fa--xf:before,
			.button-icon
			{
				margin-left: 0;
				margin-right: 0;
			}
		}

		&.button--padded
		{
			> .button-text:before,
			> i.fa--xf:before,
			.button-icon
			{
				margin-top: 0;
				margin-bottom: 0;
			}
		}

		&--add { 
			.m-buttonIcon(@fa-var-plus-square, 0.79em);  // fa-plus-square
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'plus',
	), $__vars) . '}
		}
		&--import { 
			.m-buttonIcon(@fa-var-upload, 0.93em); // fa-upload
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'import',
	), $__vars) . ' } 
		}
		&--export, &--download { 
			.m-buttonIcon(@fa-var-download, 1.125em); // fa-download
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'export',
	), $__vars) . ' }
		}
		&--redirect	    {
			.m-buttonIcon(@fa-var-external-link, 1.125em);
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'link',
	), $__vars) . '}
		}
		&--edit	{ 
			.m-buttonIcon(@fa-var-pencil, 1.13em); // fa-pencil
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'edit',
	), $__vars) . '} 
		}
		&--save	{ 
			.m-buttonIcon(@fa-var-save, 0.86em); // fa-floppy-o
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'save',
	), $__vars) . ' }
		}
		&--delete { 
			.m-buttonIcon(@fa-var-trash-o, 0.79em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'delete',
	), $__vars) . ' } // fa-trash-o
		}
		&--reply { 
			.m-buttonIcon(@fa-var-mail-reply, 1.13); // fa-reply
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'reply',
	), $__vars) . '} 
		}
		&--quote { 
			.m-buttonIcon(@fa-var-quote-left, 0.93em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'quote',
	), $__vars) . '} // fa-quote-left
		}
		&--purchase	{ 
			.m-buttonIcon(@fa-var-shopping-basket, 1.15em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'shopping-cart',
	), $__vars) . '} // fa-shopping-cart
		}
		&--payment { 
			.m-buttonIcon(@fa-var-credit-card, 1.08em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'payment',
	), $__vars) . '} // fa-credit-card
		}
		&--convert { 
			.m-buttonIcon(@fa-var-flash, 0.5em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'convert',
	), $__vars) . '} // fa-bolt
		}
		&--search { 
			.m-buttonIcon(@fa-var-search, 0.93em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'search',
	), $__vars) . '} // fa-search
		}
		&--sort { 
			.m-buttonIcon(@fa-var-sort, 0.58em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'sort',
	), $__vars) . ' } // fa-sort
		}
		&--upload { 
			.m-buttonIcon(@fa-var-upload, 0.93em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'upload',
	), $__vars) . '} // fa-upload
		}
		&--attach { 
			.m-buttonIcon(@fa-var-paperclip, 0.79em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'attachment',
	), $__vars) . '} // fa-attachment
		}			
		&--login { 
			.m-buttonIcon(@fa-var-lock, 0.65em); 
			.button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'login',
	), $__vars) . '} // fa-lock
		}
		&--rate { 
			.m-buttonIcon(@fa-var-star, 0.93em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'rate',
	), $__vars) . '} // fa-star-half-o
		}
		&--config { 
			.m-buttonIcon(@fa-var-cog, 0.86em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'settings',
	), $__vars) . '} // fa-cog
		}
		&--refresh { 
			.m-buttonIcon(@fa-var-refresh, 0.86em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'refresh',
	), $__vars) . '}
		}
		&--translate { 
			.m-buttonIcon(@fa-var-globe, .86em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'translate',
	), $__vars) . ' }
		}
		&--vote { 
			.m-buttonIcon(@fa-var-check-circle-o, .86em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'poll',
	), $__vars) . '}
		}
		&--result { 
			.m-buttonIcon(@fa-var-bar-chart-o, 1.15em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'graph',
	), $__vars) . '}
		}
		&--history { 
			.m-buttonIcon(@fa-var-history, .86em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'history',
	), $__vars) . '}
		}
		&--cancel  {
			.m-buttonIcon(@fa-var-ban, .86em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'close',
	), $__vars) . ' }
		}
		&--preview { 
			.m-buttonIcon(@fa-var-eye, 1em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'watched',
	), $__vars) . '}
		}
		&--conversation { 
			.m-buttonIcon(@fa-var-comments-o, 1em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'messages',
	), $__vars) . '}
		}
		&--write { 
			.m-buttonIcon(@fa-var-edit, 1em);
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'edit',
	), $__vars) . '}
		}
		&--download	{ 
			.m-buttonIcon(@fa-var-download, .93em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'download',
	), $__vars) . '}
		}
		&--bolt { 
			.m-buttonIcon(@fa-var-bolt, 1em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'comment-alert',
	), $__vars) . ' }
		}
		&--list	{ 
			.m-buttonIcon(@fa-var-list, .93em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'list',
	), $__vars) . '}
		}
		&--confirm { 
			.m-buttonIcon(@fa-var-check, 1em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'check',
	), $__vars) . '}
		}
		&--disable { 
			.m-buttonIcon(@fa-var-power-off); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'disable',
	), $__vars) . '}
		}
		&--markRead { 
			.m-buttonIcon(@fa-var-check-square-o, .93em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'check',
	), $__vars) . '}
		}
		&--notificationsOn { 
			.m-buttonIcon(@fa-var-bell-o, 1em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'alert',
	), $__vars) . '}
		}
		&--notificationsOff { 
			.m-buttonIcon(@fa-var-bell-slash-o, 1.15em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'alert-off',
	), $__vars) . '}
		}
		&--merge { 
			.m-buttonIcon(@fa-var-compress, .86em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'merge',
	), $__vars) . '}
		}
		&--move { 
			.m-buttonIcon(@fa-var-share, 1em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'move',
	), $__vars) . '}
		}
		&--copy { 
			.m-buttonIcon(@fa-var-clone, 1em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'clone',
	), $__vars) . '}
		}
		&--approve, &--unapprove { 
			.m-buttonIcon(@fa-var-shield, .72em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'moderate',
	), $__vars) . '}
		}
		&--delete, &--undelete { 
			.m-buttonIcon(@fa-var-trash-o, .88em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'delete',
	), $__vars) . '}
		}
		&--stick, &--unstick { 
			.m-buttonIcon(@fa-var-thumb-tack, .65em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'sticky',
	), $__vars) . '}
		}
		&--lock { 
			.m-buttonIcon(@fa-var-lock, .88em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'lock',
	), $__vars) . '}
		}
		&--unlock { 
			.m-buttonIcon(@fa-var-unlock, .93em); 
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'unlock',
	), $__vars) . '}
		}
		&--bookmark
		{
			.m-buttonIcon(@fa-var-bookmark);
			> .button-text:before {' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'bookmark',
	), $__vars) . '}

			&.is-bookmarked .button-text:before
			{
				font-weight: @faWeight-solid;
				color: @xf-textColorAttention;
			}
		}		
	}

	&.button--provider
	{
		> .button-text:before,
		.button-icon
		{
			.m-faBase(\'Brands\');
			font-size: 120%;
			vertical-align: middle;
			display: inline-block;
			margin: -4px 6px -4px 0;
		}

		.button-icon
		{
			height: 1em;
			vertical-align: 0;
		}

		&--facebook
		{
			.m-buttonColorVariation(#3B5998, white);
			.m-buttonIcon(@fa-var-facebook, .58em);
			
			.button-text {display: inline;}
		}

		&--twitter
		{
			.m-buttonColorVariation(#1DA1F3, white);
			.m-buttonIcon(@fa-var-twitter, .93em);
		}

		&--google
		{
			.m-buttonColorVariation(white, #444);
			border-color: #e9e9e9;

			> .button-text:before
			{
				display: none;
			}
		}

		&--github
		{
			.m-buttonColorVariation(#666666, white);
			.m-buttonIcon(@fa-var-github, .86em);
		}

		&--linkedin
		{
			.m-buttonColorVariation(#0077b5, white);
			.m-buttonIcon(@fa-var-linkedin, .86em);
		}

		&--microsoft
		{
			.m-buttonColorVariation(#00bcf2, white);
			.m-buttonIcon(@fa-var-windows, .93em);
		}

		&--yahoo
		{
			.m-buttonColorVariation(#410093, white);
			.m-buttonIcon(@fa-var-yahoo, .86em);
		}
	}

	// button-text and button-menu are always children of button--splitTrigger
	// but are defined here for reasons of specificity, as these border colors
	// are overwritten by .m-buttonBorderColorVariation()

	> .button-text { border-right: @xf-borderSize solid transparent; }
	> .button-menu { border-left: @xf-borderSize solid transparent; }

	&.button--splitTrigger
	{
		.m-clearFix();
		padding: 0;
		font-size: 0;
		display: inline-block;

		button.button-text
		{
			background: transparent;
			border: none;
			border-right: @xf-borderSize solid transparent;
			color: inherit;
		}

		> .button-text,
		> .button-menu
		{
			.xf-buttonBase();
			display: inline-block;

			&:hover
			{
				&:after
				{
					opacity: 1;
				}
			}
		}

		> .button-text
		{
			.m-borderRightRadius(0);
		}

		> .button-menu
		{
			.m-borderLeftRadius(0);
			padding-right: xf-default(@xf-buttonBase--padding-right, 0);// * (2/3);
			padding-left: xf-default(@xf-buttonBase--padding-left, 0);// * (2/3);

			&:after
			{
				.m-menuGadget(); // .58em
				opacity: .5;
			}
		}
	}
}

.buttonGroup
{
	display: inline-block;
	vertical-align: top;
	.m-clearFix();

	&.buttonGroup--aligned
	{
		vertical-align: middle;
	}

	> .button
	{
		float: left;

		&:not(:first-child)
		{
			border-left: none;
		}

		&:not(:first-child):not(:last-child)
		{
			border-radius: 0;
		}

		&:first-child:not(:last-child)
		{
			.m-borderRightRadius(0);
		}

		&:last-child:not(:first-child)
		{
			.m-borderLeftRadius(0);
		}
	}

	> .buttonGroup-buttonWrapper
	{
		float: left;

		&:not(:first-child) > .button
		{
			border-left: none;
		}

		&:not(:first-child):not(:last-child) > .button
		{
			border-radius: 0;
		}

		&:first-child:not(:last-child) > .button
		{
			.m-borderRightRadius(0);
		}

		&:last-child:not(:first-child) > .button
		{
			.m-borderLeftRadius(0);
		}
	}
}

.toggleButton
{
	> input
	{
		display: none;
	}

	> span
	{
		.xf-buttonDisabled();
		.m-buttonBorderColorVariation(xf-default(@xf-buttonDisabled--background-color, transparent));
	}

	&.toggleButton--small > span
	{
		font-size: @xf-fontSizeSmaller;
		padding: @xf-paddingSmall;
	}

	> input:checked + span
	{
		.xf-buttonDefault();
		.m-buttonBlockColorVariationSimple(xf-default(@xf-buttonDefault--background-color, transparent));
	}
}

.u-scrollButtons
{
	position: fixed;
	bottom: 30px;
	';
	if ($__templater->func('property', array('uix_fab', ), false) == 'always') {
		$__finalCompiled .= '
	bottom: 100px;
	';
	} else if ($__templater->func('property', array('uix_fab', ), false) == 'mobile') {
		$__finalCompiled .= '
	@media(max-width: ' . ($__templater->func('property', array('uix_fabVw', ), false) - 1) . 'px ) {
		bottom: 100px;
	}
	';
	}
	$__finalCompiled .= '
	';
	if (($__templater->func('property', array('uix_visitorTabsMobile', ), false) == 'tabbar') AND ($__templater->func('property', array('uix_fab', ), false) != 'never')) {
		$__finalCompiled .= '
	@media (max-width: @xf-responsiveNarrow) {
		bottom: ' . (($__templater->func('property', array('paddingLarge', ), false) + 23) + 100) . 'px;
}
';
	}
	$__finalCompiled .= '
right: (@xf-pageEdgeSpacer) / 2;
.has-hiddenscroll &
{
	right: 20px;
}
z-index: @zIndex-9;
.m-transition(opacity; @xf-animationSpeed);
opacity: 0;
display: none;
&.is-transitioning
{
	display: flex;
}
&.is-active
{
	display: flex;
	opacity: 1;
}
.button
{
	font-size: 14px;
	display: flex;
	height: 24px;
	line-height: 24px;
	+ .button
	{
		margin-left: @xf-paddingMedium;
	}
}
}

.block-outer-opposite .button {
	.xf-uix_buttonSmall();
}';
	return $__finalCompiled;
}
);