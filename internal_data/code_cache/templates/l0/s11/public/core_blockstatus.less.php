<?php
// FROM HASH: c0a6257732a9530a6a60964c856a509b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ################################## BLOCK STATUS MESSAGES ##############################

.blockStatus
{
	.xf-contentAltBase();
	.xf-blockBorder();
	border-left: @xf-borderSizeFeature solid @xf-borderColorAttention;
	border-radius: @xf-blockBorderRadius;
	margin: 0;
	padding: @xf-paddingMedium 0;
	font-size: @xf-fontSizeSmall;
	text-align: left;

	//.m-transition(border, margin;); // edgeSpacerRemoval

	> dt
	{
		display: none;
	}

	&.blockStatus--info
	{
		border-left-color: @xf-borderColorFeature;
	}

	&.blockStatus--simple
	{
		.xf-blockBorder();
	}

	&.blockStatus--standalone
	{
		margin-bottom: (@xf-elementSpacer) / 2;
	}
}

.blockStatus-message
{
	display: block;
	padding: 0 @xf-paddingMedium;
	margin: .2em 0 0;

	&:first-of-type
	{
		margin-top: 0;
	}

	&:before
	{
		.m-faBase();
		display: inline-block;
		min-width: .8em;
		color: @xf-textColorAttention;
	}

	&--deleted::before { .m-faContent("@{fa-var-trash}\\20"); }
	&--locked::before { .m-faContent("@{fa-var-lock}\\20"); }
	&--moderated::before { .m-faContent("@{fa-var-shield}\\20"); }
	&--warning:before { .m-faContent("@{fa-var-exclamation-triangle}\\20"); }
	&--ignored:before { .m-faContent("@{fa-var-microphone-slash}\\20"); }

	&--deleted::before { ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'delete',
	), $__vars) . ' }
	&--locked::before { ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'lock',
	), $__vars) . ' }
	&--moderated::before { ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'moderate',
	), $__vars) . ' }
	&--warning:before { ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'warning',
	), $__vars) . ' }
	&--ignored:before { ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'ignore',
	), $__vars) . ' }

}';
	return $__finalCompiled;
}
);