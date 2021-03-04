<?php
// FROM HASH: 5079d49d77b90e2d3e08ad758dd5cb3d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.block-container {
	.th_node--hasBackground {
		
		&.th_node--overwriteTextStyling {
			.node-icon {
				i:before {color: rgba(255,255,255,.5);}
			}
			.node-description {color: rgba(255,255,255,.7);}
			.node-title {color: #fff;}
			.node-title a {color: inherit;}
			.node-extra {color: rgba(255,255,255,.7);}
			.node-extra-row {
				color: inherit;
				.node-extra-title {color: #fff;}
				.username {color: inherit; text-decoration: underline;}
			}

			.node-statsMeta {
				dt {color: rgba(255,255,255,.5);}
				dd {color: rgba(255,255,255,.7);}
			}

			.node-stats {
				dt {color: rgba(255,255,255,.5);}
				dd {color: rgba(255,255,255,.7);}
			}
			
			.node-subNodeMenu .menuTrigger {color: rgba(255,255,255,.7);}
			
			.node-subNodeFlatList {
				.subNodeLink:before {color: rgba(255,255,255,.5);}
				a {color: rgba(255,255,255,.7);}
			}
			
			.block-footer {color: #fff;}
		}

		

        .block-footer {
            background-color: rgba(0,0,0,.1);
			// background: none;
            border: none;
        }
    }

    .th_node--hasBackgroundImage {
        .node-body {
            background-size: cover;
            background-position: center;
			position: relative;
			
			.node-main:before {
				content: \'\';
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background: @xf-th_imageOverlay;
                pointer-events: none;
			}
			
			.block-footer {background: rgba(0,0,0,.4);}

			> * {z-index: 1;}
        }
    }
}';
	return $__finalCompiled;
}
);