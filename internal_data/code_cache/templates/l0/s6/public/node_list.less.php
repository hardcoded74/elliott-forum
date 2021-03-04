<?php
// FROM HASH: 903984eb0ab68e8118a9c04cf467fbe8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@_nodeList-statsCellBreakpoint: 1000px;

.node
{
	& + .node
	{
		border-top: @xf-borderSize solid @xf-borderColorFaint;
	}
}

.node-body
{
	display: table;
	table-layout: fixed;
	width: 100%;
}

.node-icon
{
	display: table-cell;
	vertical-align: middle;
	text-align: center;
	width: 46px;
	padding: @xf-paddingLarge 0 @xf-paddingLarge @xf-paddingLarge;

	i
	{
		display: block;
		line-height: 1.125;
		font-size: 32px;

		&:before
		{
			.m-faBase();

			color: @xf-nodeIconReadColor;

			.node--unread &
			{
				opacity: 1;
				color: @xf-nodeIconUnreadColor;
			}
		}

		.node--category &:before
		{
			.m-faContent(@fa-var-comments);
		}

		.node--search &::before
		{
			.m-faContent(@fa-var-search);
		}

		.node--page &:before
		{
			.m-faContent(@fa-var-file-alt);
		}

		.node--link &:before
		{
			.m-faContent(@fa-var-link);
		}
	}
}

.node-main
{
	display: table-cell;
	vertical-align: middle;
	padding: @xf-paddingLarge;
}

.node-stats
{
	display: table-cell;
	width: 140px;
	vertical-align: middle;
	text-align: center;
	padding: @xf-paddingLarge 0;

	> dl.pairs.pairs--rows
	{
		width: 50%;
		float: left;
		margin: 0;
		padding: 0 @xf-paddingMedium/2;

		&:first-child
		{
			padding-left: 0;
		}

		&:last-child
		{
			padding-right: 0;
		}
	}

	&.node-stats--single
	{
		width: 100px;

		> dl.pairs.pairs--rows
		{
			width: 100%;
			float: none;
		}
	}

	&.node-stats--triple
	{
		width: 240px;

		> dl.pairs.pairs--rows
		{
			width: 33.333%;
		}
	}

	@media (max-width: @_nodeList-statsCellBreakpoint)
	{
		display: none;
	}
}

@_nodeExtra-avatarSize: 36px;

.node-extra
{
	display: table-cell;
	vertical-align: middle;
	width: 280px;
	padding: @xf-paddingLarge;

	font-size: @xf-fontSizeSmall;
}

.node-extra-row
{
	.m-overflowEllipsis();
	color: @xf-textColorMuted;
}

.node-extra-icon
{
	padding-right: @xf-paddingLarge;
	float: left;

	.avatar
	{
		.m-avatarSize(@_nodeExtra-avatarSize);
	}
}

.node-title
{
	margin: 0;
	padding: 0;
	font-size: @xf-fontSizeLarge;
	font-weight: @xf-fontWeightNormal;

	.node--unread &
	{
		font-weight: @xf-fontWeightHeavy;
	}
}

.node-description
{
	font-size: @xf-fontSizeSmall;
	color: @xf-textColorDimmed;

	&.node-description--tooltip
	{
		.has-js:not(.has-touchevents) &
		{
			display: none;
		}
	}
}

.node-meta
{
	font-size: @xf-fontSizeSmall;
}

.node-statsMeta
{
	display: none;

	@media (max-width: @_nodeList-statsCellBreakpoint)
	{
		display: inline;
	}
}

.node-bonus
{
	font-size: @xf-fontSizeSmall;
	color: @xf-textColorMuted;
	text-align: right;
}

.node-subNodesFlat
{
	font-size: @xf-fontSizeSmall;
	margin-top: .3em;

	.node-subNodesLabel
	{
		display: none;
	}
}

.node-subNodeMenu
{
	display: inline;

	.menuTrigger
	{
		color: @xf-textColorMuted;
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.node-main
	{
		display: block;
		width: auto;

		.node--link &,
		.node--page &
		{
			// #168882: we only display the title for these types
			// so keep these as table-cells for vertical alignment
			display: table-cell;
		}
	}

	.node-extra
	{
		display: block;
		width: auto;
		// this gives an equivalent of medium padding between main and extra, with main still having large
		margin-top: (@xf-paddingMedium - @xf-paddingLarge);
		padding-top: 0;
	}

	.node-extra-row
	{
		display: inline-block;
		vertical-align: top;
		max-width: 100%;
	}

	.node-extra-icon
	{
		display: none;
	}

	.node-description,
	.node-stats,
	.node-subNodesFlat
	{
		display: none;
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.node-subNodeMenu
	{
		display: none;
	}
}

.subNodeLink
{
	&:before,
	.subNodeLink-icon
	{
		display: inline-block;
		width: 1.28571429em;
		margin-right: .3em;
		text-decoration: none;
		text-align: center;

		color: @xf-nodeIconReadColor;
	}

	&:before
	{
		.m-faBase();
	}

	&:hover:before
	{
		&:before,
		.subNodeLink-icon
		{
			text-decoration: none;
		}
	}

	&.subNodeLink--unread
	{
		font-weight: @xf-fontWeightHeavy;

		&:before,
		.subNodeLink-icon
		{
			color: @xf-nodeIconUnreadColor;
		}
	}

	&.subNodeLink--category:before
	{
		.m-faContent(@fa-var-comments);
	}

	&.subNodeLink--page:before
	{
		.m-faContent(@fa-var-file-alt);
	}

	&.subNodeLink--link:before
	{
		.m-faContent(@fa-var-link);
	}

	&.subNodeLink--search::before
	{
		.m-faContent(@fa-var-search);
	}
}

.node-subNodeFlatList
{
	.m-listPlain();
	.m-clearFix();

	> li
	{
		display: inline-block;
		margin-right: 1em;

		&:last-child
		{
			margin-right: 0;
		}
	}

	ol,
	ul,
	.node-subNodes
	{
		display: none;
	}
}

.subNodeMenu
{
	.m-listPlain();

	ol,
	ul
	{
		.m-listPlain();
	}

	.subNodeLink
	{
		display: block;
		padding: @xf-blockPaddingV @xf-blockPaddingH;
		text-decoration: none;
		cursor: pointer;

		&:hover
		{
			text-decoration: none;
			background: @xf-contentHighlightBg;
		}
	}

	li li .subNodeLink { padding-left: 1.5em; }
	li li li .subNodeLink { padding-left: 3em; }
	li li li li .subNodeLink { padding-left: 4.5em; }
	li li li li li .subNodeLink { padding-left: 6em; }
	li li li li li li .subNodeLink { padding-left: 7.5em; }
}';
	if ($__templater->func('property', array('th_enableGrid_nodes', ), false)) {
		$__finalCompiled .= '
	' . $__templater->includeTemplate('th_node_list_grid_nodes.less', $__vars) . '
';
	}
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('th_enableStyling_nodes', ), false)) {
		$__finalCompiled .= '
	' . $__templater->includeTemplate('th_node_list_style_nodes.less', $__vars) . '
';
	}
	$__finalCompiled .= '

' . $__templater->includeTemplate('th_node_list_icons_nodes.less', $__vars);
	return $__finalCompiled;
}
);