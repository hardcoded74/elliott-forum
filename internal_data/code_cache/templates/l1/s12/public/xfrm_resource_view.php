<?php
// FROM HASH: d058d6972be30d373bd5d94c9182dc4e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('resource', $__vars['resource'], 'escaped', ), true) . $__templater->escape($__vars['resource']['title']));
	$__finalCompiled .= '

';
	$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['description']['message'], 250, array('stripBbCode' => true, ), ), false);
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['descSnippet'],
		'shareUrl' => $__templater->func('link', array('canonical:resources', $__vars['resource'], ), false),
		'canonicalUrl' => $__templater->func('link', array('canonical:resources', $__vars['resource'], ), false),
	), $__vars) . '


';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['resource'], 'isVersioned', array())) {
		$__compilerTemp1 .= '
			"version": "' . $__templater->filter($__vars['resource']['CurrentVersion']['version_string'], array(array('escape', array('json', )),), true) . '",
		';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['xfrmAllowIcons'] AND $__vars['resource']['icon_date']) {
		$__compilerTemp2 .= '
			"thumbnailUrl": "' . $__templater->filter($__templater->method($__vars['resource'], 'getIconUrl', array('s', true, )), array(array('escape', array('json', )),), true) . '",
		';
	}
	$__compilerTemp3 = '';
	if ($__vars['resource']['rating_count']) {
		$__compilerTemp3 .= '"aggregateRating": {
			"@type": "AggregateRating",
			"ratingCount": "' . $__templater->filter($__vars['resource']['rating_count'], array(array('escape', array('json', )),), true) . '",
			"ratingValue": "' . $__templater->filter($__vars['resource']['rating_avg'], array(array('escape', array('json', )),), true) . '"
		},';
	}
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['resource'], 'hasViewableDiscussion', array())) {
		$__compilerTemp4 .= '
			"discussionUrl": "' . $__templater->filter($__templater->func('link', array('canonical:threads', $__vars['resource']['Discussion'], ), false), array(array('escape', array('json', )),), true) . '",
		';
	}
	$__templater->setPageParam('ldJsonHtml', '
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "CreativeWork",
		"@id": "' . $__templater->filter($__templater->func('link', array('canonical:resources', $__vars['resource'], ), false), array(array('escape', array('json', )),), true) . '",
		"name": "' . $__templater->filter($__vars['resource']['title'], array(array('escape', array('json', )),), true) . '",
		"headline": "' . $__templater->filter($__vars['resource']['title'], array(array('escape', array('json', )),), true) . '",
		"alternativeHeadline": "' . $__templater->filter($__vars['resource']['tag_line'], array(array('escape', array('json', )),), true) . '",
		"description": "' . $__templater->filter($__vars['descSnippet'], array(array('escape', array('json', )),), true) . '",
		' . $__compilerTemp1 . '
		' . $__compilerTemp2 . '
		"dateCreated": "' . $__templater->filter($__templater->func('date', array($__vars['resource']['resource_date'], 'c', ), false), array(array('escape', array('json', )),), true) . '",
		"dateModified": "' . $__templater->filter($__templater->func('date', array($__vars['resource']['last_update'], 'c', ), false), array(array('escape', array('json', )),), true) . '",
		' . $__compilerTemp3 . '
		' . $__compilerTemp4 . '
		"author": {
			"@type": "Person",
			"name": "' . $__templater->filter(($__vars['resource']['User'] ? $__vars['resource']['User']['username'] : $__vars['resource']['username']), array(array('escape', array('json', )),), true) . '"
		}
	}
	</script>
');
	$__finalCompiled .= '

';
	if ($__vars['iconError']) {
		$__finalCompiled .= '
	<div class="blockMessage blockMessage--error">' . 'xfrm_new_icon_could_not_be_applied_try_later' . '</div>
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp5 = $__vars;
	$__compilerTemp5['pageSelected'] = 'overview';
	$__templater->wrapTemplate('xfrm_resource_wrapper', $__compilerTemp5);
	$__finalCompiled .= '

' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => $__templater->method($__vars['resource'], 'canViewUpdateImages', array()),
	), $__vars) . '

<div class="block">
	';
	$__compilerTemp6 = '';
	$__compilerTemp6 .= '
				' . $__templater->callMacro('xfrm_resource_wrapper_macros', 'action_buttons', array(
		'resource' => $__vars['resource'],
	), $__vars) . '
			';
	if (strlen(trim($__compilerTemp6)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer">
			<div class="block-outer-opposite">
			' . $__compilerTemp6 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
	<div class="block-container">
		<div class="block-body lbContainer js-resourceBody"
			data-xf-init="lightbox"
			data-lb-id="resource-' . $__templater->escape($__vars['resource']['resource_id']) . '"
			data-lb-caption-desc="' . ($__vars['resource']['User'] ? $__templater->escape($__vars['resource']['User']['username']) : $__templater->escape($__vars['resource']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['resource']['resource_date'], ), true) . '">

			<div class="resourceBody">
				<article class="resourceBody-main js-lbContainer">
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'resources',
		'group' => 'above_info',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['resource']['custom_fields'],
		'wrapperClass' => 'resourceBody-fields resourceBody-fields--before',
	), $__vars) . '

					';
	if ($__vars['trimmedDescription']) {
		$__finalCompiled .= '
						' . $__templater->func('bb_code', array($__vars['trimmedDescription'], 'resource_update', $__vars['description'], ), true) . '

						<div class="block-rowMessage block-rowMessage--important">
							' . 'xfrm_do_not_have_permission_to_view_full_content_of_this_resource' . '
							';
		if (!$__vars['xf']['visitor']['user_id']) {
			$__finalCompiled .= '
								<a href="' . $__templater->func('link', array('login', ), true) . '" data-xf-click="overlay">' . 'Log in or register now.' . '</a>
							';
		}
		$__finalCompiled .= '
						</div>
					';
	} else {
		$__finalCompiled .= '
						' . $__templater->func('bb_code', array($__vars['description']['message'], 'resource_update', $__vars['description'], ), true) . '
					';
	}
	$__finalCompiled .= '

					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'resources',
		'group' => 'below_info',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['resource']['custom_fields'],
		'wrapperClass' => 'resourceBody-fields resourceBody-fields--after',
	), $__vars) . '

					';
	if ($__vars['description']['attach_count']) {
		$__finalCompiled .= '
						';
		$__compilerTemp7 = '';
		$__compilerTemp7 .= '
									';
		if ($__templater->isTraversable($__vars['description']['Attachments'])) {
			foreach ($__vars['description']['Attachments'] AS $__vars['attachment']) {
				if (!$__templater->method($__vars['description'], 'isAttachmentEmbedded', array($__vars['attachment'], ))) {
					$__compilerTemp7 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['resource'], 'canViewUpdateImages', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp7 .= '
								';
		if (strlen(trim($__compilerTemp7)) > 0) {
			$__finalCompiled .= '
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList resourceBody-attachments">
								' . $__compilerTemp7 . '
							</ul>
						';
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '

					';
	$__compilerTemp8 = '';
	$__compilerTemp8 .= '
								';
	$__compilerTemp9 = '';
	$__compilerTemp9 .= '
										' . $__templater->func('react', array(array(
		'content' => $__vars['description'],
		'link' => 'resources/update/react',
		'list' => '< .js-resourceBody | .js-reactionsList',
	))) . '
									';
	if (strlen(trim($__compilerTemp9)) > 0) {
		$__compilerTemp8 .= '
									<div class="actionBar-set actionBar-set--external">
									' . $__compilerTemp9 . '
									</div>
								';
	}
	$__compilerTemp8 .= '

								';
	$__compilerTemp10 = '';
	$__compilerTemp10 .= '
										';
	if ($__templater->method($__vars['description'], 'canReport', array())) {
		$__compilerTemp10 .= '
											<a href="' . $__templater->func('link', array('resources/update/report', $__vars['description'], ), true) . '"
												class="actionBar-action actionBar-action--report" data-xf-click="overlay">' . 'Report' . '</a>
										';
	}
	$__compilerTemp10 .= '

										';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp10 .= '
										';
	if ($__templater->method($__vars['resource'], 'canEdit', array())) {
		$__compilerTemp10 .= '
											<a href="' . $__templater->func('link', array('resources/edit', $__vars['resource'], ), true) . '"
												class="actionBar-action actionBar-action--edit actionBar-action--menuItem">' . 'Edit' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
										';
	}
	$__compilerTemp10 .= '
										';
	if ($__templater->method($__vars['description'], 'canDelete', array('soft', ))) {
		$__compilerTemp10 .= '
											<a href="' . $__templater->func('link', array('resources/delete', $__vars['description'], ), true) . '"
												class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
												data-xf-click="overlay">' . 'Delete' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
										';
	}
	$__compilerTemp10 .= '
										';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['description']['ip_id']) {
		$__compilerTemp10 .= '
											<a href="' . $__templater->func('link', array('resources/update/ip', $__vars['description'], ), true) . '"
												class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
												data-xf-click="overlay">' . 'IP' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
										';
	}
	$__compilerTemp10 .= '
										';
	if ($__templater->method($__vars['description'], 'canWarn', array())) {
		$__compilerTemp10 .= '
											<a href="' . $__templater->func('link', array('resources/update/warn', $__vars['description'], ), true) . '"
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
										';
	} else if ($__vars['description']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp10 .= '
											<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['description']['warning_id'], ), ), true) . '"
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
												data-xf-click="overlay">' . 'View warning' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
										';
	}
	$__compilerTemp10 .= '

										';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp10 .= '
											<a class="actionBar-action actionBar-action--menuTrigger"
												data-xf-click="menu"
												title="' . 'More options' . '"
												role="button"
												tabindex="0"
												aria-expanded="false"
												aria-haspopup="true">&#8226;&#8226;&#8226;</a>

											<div class="menu" data-menu="menu" aria-hidden="true" data-menu-builder="actionBar">
												<div class="menu-content">
													<h4 class="menu-header">' . 'More options' . '</h4>
													<div class="js-menuBuilderTarget"></div>
												</div>
											</div>
										';
	}
	$__compilerTemp10 .= '
									';
	if (strlen(trim($__compilerTemp10)) > 0) {
		$__compilerTemp8 .= '
									<div class="actionBar-set actionBar-set--internal">
									' . $__compilerTemp10 . '
									</div>
								';
	}
	$__compilerTemp8 .= '
							';
	if (strlen(trim($__compilerTemp8)) > 0) {
		$__finalCompiled .= '
						<div class="actionBar">
							' . $__compilerTemp8 . '
						</div>
					';
	}
	$__finalCompiled .= '

					<div class="reactionsBar js-reactionsList ' . ($__vars['description']['reactions'] ? 'is-active' : '') . '">
						' . $__templater->func('reactions', array($__vars['description'], 'resources/update/reactions', array())) . '
					</div>

					<div class="js-historyTarget toggleTarget" data-href="trigger-href"></div>
				</article>

				<div class="resourceBody-sidebar">
					<div class="resourceSidebarGroup">
						<dl class="pairs pairs--justified">
							<dt>' . 'Author' . '</dt>
							<dd>' . $__templater->func('username_link', array($__vars['resource']['User'], false, array(
		'defaultname' => $__vars['resource']['username'],
	))) . '</dd>
						</dl>
						';
	if ($__templater->method($__vars['resource'], 'isDownloadable', array())) {
		$__finalCompiled .= '
							<dl class="pairs pairs--justified">
								<dt>' . 'xfrm_downloads' . '</dt>
								<dd>' . $__templater->filter($__vars['resource']['download_count'], array(array('number', array()),), true) . '</dd>
							</dl>
						';
	}
	$__finalCompiled .= '
						<dl class="pairs pairs--justified">
							<dt>' . 'Views' . '</dt>
							<dd>' . $__templater->filter($__templater->func('max', array($__vars['resource']['view_count'], $__vars['resource']['download_count'], 1, ), false), array(array('number', array()),), true) . '</dd>
						</dl>
						<dl class="pairs pairs--justified">
							<dt>' . 'xfrm_first_release' . '</dt>
							<dd>' . $__templater->func('date_dynamic', array($__vars['resource']['resource_date'], array(
	))) . '</dd>
						</dl>
						<dl class="pairs pairs--justified">
							<dt>' . 'xfrm_last_update' . '</dt>
							<dd>' . $__templater->func('date_dynamic', array($__vars['resource']['last_update'], array(
	))) . '</dd>
						</dl>
						<dl class="pairs pairs--justified">
							<dt>' . 'Rating' . '</dt>
							<dd>
								' . $__templater->callMacro('rating_macros', 'stars_text', array(
		'rating' => $__vars['resource']['rating_avg'],
		'count' => $__vars['resource']['rating_count'],
		'rowClass' => 'ratingStarsRow--textBlock',
	), $__vars) . '
							</dd>
						</dl>
					</div>

					';
	$__compilerTemp11 = '';
	$__compilerTemp11 .= '
								';
	if ($__templater->method($__vars['resource'], 'hasViewableDiscussion', array())) {
		$__compilerTemp11 .= '
									' . $__templater->button('xfrm_join_discussion', array(
			'href' => $__templater->func('link', array('threads', $__vars['resource']['Discussion'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp11 .= '

								';
	if ($__vars['resource']['external_url']) {
		$__compilerTemp11 .= '
									' . $__templater->button('xfrm_more_information', array(
			'href' => $__vars['resource']['external_url'],
			'class' => 'button--link button--fullWidth',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp11 .= '

								';
	if ($__vars['resource']['alt_support_url'] AND $__vars['resource']['Category']['enable_support_url']) {
		$__compilerTemp11 .= '
									' . $__templater->button('xfrm_get_support', array(
			'href' => $__vars['resource']['alt_support_url'],
			'class' => 'button--link button--fullWidth',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp11 .= '
							';
	if (strlen(trim($__compilerTemp11)) > 0) {
		$__finalCompiled .= '
						<div class="resourceSidebarGroup resourceSidebarGroup--buttons">
							' . $__compilerTemp11 . '
						</div>
					';
	}
	$__finalCompiled .= '

					';
	if (!$__templater->test($__vars['authorOthers'], 'empty', array())) {
		$__finalCompiled .= '
						<div class="resourceSidebarGroup">
							<h4 class="resourceSidebarGroup-title">
								<a href="' . $__templater->func('link', array('resources/authors', $__vars['resource']['User'], ), true) . '">' . 'xfrm_more_resources_from_x' . '</a>
							</h4>
							<ul class="resourceSidebarList">
							';
		if ($__templater->isTraversable($__vars['authorOthers'])) {
			foreach ($__vars['authorOthers'] AS $__vars['authorOther']) {
				$__finalCompiled .= '
								<li>
									' . $__templater->callMacro('xfrm_resource_list_macros', 'resource_simple', array(
					'resource' => $__vars['authorOther'],
					'withMeta' => false,
				), $__vars) . '
								</li>
							';
			}
		}
		$__finalCompiled .= '
							</ul>
						</div>
					';
	}
	$__finalCompiled .= '

					';
	$__compilerTemp12 = '';
	$__compilerTemp12 .= '
								' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp12)) > 0) {
		$__finalCompiled .= '
						<div class="resourceSidebarGroup">
							<h4 class="resourceSidebarGroup-title">' . 'xfrm_share_this_resource' . '</h4>
							' . $__compilerTemp12 . '
						</div>
					';
	}
	$__finalCompiled .= '
				</div>
			</div>
		</div>
	</div>
</div>

';
	if (!$__templater->test($__vars['latestUpdates'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'xfrm_latest_updates' . '</h3>
			<ol class="block-body">
			';
		if ($__templater->isTraversable($__vars['latestUpdates'])) {
			foreach ($__vars['latestUpdates'] AS $__vars['update']) {
				$__finalCompiled .= '
				<li class="block-row block-row--separated">
					<h3 class="block-textHeader">
						<a href="' . $__templater->func('link', array('resources/update', $__vars['update'], ), true) . '">' . $__templater->escape($__vars['update']['title']) . '</a>
					</h3>
					<div>' . $__templater->func('snippet', array($__vars['update']['message'], 100, array('stripBbCode' => true, ), ), true) . '</div>
			';
			}
		}
		$__finalCompiled .= '
			</ol>
			<div class="block-footer">
				<span class="block-footer-controls">' . $__templater->button('xfrm_read_more...', array(
			'class' => 'button--link',
			'href' => $__templater->func('link', array('resources/updates', $__vars['resource'], ), false),
		), '', array(
		)) . '</span>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['latestReviews'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'xfrm_latest_reviews' . '</h3>
			<div class="block-body">
			';
		if ($__templater->isTraversable($__vars['latestReviews'])) {
			foreach ($__vars['latestReviews'] AS $__vars['review']) {
				$__finalCompiled .= '
				' . $__templater->callMacro('xfrm_resource_review_macros', 'review', array(
					'review' => $__vars['review'],
					'resource' => $__vars['resource'],
				), $__vars) . '
			';
			}
		}
		$__finalCompiled .= '
			</div>
			<div class="block-footer">
				<span class="block-footer-controls">' . $__templater->button('xfrm_read_more...', array(
			'class' => 'button--link',
			'href' => $__templater->func('link', array('resources/reviews', $__vars['resource'], ), false),
		), '', array(
		)) . '</span>
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);