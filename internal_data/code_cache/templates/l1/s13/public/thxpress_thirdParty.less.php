<?php
// FROM HASH: 4b41ce6997931a6833a77db195a90cd4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '/********************************
*								*
*			Woocommerce			*
*								*
*******************************/

// Buttons

body .p-pageWrapper .woocommerce #respond input#submit, 
body .p-pageWrapper .woocommerce button.button,
body .p-pageWrapper .woocommerce a.button, 
body .p-pageWrapper .woocommerce button.button, 
body .p-pageWrapper .woocommerce button.button.alt, 
body .p-pageWrapper .woocommerce input.button {
	padding: 0;
	.xf-buttonBase() !important;
	.xf-buttonDefault() !important;
	.xf-buttonPrimary() !important;

	&:disabled, 
	&:disabled[disabled] {
		padding: 0;
		.xf-buttonBase() !important;
		.xf-buttonDisabled() !important;
	}
	
	&:hover {
		.xf-uix_buttonHover();
		.xf-uix_buttonPrimaryHover();
	}
	
	&:active {
		.xf-uix_buttonActive();
		.xf-uix_buttonPrimaryActive();
	}
}

// Tabs

body.woocommerce div.product .woocommerce-tabs ul.tabs {
	// remove woo styling
	background: none;
	border: none;
	margin: 0;

	&:before {display: none;}

	// add xf styling
	padding: 0;
	margin-bottom: @xf-elementSpacer;
	font-weight: @xf-fontWeightNormal;
	.xf-blockBorder();
	.xf-standaloneTab();

	li
	{
		// remove woo styling
		line-height: @xf-lineHeightDefault;
		margin: 0;
		border-radius: 0;
		border: none;
		background: none;

		&:before, &:after {display: none;}

		// add xf styling
		padding: @xf-blockPaddingV @xf-blockPaddingH max(0px, @xf-blockPaddingV - @xf-borderSizeFeature);
		border-bottom: @xf-borderSizeFeature solid transparent;

		a {
			padding: 0;
			font-weight: inherit;
			color: inherit;
		}

		&:hover
		{
			color: @xf-standaloneTabSelected--color;
		}

		&.active
		{
			// remove woo styling
			background: none;
			border: none;

			// add xf styling
			border-bottom: @xf-borderSizeFeature solid transparent;
			.xf-standaloneTabSelected();
		}
	}
}

// Product listing

.woocommerce ul.products li {text-align: center;}

body.woocommerce ul.products, .woocommerce-page ul.products {
	display: flex;
	flex-wrap: wrap;

	li.product {
		.xf-contentBase();
		.xf-blockBorder()
		.xf-uix_blockContainer();
		border-radius: @xf-blockBorderRadius;
		.m-transition(); .m-transitionProperty(border margin); // edgeSpacerRemoval
		padding: @xf-blockPaddingV @xf-blockPaddingH;
		display: flex;
		flex-direction: column;

		.woocommerce-LoopProduct-link {flex-grow: 1;}

		.price {
			font-size: @xf-fontSizeLarger;
			color: @xf-textColorMuted;
			font-weight: @xf-fontWeightHeavy;
			margin: 0;
		}

		.button {margin-top: @xf-paddingMedium;}
	}
}

// Product single

.article-full.product {
	.thxpress_authorBlock {display: none;}

	.summary .price {
		font-size: @xf-fontSizeLargest;
		color: @xf-linkColor;
		margin: 0;
	}
}

// Cart

body .woocommerce {
	.woocommerce-cart-form__contents, table.shop_table {
		border: none;

		.xf-contentBase();
		.xf-blockBorder()
		border-radius: @xf-blockBorderRadius;
		.m-transition(); .m-transitionProperty(border margin); // edgeSpacerRemoval	
.xf-uix_blockContainer();

		thead {
			.xf-blockFilterBar();
		}
	}
}

// Notices

body .woocommerce-info,
body .woocommerce-error,
body .woocommerce-message, {
	.xf-blockBorder();
	.xf-blockBorder();
	.xf-contentBase();
	border: 2px solid @xf-uix_primaryColor;
}

body .woocommerce-error {background: @xf-errorBg; border-color: darken(@xf-errorBg, 35%);}';
	return $__finalCompiled;
}
);