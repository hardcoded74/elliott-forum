<?php
// FROM HASH: 42cdb6919dbc2dcd0f4c310131cd7403
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ################################### AVATARS #############################

.m-uix_avatarShape() {
	';
	if ($__templater->func('property', array('uix_avatarShape', ), false) == 1) {
		$__finalCompiled .= '
		border-radius: 100%;
	';
	} else if ($__templater->func('property', array('uix_avatarShape', ), false) == 2) {
		$__finalCompiled .= '
		border-radius: 0;
	';
	} else if ($__templater->func('property', array('uix_avatarShape', ), false) == 3) {
		$__finalCompiled .= '
		-webkit-clip-path: polygon(50% 0, 100% 50%, 50% 100%, 0 50%);
		clip-path: polygon(50% 0, 100% 50%, 50% 100%, 0 50%);
	';
	} else if ($__templater->func('property', array('uix_avatarShape', ), false) == 4) {
		$__finalCompiled .= '
		-webkit-clip-path: polygon(50% 0, 100% 38%, 80% 100%, 20% 100%, 0 38%);
		clip-path: polygon(50% 0, 100% 38%, 80% 100%, 20% 100%, 0 38%);
	';
	} else if ($__templater->func('property', array('uix_avatarShape', ), false) == 5) {
		$__finalCompiled .= '
		-webkit-clip-path: polygon(50% 0, 95% 25%, 95% 75%, 50% 100%, 5% 75%, 5% 25%);
		clip-path: polygon(50% 0, 95% 25%, 95% 75%, 50% 100%, 5% 75%, 5% 25%);
	';
	} else if ($__templater->func('property', array('uix_avatarShape', ), false) == 6) {
		$__finalCompiled .= '
		-webkit-clip-path: polygon(0 50%, 15% 15%, 50% 0, 85% 15%, 100% 50%, 85% 85%, 50% 100%, 15% 85%);
		clip-path: polygon(0 50%, 15% 15%, 50% 0, 85% 15%, 100% 50%, 85% 85%, 50% 100%, 15% 85%);
	';
	}
	$__finalCompiled .= '
}

.avatar
{
	display: inline-flex;
	justify-content: center;
	align-items: center;
	border-radius: @xf-avatarBorderRadius;
	vertical-align: top;
	overflow: hidden;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	line-height: 1;
	.m-uix_avatarShape();

	img { background-color: @xf-avatarBg; }

	&.avatar--default
	{
		&.avatar--default--dynamic,
		&.avatar--default--text
		{
			font-family: @xf-avatarDynamicFont;
			font-weight: normal;
			text-align: center;
			text-decoration: none !important;

			// this works with the flex box approach
			line-height: 1;

			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			
		}

		&.avatar--default--text
		{
			color: xf-default(@xf-textColorMuted, black) !important;
			background: mix(xf-default(@xf-textColorMuted, black), xf-default(@xf-avatarBg, white), 25%) !important;

			> span:before { content: ~"\'@{xf-avatarDefaultTextContent}\'"; }
		}

		&.avatar--default--image
		{
			background-color: @xf-avatarBg;
			background-image: url(@xf-avatarDefaultImage);
			background-size: cover;
			> span { display: none; }
		}
	}

	&:hover
	{
		text-decoration: none;
	}

	&.avatar--updateLink
	{
		position: relative;
	}

	&.avatar--separated
	{
		border: 1px solid @xf-avatarBg;
	}

	&.avatar--square
	{
		border-radius: 0;
	}

	&.avatar--xxs
	{
		.m-avatarSize(@avatar-xxs);
	}

	&.avatar--xs
	{
		.m-avatarSize(@avatar-xs);
	}

	&.avatar--s
	{
		.m-avatarSize(@avatar-s);
	}

	&.avatar--m
	{
		.m-avatarSize(@avatar-m);
	}

	&.avatar--l
	{
		.m-avatarSize(@avatar-l);
	}

	&.avatar--o
	{
		.m-avatarSize(@avatar-o);
	}

	img:not(.cropImage)
	{
		.m-hideText;
		display: block;
		border-radius: inherit;
		width: 100%;
		height: 100%;
	}

	&:not(a)
	{
		cursor: inherit;
	}
}

.memberHeader-avatar .avatar {border-radius: @xf-avatarBorderRadius;}

.avatar-update
{
	width: 100%;
	height: 30px;
	bottom: -30px;
	position: absolute;

	.m-hiddenLinks();
	.m-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.3), #000);
	.m-transition();

	padding: @xf-paddingSmall;
	overflow: hidden;

	font-family: @xf-fontFamilyUi;
	font-size: @xf-fontSizeSmaller;
	line-height: @xf-lineHeightDefault;

	display: none;
	align-items: center;
	justify-content: center;

	.avatar--updateLink &
	{
		display: flex;
	}

	.has-touchevents &,
	.avatar:hover &
	{
		bottom: 0;
	}

	a
	{
		text-shadow: 0 0 2px rgba(0, 0, 0, 0.6);
		color: #fff;

		&:hover
		{
			text-decoration: none;
		}
	}
}

.avatarWrapper
{
	display: inline-block;
	position: relative;
}

.avatarWrapper-update
{
	position: absolute;
	top: 50%;
	bottom: 0;
	left: 0;
	right: 0;
	display: flex;
	align-items: flex-end;
	justify-content: center;
	padding-bottom: .8em;
	color: #fff;
	text-decoration: none;
	opacity: 0;
	.m-transition(opacity);

	&:before
	{
		content: \'\';
		position: absolute;
		top: -100%;
		bottom: 0;
		left: 0;
		right: 0;
		.m-borderBottomRadius(xf-default(@xf-avatarBorderRadius, 0));
		.m-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, .9), #000, 60%);
		opacity: .75;
		.m-transition(opacity);
		pointer-events: none;
	}

	span
	{
		// so it sits on top of the BG
		position: relative;
	}

	&:hover
	{
		color: #fff;
		text-decoration: none;

		&:before
		{
			opacity: 1;
		}
	}

	.avatarWrapper:hover &,
	.has-touchevents &
	{
		opacity: 1;
	}

	&.avatarWrapper-update--small,
	.avatar--s + &
	{
		font-size: @xf-fontSizeSmall;
		padding-bottom: .2em;
	}
}';
	return $__finalCompiled;
}
);