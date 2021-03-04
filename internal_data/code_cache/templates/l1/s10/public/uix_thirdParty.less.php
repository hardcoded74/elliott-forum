<?php
// FROM HASH: 072e43693179f049586543c8da8a92f1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// Featured Threads

.message.thfeature {
	.message-body {padding: 0;}	
	.message-attribution {margin-bottom: 0;}	
	
	.message-footer {padding-top: @xf-messagePadding;}
}

// Topics

.th_topics_clearTopics {
	i:before {
		.m-faBase();
		.m-faContent(@fa-var-window-close);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'close',
	), $__vars) . '
	}
}

.topic-filter-item .thTopicAction {

	&--add {
		&:before {
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => '\\f131',
	), $__vars) . '
		}
	}

	&--remove {
		&:before {
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => '\\f12e',
	), $__vars) . '
		}
	}
}

// Reactions

';
	if ($__templater->func('property', array('uix_visitorPanelIcons', ), false)) {
		$__finalCompiled .= '
.reacts_total_text dt {
	font-size: 0;
	&:before {
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => '\\f784',
		), $__vars) . '
		font-size: @xf-fontSizeSmall;
		line-height: inherit;
	}
}
';
	}
	$__finalCompiled .= '

// Nodes

.node-footer--actions {
	.fa.fa-bookmark-o:before {
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => '\\f0c3',
	), $__vars) . '
	}

	.fa.fa-bookmark:before {
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => '\\f0c0',
	), $__vars) . '
	}

	.fa.fa-eye-slash:before {
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => '\\f209',
	), $__vars) . '
	}

	.fa.fa-eye:before {
		' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'watched',
	), $__vars) . '
	}
}

';
	if (!$__templater->func('property', array('th_enableGrid_nodes', ), false)) {
		$__finalCompiled .= '
.uix_nodeList .block-body {box-shadow: @xf-uix_elevation1;}
';
	}
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('th_enableGrid_nodes', ), false)) {
		$__finalCompiled .= '

.node + .node {border: none;}

.uix_nodeList .block-container .node-footer--more a:before {
	.m-faBase();
	.m-faContent(@fa-var-arrow-right);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'arrow-right',
		), $__vars) . '
	font-size: @xf-uix_iconSize;
}

.uix_nodeList .block-container .node-footer--createThread:before {
	.m-faBase();
	.m-faContent(@fa-var-comment-alt-plus);
	' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'new-thread',
		), $__vars) . '
	font-size: @xf-uix_iconSize;
}

.thNodes__nodeList.block .block-container .th_nodes--below-lg .node-extra {padding-top: 0;}

.thNodes__nodeList.block .block-container .node-body {
	border: none;
	box-shadow: @xf-uix_elevation1;
}

html .thNodes__nodeList .block-container {
	.node-footer--more a:before {
		.m-faBase();
		.m-faContent(@fa-var-arrow-right);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'arrow-right',
		), $__vars) . '
		font-size: 18px;
	}

	.node-footer--createThread:before {
		.m-faBase();
		.m-faContent(@fa-var-plus);
		' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'plus',
		), $__vars) . '
		font-size: 18px;
	}
}

';
	}
	$__finalCompiled .= '

// XenPorta

.porta-article-item .block-body.message-inner {display: flex;}

.porta-articles-above-full {margin-bottom: @xf-elementSpacer;}

// resource manager

.resourceBody .actionBar {
	padding: 0;
	margin: 0;
}

.resourceBody-main .bbWrapper {
	margin-bottom: @xf-messagePadding;
}

// post comments

.block--messages .message .thpostcomments_commentsContainer .message {

	.message-actionBar {
		padding-top: 0;
		border-top: 0;
	}

	.message-attribution {
		padding-top: 0;
		padding-bottom: @xf-paddingSmall;
	}
}';
	return $__finalCompiled;
}
);