<?php
// FROM HASH: 9d5ce6070d56d1e7e2f4a75a4a0323dd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.itemList-itemTypeIcon
{
	&.itemList-itemTypeIcon--image
	{
		&::after
		{
			.m-faContent(@fa-var-image);
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'media',
	), $__vars) . '
		}

		display: none;
	}

	&.itemList-itemTypeIcon--embed
	{
		.m-faBase(\'Brands\');
		&::after
		{
			.m-faContent(@fa-var-youtube);
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'youtube',
	), $__vars) . '
		}
	}

	&.itemList-itemTypeIcon--video
	{
		&::after
		{
			.m-faContent(@fa-var-video);
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'video',
	), $__vars) . '
		}
	}

	&.itemList-itemTypeIcon--audio
	{
		&::after
		{
			.m-faContent(@fa-var-music);
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'music',
	), $__vars) . '
		}
	}

	&.itemList-itemTypeIcon--embed
	{
		&--applemusic
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-apple); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'apple',
	), $__vars) . '}
		}

		&--facebook
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-facebook); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'facebook',
	), $__vars) . '}
		}

		&--flickr
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-flickr); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'video',
	), $__vars) . '}
		}

		&--instagram
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-instagram); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'instagram',
	), $__vars) . '}
		}

		&--pinterest
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-pinterest-square); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'pinterest',
	), $__vars) . '}
		}

		&--reddit
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-reddit-alien); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'reddit',
	), $__vars) . '}
		}

		&--soundcloud
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-soundcloud); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'soundcloud',
	), $__vars) . '}
		}

		&--spotify
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-spotify); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'spotify',
	), $__vars) . '}
		}

		&--tumblr
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-tumblr-square); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'tumblr',
	), $__vars) . '}
		}

		&--twitch
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-twitch); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'twitch',
	), $__vars) . '}
		}

		&--twitter
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-twitter); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'twitter',
	), $__vars) . '}
		}

		&--vimeo
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-vimeo); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'vimeo',
	), $__vars) . '}
		}

		&--youtube
		{
			.m-faBase(\'Brands\');
			&::after { .m-faContent(@fa-var-youtube); ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'youtube',
	), $__vars) . '}
		}
	}
}

' . $__templater->includeTemplate('xfmg_item_list.less', $__vars);
	return $__finalCompiled;
}
);