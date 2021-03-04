<?php
// FROM HASH: 15d661b7d6fa1558349b9b91e41c22fd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ############################ BLOCK LINKS ##################

.blockLink
{
	display: block;
	padding: (@xf-blockPaddingV / 2) @xf-blockPaddingH;
	.xf-blockLink();

	&.is-selected
	{
		.xf-blockLinkSelected(no-border);
		border-left: @xf-blockLinkSelected--border-width solid @xf-blockLinkSelected--border-color;
		padding-left: (@xf-blockPaddingH - xf-default(@xf-blockLinkSelected--border-width, 0));
	}

	&:hover
	{
		.xf-blockLinkSelected(background);
		text-decoration: inherit;
		color: inherit;
	}
}

.blockLink-desc
{
	display: block;
	color: @xf-textColorMuted;
	font-size: @xf-fontSizeSmaller;
	font-weight: @xf-fontWeightNormal;
}

.blockLinkSplitToggle
{
	display: flex;
	padding: 0;
	text-decoration: none;
	cursor: pointer;

	&.is-selected
	{
		.xf-blockLinkSelected(no-border);
	}

	&:hover
	{
		.xf-blockLinkSelected(background);
		text-decoration: inherit;
	}
}

.blockLinkSplitToggle-link
{
	display: block;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
	text-decoration: none;
	flex-grow: 1;

	&:hover
	{
		text-decoration: none;
	}

	.blockLinkSplitToggle.is-selected &
	{
		border-left: @xf-blockLinkSelected--border-width solid @xf-blockLinkSelected--border-color;
		padding-left: (@xf-blockPaddingH - xf-default(@xf-blockLinkSelected--border-width, 0));
	}
}

.blockLinkSplitToggle-toggle
{
	display: inline-block;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
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
		font-size: 80%;
		.m-faContent(@fa-var-chevron-down, 1em);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-down',
	), $__vars) . '
	}

	&.is-active:after
	{
		.m-faContent(@fa-var-chevron-up, 1em);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'chevron-up',
	), $__vars) . '
	}
}

.blockLink--iconic
{
	i:after
	{
		.m-faBase();
		display: inline-block;
		min-width: 1em;
		position: absolute;
		left: @xf-blockPaddingH;
		top: 8px;
		display: none !important;
	}

	&--started i:after
	{
		.m-faContent(@fa-var-file-alt, .86em);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'file-document',
	), $__vars) . '
	}
	&--contributed i:after
	{
		.m-faContent(@fa-var-comments, 1em);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'comment-multiple',
	), $__vars) . '
	}
	&--watched i:after
	{
		.m-faContent(@fa-var-bookmark, 0.72em);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'bookmark',
	), $__vars) . '
	}
	&--unanswered i:after
	{
		.m-faContent(@fa-var-question-circle, .86em);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'help',
	), $__vars) . '
	}

}

// ################################ FAUX BLOCK LINKS #######################
// concept from https://codepen.io/BPScott/pen/Erwan and http://codepen.io/IschaGast/pen/Qjxpxo
// z-indexes are bumped to have the link sit on top of positioned elements (without z-index)

.fauxBlockLink
{
	position: relative;

	a,
	.fauxBlockLink-link
	{
		position: relative;
		z-index: 2;
	}

	.fauxBlockLink-blockLink
	{
		position: static;

		&:before
		{
			content: \'\';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			z-index: 1;
		}
	}

	&.fauxBlockLink--noHover
	{
		.fauxBlockLink-blockLink:hover
		{
			text-decoration: none;
		}
	}
}';
	return $__finalCompiled;
}
);