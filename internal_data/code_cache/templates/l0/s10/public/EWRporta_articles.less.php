<?php
// FROM HASH: 922f20a425101406ab67738bbfb8c8a7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.porta-article-pager { margin-top: -(@xf-elementSpacer); }
.porta-article-loader { display: none; text-align: center; }

.porta-article-item
{
	margin-bottom: @xf-elementSpacer;
	
	.porta-article-header
	{
		display: block;
		overflow: hidden;
		position: relative;
		
		.porta-header-image
		{
			background-position: center;
			background-size: cover;
			height: @xf-EWRporta_header_height;
		}
		
		.porta-header-medio
		{
			background-position: center;
			background-size: cover;
			height: @xf-EWRporta_medio_height;
		}
	
		.porta-header-text
		{
			position: absolute; bottom: 0; left: 0; right: 0;
			.xf-EWRporta_header_title;

			>span
			{
				position: absolute; bottom: 0; left: 0; right: 0;
				padding: 0 @xf-elementSpacer;
			}
		}

		.porta-header-play
		{
			position: absolute;
			top: 0; bottom: 20px; left: 0; right: 0;

			display: flex;
			align-items: center;
			justify-content: center;
			flex-direction: column;

			.far
			{
				color: @xf-textColorAttention;
				font-size: 100px;
				text-shadow: 1px 1px 0 #000000, 1px -1px 0 #000000, -1px 1px 0 #000000, -1px -1px 0 #000000;
			}
		}
	}

	.porta-article-date
	{
		float: left;
		padding: @xf-paddingLarge 0;
		margin-left: @xf-EWRporta_dateblock_margin;

		.porta-date-block
		{
			.xf-messageUserBlock;
			width: 50px;
			padding: 10px;
			text-align: center;
			text-transform: uppercase;
			white-space: nowrap;

			b { display: block; font-size: 1.5em; }
		}
	}

	.message-inner { display: block; }
	.message-body .bbWrapper { display: inline; }
	.block-header { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
}

.porta-masonry
{
	margin: 0 ~"calc(-@{xf-sidebarSpacer} / 2)";
	
	.porta-article-item
	{
		display: inline-block;
		width: ~"calc(100% / @xf-EWRporta_masonry_columns)";
		
		.porta-article-container
		{
			margin: 0 ~"calc(@xf-sidebarSpacer / 2)";
		}
	}
}

.porta-article-status
{
	display: none;
	
	.porta-article-ellipse
	{
		font-size: 20px;
		position: relative;
		width: 4em;
		height: 1em;
		margin: 10px auto;

		.loader-ellipse-dot
		{
			display: block;
			width: 1em;
			height: 1em;
			border-radius: 0.5em;
			background: @xf-textColor;
			position: absolute;
			animation-duration: 0.5s;
			animation-timing-function: ease;
			animation-iteration-count: infinite;
		}

		.loader-ellipse-dot:nth-child(1),
		.loader-ellipse-dot:nth-child(2) { left: 0; }
		.loader-ellipse-dot:nth-child(3) { left: 1.5em; }
		.loader-ellipse-dot:nth-child(4) { left: 3em; }

		@keyframes reveal { from { transform: scale(0.001); } to { transform: scale(1); } }
		@keyframes slide { to { transform: translateX(1.5em) } }

		.loader-ellipse-dot:nth-child(1) { animation-name: reveal; }
		.loader-ellipse-dot:nth-child(2),
		.loader-ellipse-dot:nth-child(3) { animation-name: slide; }
		.loader-ellipse-dot:nth-child(4) { animation-name: reveal; animation-direction: reverse; }
	}
}

@media (max-width: @xf-responsiveWide)
{
	.porta-masonry
	{
		.porta-article-item
		{
			width: ~"calc(100% / @xf-EWRporta_masonry_wide)";
		}
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.porta-masonry
	{
		margin: 0 (-@xf-pageEdgeSpacer / 2);

		.porta-article-item
		{
			width: ~"calc(100% / @xf-EWRporta_masonry_medium)";
			
			.porta-article-date
			{
				margin-left: -10px;
				
				.porta-date-block
				{
					width: auto;
					padding: 10px 5px;
					transform: rotate(180deg);
					writing-mode: vertical-rl;
					
					b { display: inline; font-size: initial; }
				}
			}
		}
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.porta-masonry
	{
		.porta-article-item
		{
			display: block;
			width: 100%;

			.porta-article-container
			{
				margin: 0;
			}
			
			.porta-article-date
			{
				display: none;
			}
		}
	}
}';
	return $__finalCompiled;
}
);