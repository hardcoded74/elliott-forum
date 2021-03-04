<?php
// FROM HASH: 57c8cf44227a065da4fe4beaf739bae7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ################################ CATEGORY LIST #######################

@_categoryListTogglerWidth: 1em;
@_categoryListTogglerPaddingH: (@xf-blockPaddingH / 2);

.categoryList
{
	display: none;
	.m-listPlain();

	&.is-active
	{
		display: block;
	}
}

.categoryList-item
{
	padding: 0;
	text-decoration: none;
	font-size: @xf-fontSizeNormal;

	&.categoryList-item--small
	{
		font-size: @xf-fontSizeSmall;
	}

	.categoryList
	{
		padding-left: @xf-paddingLarge;
	}
}

.categoryList-itemDesc
{
	display: block;
	font-size: @xf-fontSizeSmaller;
	font-weight: @xf-fontWeightNormal;
	color: @xf-textColorMuted;
	margin-top: -@xf-blockPaddingV;

	.m-overflowEllipsis();
}

.categoryList-header
{
	padding: @xf-blockPaddingV 0;
	margin: 0;
	color: @xf-textColorFeature;
	text-decoration: none;
	font-weight: @xf-fontWeightHeavy;

	&.categoryList-header--muted
	{
		color: @xf-textColorMuted;
	}

	.m-clearFix();
	.m-hiddenLinks();
}

.categoryList-itemRow
{
	display: flex;
	min-width: 0;
}

.categoryList-link
{
	display: block;
	flex-grow: 1;
	padding: (@xf-blockPaddingV / 2) @xf-blockPaddingH;
	text-decoration: none;

	.m-overflowEllipsis();

	&:hover
	{
		text-decoration: none;
	}

	&.is-selected
	{
		font-weight: @xf-fontWeightHeavy;
	}

	.categoryList-toggler + &,
	.categoryList-togglerSpacer + &
	{
		padding-left: 0;
	}
}

.categoryList-label
{
	margin-left: auto;
	align-self: center;
	padding-right: @_categoryListTogglerPaddingH;
}

.categoryList-toggler
{
	display: inline-block;
	padding: @xf-blockPaddingV @_categoryListTogglerPaddingH;
	text-decoration: none;
	flex-grow: 0;
	line-height: 1;

	&:hover
	{
		text-decoration: none;
	}

	&:after
	{
		.m-faBase();
		// font-size: 80%;
		.m-faContent(@fa-var-chevron-down, @_categoryListTogglerWidth);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-down',
	), $__vars) . '
	}

	&.is-active:after
	{
		.m-faContent(@fa-var-chevron-up, @_categoryListTogglerWidth);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-up',
	), $__vars) . '
	}
}

.categoryList-togglerSpacer
{
	display: inline-block;
	visibility: hidden;
	padding: (@xf-blockPaddingV / 2) (@xf-blockPaddingH / 2);

	&:after
	{
		.m-faBase();
		// font-size: 80%;
		.m-faContent(@fa-var-chevron-down, @_categoryListTogglerWidth);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-down',
	), $__vars) . '
	}
}';
	return $__finalCompiled;
}
);