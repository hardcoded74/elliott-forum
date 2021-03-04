<?php
// FROM HASH: 8a479dce7768c6e16b4f4f1b2c2d82c9
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
				' . $__templater->fontAwesome('fa-facebook', array(
			'class' => 'fab uix_icon uix_icon--facebook',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_deviantArtUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Deviant Art' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_deviantArtUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-deviantart', array(
			'class' => 'fab mdi mdi-deviantart',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_discordUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Discord URL' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_discordUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-discord', array(
			'class' => 'fab mdi mdi-discord',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_flickrUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'option.uix_flickr' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_flickrUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-flickr', array(
			'class' => 'fab uix_icon uix_icon--flickr',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_gitHubUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'GitHub' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_gitHubUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-github-alt', array(
			'class' => 'fab uix_icon uix_icon--github',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if (($__vars['xf']['versionId'] >= 2010010) AND $__vars['xf']['options']['th_googlePlus_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Google+' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_googlePlus_uix']) . '">
			' . $__templater->fontAwesome('fa-google-plus-g', array(
			'class' => 'fab uix_icon uix_icon--google-plus',
		)) . '
		</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_instagramUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Instagram' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_instagramUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-instagram', array(
			'class' => 'fab uix_icon uix_icon--instagram',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_linkedInUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'LinkedIn' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_linkedInUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-linkedin', array(
			'class' => 'fab uix_icon uix_icon--linkedin',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_pinterestUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Pinterest' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_pinterestUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-pinterest', array(
			'class' => 'fab uix_icon uix_icon--pinterest',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_redditUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Reddit' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_redditUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-reddit', array(
			'class' => 'fab uix_icon uix_icon--reddit',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_steamUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Steam' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_steamUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-steam', array(
			'class' => 'fab uix_icon uix_icon--steam',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_tumblrUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Tumblr' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_tumblrUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-tumblr', array(
			'class' => 'fab uix_icon uix_icon--tumblr',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_twitchUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Twitch' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_twitchUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-twitch', array(
			'class' => 'fab uix_icon uix_icon--twitch',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_twitterUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'Twitter' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_twitterUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-twitter', array(
			'class' => 'fab uix_icon uix_icon--twitter',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['xf']['options']['th_youtubeUrl_uix']) {
		$__finalCompiled .= '
		<li><a data-xf-init="tooltip" title="' . 'YouTube' . '" target="_blank" href="' . $__templater->escape($__vars['xf']['options']['th_youtubeUrl_uix']) . '">
				' . $__templater->fontAwesome('fa-youtube', array(
			'class' => 'fab uix_icon uix_icon--youtube',
		)) . '
			</a></li>
	';
	}
	$__finalCompiled .= '
</ul>';
	return $__finalCompiled;
}
);