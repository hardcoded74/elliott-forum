<?php
// FROM HASH: 0b16118655e9e87c6c177367ec4eb2e4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.has-flexbox .thNodes__nodeList {
    display: flex;
    margin-left: -@xf-th_nodeGutter;
	margin-right: -@xf-th_nodeGutter;
    flex-wrap: wrap;

    > .block {
        padding: 0 @xf-th_nodeGutter;
        width: 100%;
    }

    .thNodes_separator {
        display: none;
    }
}

.has-js .thNodes__nodeList {
    visibility: hidden;
}

.has-js .thNodes__nodeList.thNodes__nodeList--running {
    visibility: visible;
}

.has-flexbox .thNodes__nodeList .block-container {
    background: none;
    border: none;
    box-shadow: none;

    .block-body {
        background: none;
        display: flex;
        margin: -@xf-th_nodeGutter 0;
        flex-wrap: wrap;
    }

    .thNodes__nodeHeader {
        display: flex;
        flex-grow: 1;
    }

    .block-header {
        margin-bottom: @xf-th_nodeGutter;
    }

    .node {
        padding: @xf-th_nodeGutter;
		padding-left: 0;
        flex-basis: 300px;
        display: flex;
        flex-direction: column;
		
		+ .node {border: none;}
		
		&.th_nodes_right {padding-right: 0;}
    }

	.node-stats {display: none;}
	.node-statsMeta {display: inline;}

    .th_nodes--below-lg {

        .node-main {
            flex-grow: 1;
        }

        .node-stats {display: none;}

        .node-extra {
            display: flex;
            width: 100%;
            margin-top: 0;
            padding-top: @xf-paddingLarge;
        }
    }

    .th_nodes--below-md {
        .node-statsMeta {display: inline;}
    }

    .node-body {
        display: flex;
        border-radius: 3px;
        flex-grow: 1;
        flex-wrap: wrap;
        background-color: @xf-contentBg;
		.xf-th_nodeBody();
    }

    .block-footer {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        order: 1;
		align-self: flex-end;

        &:before,
        &:after {display: none;}

        a {
            display: inline-flex;
            align-items: center;

            &:hover {text-decoration: none;}
        }
    }

    .node-footer--more a:before {
        .m-faBase();
        .m-faContent(@fa-var-arrow-right);
        font-size: 18px;
        padding-right: 6px;
    }

    .node-footer--createThread:before {
        .m-faBase();
        .m-faContent(@fa-var-plus-circle, .58em);
        font-size: 18px;
    }

    .th_node--hasBackground {
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

        .block-footer {
            background: rgba(0,0,0,.3);
			// background: none;
            border: none;

            a {color: #fff;}
        }

        .node-subNodeFlatList {
            .subNodeLink:before {color: rgba(255,255,255,.5);}
            a {color: rgba(255,255,255,.7);}
        }
    }

    .th_node--hasBackgroundImage {
        .node-body {
            background-size: cover;
            background-position: center;
			position: relative;
			
			&:before {
				content: \'\';
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background: @xf-th_imageOverlay;
			}

			> * {z-index: 1;}
        }
    }
}';
	return $__finalCompiled;
}
);