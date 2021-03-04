<?php
// FROM HASH: 03aa177649c312f2f281bd84a0e1787d
return array(
'macros' => array('user_info' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
		'threadUserId' => '',
		'fallbackName' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<section itemscope itemtype="https://schema.org/Person" class="message-user ' . ((!$__vars['__globals']['uix_postbitCollapsed']) ? 'userExtra--expand' : '') . '">
		<div class="message-avatar ' . (($__vars['xf']['options']['showMessageOnlineStatus'] AND ($__vars['user'] AND $__templater->method($__vars['user'], 'isOnline', array()))) ? 'message-avatar--online' : '') . '">
			<div class="message-avatar-wrapper">
				';
	if ($__templater->func('property', array('uix_postBitAvatarSize', ), false) == 'small') {
		$__finalCompiled .= '
					' . $__templater->func('avatar', array($__vars['user'], 's', false, array(
			'defaultname' => $__vars['fallbackName'],
			'itemprop' => 'image',
		))) . '
				';
	} else if ($__templater->func('property', array('uix_postBitAvatarSize', ), false) == 'medium') {
		$__finalCompiled .= '
					' . $__templater->func('avatar', array($__vars['user'], 'm', false, array(
			'defaultname' => $__vars['fallbackName'],
			'itemprop' => 'image',
		))) . '
				';
	} else if ($__templater->func('property', array('uix_postBitAvatarSize', ), false) == 'large') {
		$__finalCompiled .= '
					' . $__templater->func('avatar', array($__vars['user'], 'l', false, array(
			'defaultname' => $__vars['fallbackName'],
			'itemprop' => 'image',
		))) . '
				';
	}
	$__finalCompiled .= '
				';
	if ($__vars['xf']['options']['showMessageOnlineStatus'] AND ($__vars['user'] AND $__templater->method($__vars['user'], 'isOnline', array()))) {
		$__finalCompiled .= '
					<span class="message-avatar-online" tabindex="0" data-xf-init="tooltip" data-trigger="auto" title="' . $__templater->filter('Online now', array(array('for_attr', array()),), true) . '"></span>
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
		<div class="uix_messagePostBitWrapper">
			<div class="message-userDetails">
				<h4 class="message-name">' . $__templater->func('username_link', array($__vars['user'], true, array(
		'defaultname' => $__vars['fallbackName'],
		'itemprop' => 'name',
	))) . '</h4>
				' . $__templater->func('user_title', array($__vars['user'], true, array(
		'tag' => 'h5',
		'class' => 'message-userTitle',
		'itemprop' => 'jobTitle',
	))) . '
				';
	if ($__vars['xf']['options']['showMessageOnlineStatus'] AND ($__vars['user'] AND ($__templater->method($__vars['user'], 'isOnline', array()) AND ($__templater->func('property', array('uix_onlineIndicator', ), false) == 'userbanner')))) {
		$__finalCompiled .= '
					<div class="userBanner message-userBanner uix_userBanner--online">
						<span class="userBanner-before"></span>
						<strong>' . 'Online now' . '</strong>
						<span class="userBanner-after"></span>
					</div>
				';
	}
	$__finalCompiled .= '
				';
	if (($__vars['user']['user_id'] == $__vars['threadUserId']) AND ($__templater->func('property', array('uix_originalPoster', ), false) != 'none')) {
		$__finalCompiled .= '
					';
		if ($__templater->func('property', array('uix_originalPoster', ), false) == 'userbanner') {
			$__finalCompiled .= '
						<div class="userBanner message-userBanner uix_userBanner--op">
							<span class="userBanner-before"></span>
								<strong>' . 'Original poster' . '</strong>
							<span class="userBanner-after"></span>
						</div>
					';
		} else if ($__templater->func('property', array('uix_originalPoster', ), false) == 'icon') {
			$__finalCompiled .= '
						<div class="uix_originalPoster__icon" data-xf-init="tooltip" title="' . 'Original poster' . '">
							' . $__templater->fontAwesome('fa-user-edit', array(
				'class' => 'mdi mdi-account-edit',
			)) . '
						</div>
					';
		}
		$__finalCompiled .= '
				';
	}
	$__finalCompiled .= '
				' . $__templater->func('user_banners', array($__vars['user'], array(
		'tag' => 'div',
		'class' => 'message-userBanner',
		'itemprop' => 'jobTitle',
	))) . '
				';
	if ($__vars['dateHtml']) {
		$__finalCompiled .= '<div class="message-date">' . $__templater->escape($__vars['dateHtml']) . '</div>';
	}
	$__finalCompiled .= '
			</div>
			';
	if ($__vars['linkHtml']) {
		$__finalCompiled .= '<div class="message-permalink">' . $__templater->escape($__vars['linkHtml']) . '</div>';
	}
	$__finalCompiled .= '
			';
	if ($__vars['user']['user_id']) {
		$__finalCompiled .= '
				';
		$__vars['extras'] = $__templater->func('property', array('messageUserElements', ), false);
		$__finalCompiled .= '
				';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
									';
		if ($__vars['extras']['register_date']) {
			$__compilerTemp1 .= '
										<dl class="pairs pairs--justified">
											';
			if ($__templater->func('property', array('uix_postBitIcons', ), false)) {
				$__compilerTemp1 .= '
												<dt>
													<span data-xf-init="tooltip" title="' . 'Joined' . '">
														' . $__templater->fontAwesome('fa-user', array(
					'class' => ' uix_icon uix_icon--user',
				)) . '
													</span>
												</dt>
											';
			} else {
				$__compilerTemp1 .= '
												<dt>' . 'Joined' . '</dt>
											';
			}
			$__compilerTemp1 .= '
											<dd>' . $__templater->func('date', array($__vars['user']['register_date'], ), true) . '</dd>
										</dl>
									';
		}
		$__compilerTemp1 .= '
									';
		if ($__vars['extras']['message_count']) {
			$__compilerTemp1 .= '
										<dl class="pairs pairs--justified">
											';
			if ($__templater->func('property', array('uix_postBitIcons', ), false)) {
				$__compilerTemp1 .= '
												<dt>
													<span data-xf-init="tooltip" title="' . 'Messages' . '">
														' . $__templater->fontAwesome('fa-comments', array(
					'class' => ' uix_icon uix_icon--messages',
				)) . '
													</span>
												</dt>
											';
			} else {
				$__compilerTemp1 .= '
												<dt>' . 'Messages' . '</dt>
											';
			}
			$__compilerTemp1 .= '
											<dd>' . $__templater->filter($__vars['user']['message_count'], array(array('number', array()),), true) . '</dd>
										</dl>
									';
		}
		$__compilerTemp1 .= '
									';
		if ($__vars['extras']['reaction_score']) {
			$__compilerTemp1 .= '
										';
			if ($__templater->func('is_addon_active', array('ThemeHouse/Reactions', ), false)) {
				$__compilerTemp1 .= '
											<dl class="pairs pairs--justified">
												';
				if ($__templater->func('property', array('uix_postBitIcons', ), false)) {
					$__compilerTemp1 .= '
													<dt>
														<span data-xf-init="tooltip" title="' . 'threact_reactions' . '">
															' . $__templater->fontAwesome('fa-thumbs-up', array(
						'class' => ' uix_icon uix_icon--like',
					)) . '
														</span>
													</dt>
												';
				} else {
					$__compilerTemp1 .= '
													<dt>' . 'threact_reactions' . '</dt>
												';
				}
				$__compilerTemp1 .= '
											<dd>' . $__templater->filter($__templater->method($__vars['user'], 'getReactTotalCount', array()), array(array('number', array()),), true) . '</dd>
											</dl>
										';
			} else {
				$__compilerTemp1 .= '
											<dl class="pairs pairs--justified">
												';
				if ($__templater->func('property', array('uix_postBitIcons', ), false)) {
					$__compilerTemp1 .= '
													<dt>
														<span data-xf-init="tooltip" title="' . 'Likes' . '">
															' . $__templater->fontAwesome('fa-thumbs-up', array(
						'class' => ' uix_icon uix_icon--like',
					)) . '
														</span>
													</dt>
												';
				} else {
					$__compilerTemp1 .= '
													<dt>' . 'Reaction score' . '</dt>
												';
				}
				$__compilerTemp1 .= '
												<dd>' . $__templater->filter($__vars['user']['reaction_score'], array(array('number', array()),), true) . '</dd>
											</dl>
										';
			}
			$__compilerTemp1 .= '
									';
		}
		$__compilerTemp1 .= '
									';
		if ($__vars['extras']['trophy_points'] AND $__vars['xf']['options']['enableTrophies']) {
			$__compilerTemp1 .= '
										<dl class="pairs pairs--justified">
											';
			if ($__templater->func('property', array('uix_postBitIcons', ), false)) {
				$__compilerTemp1 .= '
												<dt>
													<span data-xf-init="tooltip" title="' . 'Points' . '">
														' . $__templater->fontAwesome('fa-trophy', array(
					'class' => ' uix_icon uix_icon--trophy',
				)) . '
													</span>
												</dt>
											';
			} else {
				$__compilerTemp1 .= '
												<dt>' . 'Points' . '</dt>
											';
			}
			$__compilerTemp1 .= '
											<dd>' . $__templater->filter($__vars['user']['trophy_points'], array(array('number', array()),), true) . '</dd>
										</dl>
									';
		}
		$__compilerTemp1 .= '
									';
		if ($__vars['extras']['age'] AND $__vars['user']['Profile']['age']) {
			$__compilerTemp1 .= '
										<dl class="pairs pairs--justified">
											';
			if ($__templater->func('property', array('uix_postBitIcons', ), false)) {
				$__compilerTemp1 .= '
												<dt>
													<span data-xf-init="tooltip" title="' . 'Age' . '">
														' . $__templater->fontAwesome('fa-birthday-cake', array(
					'class' => ' uix_icon uix_icon--birthday',
				)) . '
													</span>
												</dt>
											';
			} else {
				$__compilerTemp1 .= '
												<dt>' . 'Age' . '</dt>
											';
			}
			$__compilerTemp1 .= '
											<dd>' . $__templater->escape($__vars['user']['Profile']['age']) . '</dd>
										</dl>
									';
		}
		$__compilerTemp1 .= '
									';
		if ($__vars['extras']['location'] AND $__vars['user']['Profile']['location']) {
			$__compilerTemp1 .= '
										<dl class="pairs pairs--justified">
											';
			if ($__templater->func('property', array('uix_postBitIcons', ), false)) {
				$__compilerTemp1 .= '
												<dt>
													<span data-xf-init="tooltip" title="' . 'Location' . '">
														' . $__templater->fontAwesome('fa-map-marker', array(
					'class' => ' uix_icon uix_icon--location',
				)) . '
													</span>
												</dt>
											';
			} else {
				$__compilerTemp1 .= '
												<dt>' . 'Location' . '</dt>
											';
			}
			$__compilerTemp1 .= '
											<dd data-xf-init="tooltip" title="' . $__templater->escape($__vars['user']['Profile']['location']) . '">
												';
			if ($__vars['xf']['options']['geoLocationUrl']) {
				$__compilerTemp1 .= '
													<a href="' . $__templater->func('link', array('misc/location-info', '', array('location' => $__vars['user']['Profile']['location'], ), ), true) . '" rel="nofollow noreferrer" target="_blank" class="u-concealed">' . $__templater->escape($__vars['user']['Profile']['location']) . '</a>
												';
			} else {
				$__compilerTemp1 .= '
													' . $__templater->escape($__vars['user']['Profile']['location']) . '
												';
			}
			$__compilerTemp1 .= '
											</dd>
										</dl>
									';
		}
		$__compilerTemp1 .= '
									';
		if ($__vars['extras']['website'] AND $__vars['user']['Profile']['website']) {
			$__compilerTemp1 .= '
										<dl class="pairs pairs--justified">
											';
			if ($__templater->func('property', array('uix_postBitIcons', ), false)) {
				$__compilerTemp1 .= '
												<dt>
													<span data-xf-init="tooltip" title="' . 'Website' . '">
														' . $__templater->fontAwesome('fa-browser', array(
					'class' => ' uix_icon uix_icon--web',
				)) . '
													</span>
												</dt>
											';
			} else {
				$__compilerTemp1 .= '
												<dt>' . 'Website' . '</dt>
											';
			}
			$__compilerTemp1 .= '
											<dd data-xf-init="tooltip" title="' . $__templater->filter($__vars['user']['Profile']['website'], array(array('url', array('host', 'Visit site', )),), true) . '"><a href="' . $__templater->escape($__vars['user']['Profile']['website']) . '" rel="nofollow" target="_blank">' . $__templater->filter($__vars['user']['Profile']['website'], array(array('url', array('host', 'Visit site', )),), true) . '</a></dd>
										</dl>
									';
		}
		$__compilerTemp1 .= '
									';
		if ($__vars['extras']['custom_fields']) {
			$__compilerTemp1 .= '
										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_values', array(
				'type' => 'users',
				'group' => 'personal',
				'set' => $__vars['user']['Profile']['custom_fields'],
				'additionalFilters' => array('message', ),
				'valueClass' => 'pairs pairs--justified',
			), $__vars) . '
										';
			if ($__templater->method($__vars['user'], 'canViewIdentities', array())) {
				$__compilerTemp1 .= '
											' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'users',
					'group' => 'contact',
					'set' => $__vars['user']['Profile']['custom_fields'],
					'additionalFilters' => array('message', ),
					'valueClass' => 'pairs pairs--justified',
				), $__vars) . '
										';
			}
			$__compilerTemp1 .= '
									';
		}
		$__compilerTemp1 .= '
								';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
					';
			if ($__templater->func('property', array('uix_collapseExtraInfo', ), false)) {
				$__finalCompiled .= '
						<div class="thThreads__message-userExtras">
					';
			}
			$__finalCompiled .= '
							<div class="message-userExtras">
								' . $__compilerTemp1 . '
							</div>
						';
			if ($__templater->func('property', array('uix_collapseExtraInfo', ), false)) {
				$__finalCompiled .= '
							</div>
							<div class="thThreads__userExtra--toggle">
								<span class="thThreads__userExtra--trigger" data-xf-click="ththreads-userextra-trigger"></span>
							</div>
						';
			}
			$__finalCompiled .= '
				';
		}
		$__finalCompiled .= '
			';
	}
	$__finalCompiled .= '
		</div>
		<span class="message-userArrow"></span>
	</section>
';
	return $__finalCompiled;
}
),
'user_info_simple' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
		'fallbackName' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<header itemscope itemtype="https://schema.org/Person" class="message-user">
		<meta itemprop="name" content="' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['fallbackName'])) . '">
		<div class="message-avatar">
			<div class="message-avatar-wrapper">
				' . $__templater->func('avatar', array($__vars['user'], 's', false, array(
		'defaultname' => $__vars['fallbackName'],
		'itemprop' => 'image',
	))) . '
			</div>
		</div>
		<span class="message-userArrow"></span>
	</header>
';
	return $__finalCompiled;
}
),
'attachments' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'attachments' => '!',
		'message' => '!',
		'canView' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	if ($__templater->isTraversable($__vars['attachments'])) {
		foreach ($__vars['attachments'] AS $__vars['attachment']) {
			if (!$__templater->method($__vars['message'], 'isAttachmentEmbedded', array($__vars['attachment'], ))) {
				$__compilerTemp1 .= '
						' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
					'attachment' => $__vars['attachment'],
					'canView' => $__vars['canView'],
				), $__vars) . '
					';
			}
		}
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('attachments.less');
		$__finalCompiled .= '
		<section class="message-attachments">
			<h4 class="block-textHeader">' . 'Attachments' . '</h4>
			<ul class="attachmentList">
				' . $__compilerTemp1 . '
			</ul>
		</section>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'signature' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['xf']['visitor']['Option']['content_show_signature'] AND $__vars['user']['Profile']['signature']) {
		$__finalCompiled .= '
		';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
					' . $__templater->func('bb_code', array($__vars['user']['Profile']['signature'], 'user:signature', $__vars['user'], ), true) . '
				';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
			<aside class="message-signature">
				<div class="uix_signatureExpand">' . 'Expand signature' . '</div>
				' . $__compilerTemp1 . '
			</aside>
		';
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);