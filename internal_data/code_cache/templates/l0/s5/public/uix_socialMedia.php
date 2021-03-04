<?php
// FROM HASH: 5ff11672e67bca29e661a3bee99113f2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('uix_socialMedia.less');
	$__finalCompiled .= '
<ul class="uix_socialMedia">
	';
	if ($__vars['xf']['options']['th_facebookUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Facebook' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_facebookUrl_uix']) . '">
				<i class="mdi mdi-facebook" aria-hidden="true"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_deviantArtUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Deviant Art' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_deviantArtUrl_uix']) . '">
				<i class="mdi mdi-deviantart"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_discordUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Discord URL' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_discordUrl_uix']) . '">
				<i class="mdi mdi-discord"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_flickrUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'option.uix_flickr' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_flickrUrl_uix']) . '">
				<i class="mdi mdi-flickr"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_gitHubUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'GitHub' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_gitHubUrl_uix']) . '">
				<i class="mdi mdi-github-face"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if (($__vars['xf']['versionId'] >= 2010010) AND $__vars['xf']['options']['th_googlePlus_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Google+' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_googlePlus_uix']) . '">
				<i class="mdi mdi-google-plus"></i>
		</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_instagramUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Instagram' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_instagramUrl_uix']) . '">
				<i class="mdi mdi-instagram"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_linkedInUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'LinkedIn' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_linkedInUrl_uix']) . '">
				<i class="mdi mdi-linkedin"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_pinterestUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Pinterest' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_pinterestUrl_uix']) . '">
				<i class="mdi mdi-pinterest"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_redditUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Reddit' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_redditUrl_uix']) . '">
				<i class="mdi mdi-reddit"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_steamUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Steam' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_steamUrl_uix']) . '">
				<i class="mdi mdi-steam"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_tumblrUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Tumblr' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_tumblrUrl_uix']) . '">
				<i class="mdi mdi-tumblr"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_twitchUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Twitch' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_twitchUrl_uix']) . '">
				<i class="mdi mdi-twitch"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_twitterUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Twitter' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_twitterUrl_uix']) . '">
				<i class="mdi mdi-twitter"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_youtubeUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'YouTube' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_youtubeUrl_uix']) . '">
				<i class="mdi mdi-youtube-play"></i>
			</a></li>
	';
	}
	$__finalCompiled .= '
</ul>';
	return $__finalCompiled;
}
);