<?php
// FROM HASH: 0542d30d6e492152d8d3b148bb616b56
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.xfmgInfoBlock-title
{
	margin-bottom: @xf-paddingLarge;
}

.xfmgInfoBlock-description
{
	margin-bottom: @xf-paddingLarge;

	.bbCodeBlock
	{
		font-size: @xf-fontSizeSmall;
		font-style: italic;
	}
}

.xfmgInfoBlock-title + .actionBar
{
	margin-top: -@xf-paddingLarge;
}

.xfmgInfoBlock .actionBar-set
{
	font-size: @xf-fontSizeSmall;
	// margin-top: @xf-paddingLarge;
}

.columnContainer
{
	display: flex;
}

.columnContainer-comments
{
	margin-right: @xf-paddingLarge;
	flex: 0 70%;
	min-width: 0;
}

.columnContainer-sidebar
{
	flex: 0 30%;
	min-width: 0;
}

@media (max-width: @xf-responsiveEdgeSpacerRemoval)
{
	.columnContainer
	{
		.columnContainer-sidebar
		{
			display: block;
			margin-left: 0;
			margin-right: 0;

			> *
			{
				margin-left: 0;
				margin-right: 0;
				min-width: 0;
			}
		}
	}
}

@_columnSidebarWidth: 350px;

@media (max-width: @xf-responsiveWide)
{
	.columnContainer
	{
		display: block;
	}

	.columnContainer-comments
	{
		margin-right: 0;
	}

	.columnContainer-sidebar
	{
		display: flex;
		flex-wrap: wrap;
		align-items: stretch;
		margin: (@xf-elementSpacer) -((@xf-pageEdgeSpacer) / 2) -(@xf-elementSpacer);
		width: auto;

		> *
		{
			margin: 0 ((@xf-pageEdgeSpacer) / 2) @xf-elementSpacer;
			min-width: @_columnSidebarWidth;
			flex: 1 1 @_columnSidebarWidth;

			&:last-child
			{
				margin-bottom: @xf-elementSpacer;
			}
		}

		// add an invisible block to ensure that the last row has the correct widths
		&:after
		{
			display: block;
			content: \'\';
			height: 0;
			margin: 0 ((@xf-pageEdgeSpacer) / 2);
			min-width: @_columnSidebarWidth;
			flex: 1 1 @_columnSidebarWidth;
		}

		.block-container
		{
			display: flex;
			flex-direction: column;
			height: 100%;

			.block-footer
			{
				margin-top: auto;
			}
		}
	}
}

' . $__templater->includeTemplate('bb_code.less', $__vars);
	return $__finalCompiled;
}
);