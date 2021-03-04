<?php
// FROM HASH: 95a6289f6591d77e95913ccbbe27f148
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.actionBar
{
	.m-clearFix();
}

.actionBar-set
{
	&.actionBar-set--internal
	{
		float: left;
		// margin-left: -3px;

		> .actionBar-action:first-child
		{
			margin-left: 0;
		}
	}

	&.actionBar-set--external
	{
		float: right;
		// margin-right: -3px;

		> .actionBar-action:last-child
		{
			margin-right: 0;
		}
	}
}

.actionBar .actionBar-action
{
	display: inline-block;
	padding: 3px;
	border: 1px solid transparent;
	border-radius: @xf-borderRadiusMedium;
	margin-left: 5px;
	.xf-uix_messageControl();

	&:hover {
		.xf-uix_messageControlHover();
	}

	&:before
	{
		.m-faBase();
		font-size: 12px;
		padding-right: 2px;
	}

	&.actionBar-action--menuTrigger
	{
		display: none;

		&:after
		{
			.m-menuGadget(true);
		}
	}

	&.actionBar-action--inlineMod
	{
		label
		{
			color: @xf-linkColor;
			font-size: 120%;
			// vertical-align: -2px;
		}

		input
		{
			.m-checkboxAligner();
		}
	}

	&.actionBar-action--mq
	{
		&:before { .m-faContent("@{fa-var-plus}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'quote',
	), $__vars) . ' } // plus

		&.is-selected
		{
			background-color: @xf-contentHighlightBg;
			border-color: @xf-borderColorHighlight;

			&:before { .m-faContent("@{fa-var-minus}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'quote',
	), $__vars) . '} // minus
		}
	}

	&.actionBar-action--postLink
	{
		text-decoration: inherit !important;
		color: inherit !important;
	}

	&.actionBar-action--reaction:not(.has-reaction) .reaction-text
	{
		color: inherit;
	}
	&.actionBar-action--reply:before { .m-faContent("@{fa-var-reply}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'reply',
	), $__vars) . '} // reply
	&.actionBar-action--comment:before { .m-faContent("@{fa-var-reply}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'post',
	), $__vars) . '} // reply
	&.actionBar-action--like:before { .m-faContent("@{fa-var-thumbs-up}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'like',
	), $__vars) . '}
	&.actionBar-action--like.unlike:before { .m-faContent("@{fa-var-thumbs-down}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'unlike',
	), $__vars) . '}// thumbs up
	&.actionBar-action--report:before { .m-faContent("@{fa-var-exclamation-circle}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'report',
	), $__vars) . '}
	&.actionBar-action--delete:before { .m-faContent("@{fa-var-trash-o}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'delete',
	), $__vars) . '}
	&.actionBar-action--edit:before { .m-faContent("@{fa-var-edit}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'edit',
	), $__vars) . '}
	&.actionBar-action--ip:before { .m-faContent("@{fa-var-globe}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'ipaddress',
	), $__vars) . '}
	&.actionBar-action--history:before { .m-faContent("@{fa-var-history}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'history',
	), $__vars) . '}
	&.actionBar-action--warn:before { .m-faContent("@{fa-var-warning}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'warn',
	), $__vars) . '}
	&.actionBar-action--spam:before { .m-faContent("@{fa-var-warning}\\20"); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'spam',
	), $__vars) . '}
}

@media (max-width: @xf-responsiveNarrow)
{
	.actionBar .actionBar-action
	{
		&.actionBar-action--menuItem
		{
			display: none !important;
		}

		&.actionBar-action--menuTrigger
		{
			display: inline;
		}
	}
}';
	return $__finalCompiled;
}
);