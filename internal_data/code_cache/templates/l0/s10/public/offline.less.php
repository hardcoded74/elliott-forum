<?php
// FROM HASH: 284fdd1961935b9301a57a0fe4f24f57
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'html
{
	font: @xf-fontSizeNormal / @xf-lineHeightDefault sans-serif;
	font-family: @xf-fontFamilyUi;
	font-weight: @xf-fontWeightNormal;
	color: @xf-textColor;
	margin: 0;
	padding: 0;
	word-wrap: break-word;
	background-color: @xf-pageBg;
}

body
{
	max-width: @xf-pageWidthMax;
	padding: 0 @xf-pageEdgeSpacer;
	margin: 0 auto;
}

a
{
	.xf-link();

	&:hover
	{
		.xf-linkHover();
	}
}

.p-offline-header
{
	padding: 0;
	font-size: @xf-fontSizeLargest;
	font-weight: @xf-fontWeightNormal;

}

.p-offline-main
{
	margin-bottom: @xf-elementSpacer;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
	.xf-contentBase();
	.xf-blockBorder();
	border-radius: @xf-blockBorderRadius;
	background-image: none;

	.m-clearFix();
}';
	return $__finalCompiled;
}
);