<?php
// FROM HASH: acf9d59238cb939dff0e42cd1f654e1b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@xpress_sidebarRowPaddingV: @xf-uix_widgetPadding;
@xpress_sidebarRowPaddingH: @xf-uix_widgetPadding;

/* Sidebar widgets */

.p-body-sidebar {
	.block-xpress {
		.block-container > .block-body:first-child {
			display:none;
			
			& + .block-minorHeader {
				border-top: none;
			}
		}
		.block-container > .block-body:first-child:last-child {display:block;}

		.block-row ul:not(.listInline) {
			.m-listPlain();
			margin: -@xpress_sidebarRowPaddingV -@xpress_sidebarRowPaddingH;

			> li {
				margin: 0;
				padding: @xpress_sidebarRowPaddingV @xpress_sidebarRowPaddingH;
				.m-clearFix();
			}
		}

        .widget_search button .icon {display: none;}

        .block-container {color: @xf-textColorDimmed;}

		&.widget_media_audio .block-row {
			background: #f2f3f4;
			padding: 0;
		}

        #wp-calendar {
            text-align: center;
            width: 100%;
        }
		
		&.widget_tag_cloud .tagcloud .wp-tag-cloud {
			display: flex;
			flex-wrap: wrap;
			margin: -2px;
			
			li {padding: 0;}
			
			.tag-cloud-link {
				display: inline-block;
				max-width: 100%;
				padding: 0 6px 1px;
				margin: 0 0 2px;
				border-radius: @xf-borderRadiusMedium;
				font-size: @xf-fontSizeSmaller;
				.xf-chip();
				margin: 2px;
			}
		}
		
		.search-form {
			display: flex;
			
			.button {
				height: auto;
				line-height: 1;
				padding: 0 4px;
				margin-left: 6px;
				
				.button-text {
					font-size: 0;
					
					&:before {
						font-size: 18px;
						margin: 0;
					}
				}
			}
		}
    }
}';
	return $__finalCompiled;
}
);