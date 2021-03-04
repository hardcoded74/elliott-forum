<?php
// FROM HASH: 7a2ca385742658f3891cccbfc55f8ac7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.uix_showMegaMenu + .p-sectionLinks {display: none;}

.uix_megaMenu {
	display: none;
	overflow: hidden;
	position: relative;
	background: @xf-contentBg;
	
	.pageContent {
		.m-pageWidth();
	}

	.uix_showMegaMenu & {
		display: block;
	}

	.uix_megaMenu__content {
		display: none;

		&--active {display: block;}
	}

	.uix_megaMenu__title {margin: 0; color: @xf-textColor;}

	.uix_megaMenu__row {
		display: flex;
		flex-wrap: wrap;
		padding: 15px 0;
		margin: 0 -15px;

		.uix_megaMenu__col {
			flex-basis: 250px;
			padding: 15px;
			
			&:not(:first-child) {
				flex-grow: 1;
			}

			.blockLink {
				padding: 5px 0;
				color: @xf-textColorDimmed;
				background: none;
				line-height: 1;

				&:hover {background: none; color: @xf-textColor;}
			}

			.uix_footerLink .blockLink {
				padding: 1px 0;
				margin-bottom: 8px;
			}

			&.uix_megaMenu__col--alt {
				position: relative;
				padding: 15px 45px;
				margin-left: 30px;
				flex-grow: 0;

				&:before {
					content: \'\';
					display: block;
					background: @xf-contentAltBg;
					position: absolute;
					top: -15px;
					bottom: -15px;
					left: 0;
					right: -400px;
				}
			}
		}
	}

	.uix_megaMenu__iconLink {
		display: flex;
		align-items: center;

		i {
			font-size: 16px;
			color: @xf-uix_primaryColor;
			padding-right: 4px;
		}
	}

	.block-minorHeader {
		padding: 0 0 10px;
		border-bottom: 1px solid rgba(0,0,0,.12);
		margin-bottom: 5px;		
	}

	.uix_megaMenu__listLabel {
		text-transform: uppercase;
		color: @xf-textColorMuted;
		font-size: @xf-fontSizeSmall;
		margin-top: 20px;
	}
}';
	return $__finalCompiled;
}
);