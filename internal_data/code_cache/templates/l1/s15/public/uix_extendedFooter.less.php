<?php
// FROM HASH: c0c699741961fc3368c2c417a55c223b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.uix_extendedFooter {
	order: @xf-uix_extendedFooterOrder;
	.xf-uix_extendedFooterStyle();
	';
	if (($__templater->func('property', array('uix_pageStyle', ), false) != 'covered') AND (!$__templater->func('property', array('uix_forceCoverExtendedFooter', ), false))) {
		$__finalCompiled .= '
		.m-pageWidth();
	';
	}
	$__finalCompiled .= '
	
	';
	if ($__templater->func('property', array('uix_hideExtendedFooterMobile', ), false)) {
		$__finalCompiled .= '
	@media (max-width: @xf-responsiveMedium) {
		display: none;
	}
	';
	}
	$__finalCompiled .= '

	.pageContent {
		';
	if (($__templater->func('property', array('uix_pageStyle', ), false) == 'covered') OR $__templater->func('property', array('uix_forceCoverExtendedFooter', ), false)) {
		$__finalCompiled .= '
			.m-pageWidth();
			';
		if ($__templater->func('property', array('uix_pageStyle', ), false) != 'wrapped') {
			$__finalCompiled .= '
				padding:0;
			';
		}
		$__finalCompiled .= '
		';
	}
	$__finalCompiled .= '

	}

	.uix_extendedFooterRow {
		display: flex;
		flex-wrap: wrap;
		margin: calc(-@xf-uix_footerWidgetPadding / 2);
			
			.blockLink {text-transform: capitalize;}

		> .block {
			flex-basis: @xf-uix_footerWidgetWidth;
			padding: calc(@xf-uix_footerWidgetPadding / 2);
			margin: 0;
			flex-grow: 1;
			min-width: 0;
			
			.block-container {
				margin-left: 0;
				margin-right: 0;
				';
	if ($__templater->func('property', array('uix_extendedFooter__whiteText', ), false)) {
		$__finalCompiled .= '
					color: rgba(255,255,255,.7);
				';
	}
	$__finalCompiled .= '
				.xf-uix_footerWidget();
				
				.block-minorHeader {
					';
	if ($__templater->func('property', array('uix_extendedFooter__whiteText', ), false)) {
		$__finalCompiled .= '
						color: rgba(255,255,255,1);
					';
	}
	$__finalCompiled .= '
					.xf-uix_footerWidgetHeader();
			
					&:before {
						';
	if ($__templater->func('property', array('uix_extendedFooter__whiteText', ), false)) {
		$__finalCompiled .= '
							color: rgba(255,255,255,.3);
						';
	}
	$__finalCompiled .= '
					}
			
					a {color: inherit;}
				}
	
				';
	if ($__templater->func('property', array('uix_extendedFooter__whiteText', ), false)) {
		$__finalCompiled .= '
					.contentRow-minor {
						color: rgba(255,255,255,.5);

						time {color: rgba(255,255,255,.7);}
					}
	
					a {
						color: inherit;
					}

					.pairs > dt {color: rgba(255,255,255,.5);}
				';
	}
	$__finalCompiled .= '
				
				.block-body {
					.xf-uix_footerWidgetBody();	
				}
	
				.block-row, .blockLink {
					.xf-uix_footerWidgetRow();
				}
				
				.block-footer {
						';
	if ($__templater->func('property', array('uix_extendedFooter__whiteText', ), false)) {
		$__finalCompiled .= '
							color: inherit;
						';
	}
	$__finalCompiled .= '
					.xf-uix_footerWidgetFooter();	
				}
	
				.blockLink {
					background: none;
					color: inherit;
					border-bottom: 1px solid rgba(255,255,255,.12);
					
					a {display: block;}
				}
				
			}
		}
	}
}';
	return $__finalCompiled;
}
);