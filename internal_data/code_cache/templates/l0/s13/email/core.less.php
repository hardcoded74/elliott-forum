<?php
// FROM HASH: 79ef67254427aa45b2a5e1897b849902
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// NOTE: THIS DOES NOT HAVE ACCESS TO public:setup.less automatically!
// THE LESS HERE SHOULD BE KEPT AS SIMPLE AS POSSIBLE

// UI.X Font Sizes

/* solution for pxs */
@fontSizeNormal: unit(10 * @xf-fontSizeNormal, px);
@fontSizeLargest: unit(10 * @xf-fontSizeLargest, px);
@fontSizeSmall: unit(10 * @xf-fontSizeSmall, px);

/* solution for rems
@fontSizeNormal: (0.625 * @xf-fontSizeNormal);
@fontSizeLargest: (0.625 * @xf-fontSizeLargest);
@fontSizeSmall: (0.625 * @xf-fontSizeSmall);
*/

body
{
	margin: 0;
	padding: 0;
	word-wrap: break-word;
	-ms-text-size-adjust: 100%;
	-webkit-text-size-adjust: 100%;

	background-color: @xf-emailBg;
	font-size: @fontSizeNormal;
	font-family: @xf-emailFont;
	line-height: @xf-lineHeightDefault;
	color: @xf-emailTextColor;
}

table
{
	border-spacing: 0;
	mso-table-lspace: 0pt;
	mso-table-rspace: 0pt;
}

table,
td
{
	border-collapse: collapse;
}

a
{
	color: @xf-emailLinkColor;
	text-decoration: none;
}

#bodyTable
{
	height: 100% !important;
	width: 100% !important;
	margin: 0;
	padding: 0;
	background-color: @xf-emailBg;
}

#bodyTableContainer
{
	background-color: @xf-emailBg;
}

.container
{
	width: 100%;
	max-width: 600px;
}

.header
{
	color: @xf-emailHeaderColor;
	padding: @xf-paddingMedium @xf-paddingLarge;
	border-top-left-radius: @xf-borderRadiusMedium;
	border-top-right-radius: @xf-borderRadiusMedium;
	font-family: @xf-emailFont;
	font-size: @fontSizeLargest;
	line-height: @xf-lineHeightDefault;
}

.header a
{
	color: @xf-emailHeaderColor;
}

.content
{
	background-color: @xf-emailContentBg;
	border-radius: @xf-borderRadiusSmall;
	color: @xf-emailTextColor;
	padding: @xf-paddingLarge;
	font-size: @fontSizeNormal;
	font-family: @xf-emailFont;
	line-height: @xf-lineHeightDefault;
}

.content .bbImage
{
	max-width: 100%;
}

.content > p:first-child
{
	margin-top: 0;
}

.content > p:last-child
{
	margin-bottom: 0;
}

.footer
{
	padding: @xf-paddingMedium @xf-paddingLarge;
	text-align: center;
	color: @xf-emailTextColorMuted;
	font-size: @fontSizeSmall;
	font-family: @xf-emailFont;
	line-height: @xf-lineHeightDefault;
}

.footer a
{
	color: @xf-emailTextColorMuted;
	text-decoration: underline;
}

.footerExtra
{
	margin-top: 1em;
}

h2
{
	font-size: @fontSizeLargest;
	font-weight: normal;
	margin: @xf-paddingLarge 0;
	padding: 0;
}

hr
{
	border: 0;
	border-bottom: @xf-borderSize solid @xf-emailBorderColor;
}

div.message
{
	border-left: @xf-borderSizeFeature solid @xf-borderColorFeature;
	margin: @xf-paddingLarge 0;
	padding: @xf-paddingLarge;
}

div.quote
{
	border-left: @xf-borderSizeFeature solid @xf-borderColorAttention;
	border-top: @xf-borderSize solid @xf-emailBorderColor;
	border-bottom: @xf-borderSize solid @xf-emailBorderColor;
	border-right: @xf-borderSize solid @xf-emailBorderColor;
	background: @xf-emailContentAltBg;
	margin: @xf-paddingLarge 0;
	padding: @xf-paddingLarge;
}

.quote-name
{
	color: @xf-textColorAttention;
	font-size: @fontSizeSmall;
	margin-bottom: @xf-paddingMedium;
}

pre.code
{
	margin: @xf-paddingLarge 0;
	padding: @xf-paddingLarge;
	border-left: @xf-borderSizeFeature solid @xf-borderColorAttention;
	border-top: @xf-borderSize solid @xf-emailBorderColor;
	border-bottom: @xf-borderSize solid @xf-emailBorderColor;
	border-right: @xf-borderSize solid @xf-emailBorderColor;
	background: @xf-emailContentAltBg;
	max-width: 600px;
	overflow: auto;
}

.textLink
{
	color: @xf-emailTextColor;
	text-decoration: none;
}

.linkBar
{
	padding: @xf-paddingMedium;
	background-color: @xf-emailContentAltBg;
	border-top: @xf-borderSize solid @xf-emailBorderColor;
}

.button
{
	display: inline-block;
	padding: 5px 10px;
	background-color: @xf-buttonBg;
	border: none;
	border-radius: @xf-borderRadiusMedium;
	color: @xf-buttonTextColor;
	text-decoration: none;
	.xf-buttonBase();
	.xf-buttonDefault();
	.xf-buttonPrimary();
	font-size: @fontSizeSmall;
}

.buttonFake
{
	display: inline-block;
	padding: 5px 10px;
	font-size: @fontSizeSmall;
}

.minorText,
.unsubscribeLink
{
	font-size: @fontSizeSmall;
	color: @xf-emailTextColorMuted;
}

.minorText a,
.unsubscribeLink a
{
	color: @xf-emailTextColorMuted;
	text-decoration: underline;
}

.unsubscribeLink
{
	margin-top: 1em;
	text-align: center;
}

.mediaPlaceholder,
.spoilerPlaceholder
{
	border-radius: @xf-borderRadiusSmall;
	margin: @xf-paddingMedium 0;
	padding: @xf-paddingMedium;
	font-size: @fontSizeLargest;
	line-height: 3;
	text-align: center;
	border: @xf-borderSize solid @xf-emailBorderColor;
	background-color: @xf-emailContentAltBg;
}

.inlineSpoilerPlaceholder {
	font-size: 80%;
	background-color: @xf-contentAltBg;
	border: @xf-borderSize solid @xf-borderColorLight;
	border-radius: @xf-borderRadiusSmall;
	padding: 1px @xf-paddingSmall;
}';
	return $__finalCompiled;
}
);