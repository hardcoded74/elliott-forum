<?php
// FROM HASH: ef3579ca73c413e4716f53ba5a76a4f2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@thmonetize-var-padding-top: ((@xf-thmonetize_userUpgradeShapeWidth - @xf-thmonetize_userUpgradeShapeBorderWidth - 60px) / 2);

.thmonetize_upgrade {
	&--hasShape .formRow {
		dt {
			position: relative;
			overflow: hidden;
		}
		.formRow-labelWrapper:after {
			.m-faBase();
			font-weight: 900;
			font-size: 100px;
			line-height: 100px;
			position: absolute;
			left: -50px;
			opacity: 0.3;
		}
	}

	&--circle {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-circle);
		}
	}
	&--square {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-square);
		}
	}
	&--star {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-star);
		}
	}
	&--certificate {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-certificate);
		}
	}
	&--weight-hanging {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-weight-hanging);
		}
	}
	&--shield {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-shield);
		}
	}
	&--hexagon {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-hexagon);
		}
	}
	&--heart {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-heart);
		}
	}
	&--folder {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-folder);
		}
	}
	&--comment-alt {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-comment-alt);
		}
	}
	&--cloud {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-cloud);
		}
	}
	&--poop {
		.formRow-labelWrapper:after {
			.m-faContent(@fa-var-poop);
		}
	}
	.block-row {
		display: flex;
		align-items: center;
		line-height: 1;
	}
	i {
		color: @xf-linkColor;
		display: inline-block;
		padding-right: @xf-paddingSmall;
	}
}

.overlay .thmonetize_UpgradeOptionsList {
	padding: @xf-paddingMedium;
}

.thmonetize_UpgradeOptionsList {
	display: flex;
	flex-wrap: wrap;
	margin-right: -@xf-paddingMedium;
	margin-bottom: @xf-paddingMedium;

	.thmonetize_upgradeHeader, .block-footer {padding: @xf-paddingLarge;}

	.thmonetize_upgrade {
		overflow: hidden;
	}

	.thmonetize_upgrade--hasColor .block-header {
		border-bottom: 0;
	}

	.thmonetize_upgradeHeader {
		background: @xf-contentBg;
		align-items: center;
		display: flex;
		flex-direction: column;

		&.thmonetize_upgradeHeader--shape {
			margin-bottom: (@xf-thmonetize_userUpgradeShapeWidth / 2);

			.thmonetize_upgradeHeader__price {
				width: @xf-thmonetize_userUpgradeShapeWidth;
				height: @xf-thmonetize_userUpgradeShapeWidth;
				margin-bottom: -(@xf-thmonetize_userUpgradeShapeWidth / 2);
			}
		}

		&--circle, &--square {
			.thmonetize_upgradeHeader__price {
				background-color: @xf-contentBg;
				border: @xf-thmonetize_userUpgradeShapeBorderWidth solid @xf-contentAltBg;
				padding-top: @thmonetize-var-padding-top;
			}
		}
		&--circle {
			.thmonetize_upgradeHeader__price {
				border-radius: 50%;
			}
		}
		&--star, &--certificate, &--poop, &--bread, &--cloud, &--comment-alt, &--folder, &--heart, &--hexagon, &--shield, &--weight-hanging {
			.thmonetize_upgradeHeader__price {
				position: relative;
				padding-top: (@thmonetize-var-padding-top + @xf-thmonetize_userUpgradeShapeBorderWidth);
				padding-bottom: @xf-thmonetize_userUpgradeShapeBorderWidth;
				&:before {
					.m-faBase();
					position: absolute;
					display: inline-block;
					font-size: (@xf-thmonetize_userUpgradeShapeWidth - @xf-thmonetize_userUpgradeShapeBorderWidth);
					line-height: (@xf-thmonetize_userUpgradeShapeWidth - @xf-thmonetize_userUpgradeShapeBorderWidth);
					font-weight: 900;
					top: (@xf-thmonetize_userUpgradeShapeBorderWidth / 2);
					color: @xf-contentBg;
					-webkit-text-stroke: @xf-thmonetize_userUpgradeShapeBorderWidth @xf-contentAltBg;
				}
			}
		}
		&--star {
			.thmonetize_upgrade__length {
				max-width: (@xf-thmonetize_userUpgradeShapeWidth / 2);
			}
			.thmonetize_upgradeHeader__price {
				&:before {
					.m-faContent(@fa-var-star);
				}
			}
		}
		&--certificate {
			.thmonetize_upgradeHeader__price {
				&:before {
					.m-faContent(@fa-var-certificate);
				}
			}
		}
		&--weight-hanging {
			.thmonetize_upgradeHeader__price {
				padding-top: (@thmonetize-var-padding-top + @xf-thmonetize_userUpgradeShapeBorderWidth + @xf-thmonetize_userUpgradeShapeWidth / 10);
				&:before {
					.m-faContent(@fa-var-weight-hanging);
				}
			}
		}
		&--shield {
			.thmonetize_upgradeHeader__price {
				padding-top: (@thmonetize-var-padding-top + @xf-thmonetize_userUpgradeShapeBorderWidth - @xf-thmonetize_userUpgradeShapeWidth / 10);
				&:before {
					.m-faContent(@fa-var-shield);
				}
			}
		}
		&--hexagon {
			.thmonetize_upgradeHeader__price {
				&:before {
					.m-faContent(@fa-var-hexagon);
				}
			}
		}
		&--heart {
			.thmonetize_upgrade__length {
				max-width: (@xf-thmonetize_userUpgradeShapeWidth / 2);
			}
			.thmonetize_upgradeHeader__price {
				padding-top: (@thmonetize-var-padding-top + @xf-thmonetize_userUpgradeShapeBorderWidth - @xf-thmonetize_userUpgradeShapeWidth / 10);
				&:before  {
					.m-faContent(@fa-var-heart);
				}
			}
		}
		&--folder {
			.thmonetize_upgradeHeader__price {
				&:before {
					.m-faContent(@fa-var-folder);
				}
			}
		}
		&--comment-alt {
			.thmonetize_upgradeHeader__price {
				padding-top: (@thmonetize-var-padding-top + @xf-thmonetize_userUpgradeShapeBorderWidth - @xf-thmonetize_userUpgradeShapeWidth / 8);
				&:before {
					.m-faContent(@fa-var-comment-alt);
				}
			}
		}
		&--cloud {
			.thmonetize_upgradeHeader__price {
				padding-top: (@thmonetize-var-padding-top + @xf-thmonetize_userUpgradeShapeBorderWidth + @xf-thmonetize_userUpgradeShapeWidth / 10);
				&:before {
					.m-faContent(@fa-var-cloud);
				}
			}
		}
		&--poop {
			.thmonetize_upgradeHeader__price {
				padding-top: (@thmonetize-var-padding-top + @xf-thmonetize_userUpgradeShapeBorderWidth + @xf-thmonetize_userUpgradeShapeWidth / 6);
				&:before {
					.m-faContent(@fa-var-poop);
				}
			}
		}
	}

	.block-container {
		background: @xf-contentAltBg;
	}

	.thmonetize_UpgradeOption {
		flex-basis: 250px;
		flex-grow: 1;
		padding-right: @xf-paddingMedium;
		display: flex;
		flex-direction: column;
		justify-content: center;

		&--featured {
			.block-container {
				border: 2px solid @xf-linkColor;
			}
		}
	}

	.block-container {
    display: flex;
    flex-direction: column;
		flex-grow: 1;
	}

	.thmonetize_upgradeHeader__price {
		align-items: center;
		display: flex;
		flex-direction: column;
	}

	.thmonetize_upgradeHeader__priceRow {
		display: flex;
		justify-content: center;
		line-height: .75;
	}

	.thmonetize_upgrade__price {
		.xf-thmonetize_userUpgradePrice();
		z-index: 1;
	}

	.thmonetize_upgrade__currency {
		.xf-thmonetize_userUpgradeCurrency();
		z-index: 1;
	}

	.thmonetize_upgrade__occurrence {
		.xf-thmonetize_userUpgradeOccurrence();
		align-self: flex-end;
		z-index: 1;
	}

	.thmonetize_upgrade__length {
		.xf-thmonetize_userUpgradeLength();
		z-index: 1;
		text-align: center;
		line-height: 1em;
		padding-top: 10px;
	}

	.block-body {
		flex-grow: 1;
		padding-bottom: 20px;
	}
}

.thmonetize_UpgradeOptionsList, .thmonetize_UpgradeButtons {
	.block-footer {
		text-align: center;
	}
}

.thmonetize_UpgradePageLinks {
	padding: @xf-paddingMedium;
	.thmonetize_UpgradePageLinks-more {
		float: right;
	}
	a {
		padding: @xf-paddingMedium;
		&:hover {
			text-decoration: none;
		}
	}
}

@media (max-width: @xf-formResponsive) {
	.thmonetize_upgrade--hasShape .formRow {
		.formRow-labelWrapper:after {
			right: @xf-paddingMedium;
			left: unset;
			font-size: 15px;
			line-height: 15px;
		}
	}
}';
	return $__finalCompiled;
}
);