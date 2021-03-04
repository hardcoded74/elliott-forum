<?php
// FROM HASH: 9f0e9eb65682f322a532d8b1cd78fec4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['mediaItem']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['noH1'] = true;
	$__finalCompiled .= '

';
	$__templater->includeCss('xfmg_media_view.less');
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'prod' => 'xfmg/image_noter-compiled.js',
		'dev' => 'xfmg/vendor/cropper/cropper.js, xfmg/image_noter.js',
	));
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_page_macros', 'xfmg_page_options', array(
		'album' => $__vars['mediaItem']['Album'],
		'category' => $__vars['mediaItem']['Category'],
		'mediaItem' => $__vars['mediaItem'],
	), $__vars) . '

';
	$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['mediaItem']['description'], 250, ), false);
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['descSnippet'],
		'shareUrl' => $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), false),
		'imageUrl' => $__templater->method($__vars['mediaItem'], 'getCurrentThumbnailUrl', array(true, )),
		'canonicalUrl' => $__templater->func('link', array('canonical:media', $__vars['mediaItem'], array('page' => $__vars['page'], ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('ldJsonHtml', '
<script type="application/ld+json">
' . $__templater->filter($__vars['mediaItem']['structured_data'], array(array('json', array(true, )),array('raw', array()),), true) . '
</script>
');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['mediaItem'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_media_view_macros', 'media_status', array(
		'mediaItem' => $__vars['mediaItem'],
	), $__vars) . '

<div class="media">
	';
	if ($__vars['filmStripParams']['prevItem']) {
		$__finalCompiled .= '
		<a href="' . $__templater->func('link', array('media', $__vars['filmStripParams']['prevItem'], ), true) . '" class="media-button media-button--prev" data-xf-key="ArrowLeft"><i class="media-button-icon"></i></a>
	';
	}
	$__finalCompiled .= '

	<div class="media-container"
		data-xf-init="' . (($__vars['mediaItem']['media_type'] == 'image') ? 'image-noter' : '') . '"
		data-toggle-id="#js-noterToggle"
		data-edit-url="' . $__templater->func('link', array('media/note-edit', $__vars['mediaItem'], ), true) . '">

		' . $__templater->callMacro('xfmg_media_view_macros', 'media_content', array(
		'mediaItem' => $__vars['mediaItem'],
		'mediaNotes' => $__vars['mediaNotes'],
	), $__vars) . '
	</div>

	';
	if ($__vars['filmStripParams']['nextItem']) {
		$__finalCompiled .= '
		<a href="' . $__templater->func('link', array('media', $__vars['filmStripParams']['nextItem'], ), true) . '" class="media-button media-button--next" data-xf-key="ArrowRight"><i class="media-button-icon"></i></a>
	';
	}
	$__finalCompiled .= '
</div>

<div class="block js-mediaInfoBlock">
	' . $__templater->callMacro('xfmg_media_view_macros', 'media_film_strip', array(
		'mediaItem' => $__vars['mediaItem'],
		'filmStripParams' => $__vars['filmStripParams'],
	), $__vars) . '

	<div class="block-container">
		<div class="block-body block-row xfmgInfoBlock">
			<div class="xfmgInfoBlock-title">
				<div class="contentRow contentRow--alignMiddle">
					<span class="contentRow-figure">
						' . $__templater->func('avatar', array($__vars['mediaItem']['User'], 's', false, array(
		'defaultname' => $__vars['mediaItem']['username'],
	))) . '
					</span>
					<div class="contentRow-main">
						<h1 class="contentRow-title p-title-value">' . $__templater->func('page_h1', array('')) . '</h1>
						<div class="contentRow-lesser p-description">
							<ul class="listInline listInline--bullet">
								<li>' . $__templater->fontAwesome('fa-user', array(
		'title' => $__templater->filter('xfmg_media_owner', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('username_link', array($__vars['mediaItem']['User'], false, array(
		'defaultname' => $__vars['mediaItem']['username'],
		'class' => 'u-concealed',
	))) . '</li>
								<li>' . $__templater->fontAwesome('fa-clock', array(
		'title' => $__templater->filter('xfmg_date_added', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('date_dynamic', array($__vars['mediaItem']['media_date'], array(
	))) . '</li>
								';
	if ($__vars['xf']['options']['enableTagging'] AND ($__templater->method($__vars['mediaItem'], 'canEditTags', array()) OR $__vars['mediaItem']['tags'])) {
		$__finalCompiled .= '
									<li>
										' . $__templater->callMacro('tag_macros', 'list', array(
			'tags' => $__vars['mediaItem']['tags'],
			'tagList' => 'tagList--mediaItem-' . $__vars['mediaItem']['media_id'],
			'editLink' => ($__templater->method($__vars['mediaItem'], 'canEditTags', array()) ? $__templater->func('link', array('media/tags', $__vars['mediaItem'], ), false) : ''),
		), $__vars) . '
									</li>
								';
	}
	$__finalCompiled .= '
							</ul>
						</div>
					</div>
				</div>
			</div>

			';
	if ($__vars['mediaItem']['description']) {
		$__finalCompiled .= '
				<div class="xfmgInfoBlock-description">
					<div class="bbCodeBlock bbCodeBlock--expandable">
						<div class="bbCodeBlock-content">
							<div class="bbCodeBlock-expandContent">
								' . $__templater->func('structured_text', array($__vars['mediaItem']['description'], ), true) . '
							</div>
							<div class="bbCodeBlock-expandLink"><a>' . 'Click to expand...' . '</a></div>
						</div>
					</div>
				</div>
			';
	}
	$__finalCompiled .= '

			' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'xfmgMediaFields',
		'group' => 'below_media',
		'onlyInclude' => ($__vars['mediaItem']['category_id'] ? $__vars['mediaItem']['Category']['field_cache'] : $__vars['mediaItem']['Album']['field_cache']),
		'set' => $__vars['mediaItem']['custom_fields'],
	), $__vars) . '
			
			<div class="reactionsBar js-reactionsList ' . ($__vars['mediaItem']['reactions'] ? 'is-active' : '') . '">
				' . $__templater->func('reactions', array($__vars['mediaItem'], 'media/reactions', array())) . '
			</div>

			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
									' . $__templater->func('react', array(array(
		'content' => $__vars['mediaItem'],
		'link' => 'media/react',
		'list' => '< .js-mediaInfoBlock | .js-reactionsList',
	))) . '
								';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
							<div class="actionBar-set actionBar-set--external">
								' . $__compilerTemp2 . '
							</div>
						';
	}
	$__compilerTemp1 .= '

						';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
									';
	if ($__templater->method($__vars['mediaItem'], 'canReport', array())) {
		$__compilerTemp3 .= '
										<a href="' . $__templater->func('link', array('media/report', $__vars['mediaItem'], ), true) . '"
											class="actionBar-action actionBar-action--report"
											data-xf-click="overlay">' . 'Report' . '</a>
									';
	}
	$__compilerTemp3 .= '

									';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp3 .= '
									';
	if ($__templater->method($__vars['mediaItem'], 'canEdit', array())) {
		$__compilerTemp3 .= '
										<a href="' . $__templater->func('link', array('media/edit', $__vars['mediaItem'], ), true) . '"
											class="actionBar-action actionBar-action--edit actionBar-action--menuItem"
											data-xf-click="overlay">' . 'Edit' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
									';
	}
	$__compilerTemp3 .= '
									';
	if ($__templater->method($__vars['mediaItem'], 'canDelete', array())) {
		$__compilerTemp3 .= '
										<a href="' . $__templater->func('link', array('media/delete', $__vars['mediaItem'], ), true) . '"
											class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
											data-xf-click="overlay">' . 'Delete' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
									';
	}
	$__compilerTemp3 .= '
									';
	if ($__templater->method($__vars['mediaItem'], 'canCleanSpam', array())) {
		$__compilerTemp3 .= '
										<a href="' . $__templater->func('link', array('spam-cleaner', $__vars['mediaItem'], ), true) . '"
											class="actionBar-action actionBar-action--spam actionBar-action--menuItem"
											data-xf-click="overlay">' . 'Spam' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
									';
	}
	$__compilerTemp3 .= '
									';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['mediaItem']['ip_id']) {
		$__compilerTemp3 .= '
										<a href="' . $__templater->func('link', array('media/ip', $__vars['mediaItem'], ), true) . '"
											class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
											data-xf-click="overlay">' . 'IP' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
									';
	}
	$__compilerTemp3 .= '
									';
	if ($__templater->method($__vars['mediaItem'], 'canWarn', array())) {
		$__compilerTemp3 .= '
										<a href="' . $__templater->func('link', array('media/warn', $__vars['mediaItem'], ), true) . '"
											class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
									';
	} else if ($__vars['mediaItem']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp3 .= '
										<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['mediaItem']['warning_id'], ), ), true) . '"
											class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
											data-xf-click="overlay">' . 'View warning' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
									';
	}
	$__compilerTemp3 .= '

									';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp3 .= '
										<a class="actionBar-action actionBar-action--menuTrigger"
											data-xf-click="menu"
											title="' . $__templater->filter('More options', array(array('for_attr', array()),), true) . '"
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
	$__compilerTemp3 .= '
								';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp1 .= '
							<div class="actionBar-set actionBar-set--internal">
								' . $__compilerTemp3 . '
							</div>
						';
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
				<div class="actionBar">
					' . $__compilerTemp1 . '
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
					';
	if ($__templater->method($__vars['mediaItem'], 'canRate', array())) {
		$__compilerTemp4 .= '
						' . $__templater->button('
							' . 'Leave a rating' . '
						', array(
			'href' => $__templater->func('link', array('media/media-ratings/rate', $__vars['mediaItem'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp4 .= '

					';
	if ($__templater->method($__vars['mediaItem'], 'canAddNote', array())) {
		$__compilerTemp4 .= '
						' . $__templater->button('

							' . 'xfmg_add_note_tag' . '
						', array(
			'id' => 'js-noterToggle',
			'class' => 'button--icon',
			'data-active-label' => 'xfmg_stop_adding_note_tag',
			'data-active-icon' => 'cancel',
			'data-active-message' => 'xfmg_note_tag_mode_activated',
			'data-inactive-label' => 'xfmg_add_note_tag',
			'data-inactive-icon' => '',
			'data-inactive-message' => 'xfmg_note_tag_mode_deactivated',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp4 .= '

					';
	$__compilerTemp5 = '';
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['mediaItem'], 'canUndelete', array()) AND ($__vars['mediaItem']['media_state'] == 'deleted')) {
		$__compilerTemp5 .= '
									' . $__templater->button('
										' . 'Undelete' . '
									', array(
			'href' => $__templater->func('link', array('media/undelete', $__vars['mediaItem'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['mediaItem'], 'canApproveUnapprove', array()) AND ($__vars['mediaItem']['media_state'] == 'moderated')) {
		$__compilerTemp5 .= '
									' . $__templater->button('
										' . 'Approve' . '
									', array(
			'href' => $__templater->func('link', array('media/approve', $__vars['mediaItem'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['mediaItem'], 'canWatch', array())) {
		$__compilerTemp5 .= '
									';
		$__compilerTemp6 = '';
		if ($__vars['mediaItem']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp6 .= '
											' . 'Unwatch' . '
										';
		} else {
			$__compilerTemp6 .= '
											' . 'Watch' . '
										';
		}
		$__compilerTemp5 .= $__templater->button('

										' . $__compilerTemp6 . '
									', array(
			'href' => $__templater->func('link', array('media/watch', $__vars['mediaItem'], ), false),
			'class' => 'button--link',
			'data-xf-click' => 'switch-overlay',
			'data-sk-watch' => 'Watch',
			'data-sk-unwatch' => 'Unwatch',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp5 .= '
								' . $__templater->callMacro('bookmark_macros', 'button', array(
		'content' => $__vars['mediaItem'],
		'confirmUrl' => $__templater->func('link', array('media/bookmark', $__vars['mediaItem'], ), false),
	), $__vars) . '

								';
	$__compilerTemp7 = '';
	$__compilerTemp7 .= '
													' . '
													';
	if ($__templater->method($__vars['mediaItem'], 'canSetAsAvatar', array())) {
		$__compilerTemp7 .= '
														<a href="' . $__templater->func('link', array('media/set-as-avatar', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">
															' . 'xfmg_set_as_avatar' . '
														</a>
														';
		if ($__vars['avatarUpdated']) {
			$__compilerTemp7 .= '
															<a href="' . $__templater->func('link', array('account/avatar', ), true) . '" data-xf-click="overlay" data-load-auto-click="true" style="display: none"></a>
														';
		}
		$__compilerTemp7 .= '
													';
	}
	$__compilerTemp7 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canEdit', array())) {
		$__compilerTemp7 .= '
														<a href="' . $__templater->func('link', array('media/edit', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'xfmg_edit_media_item' . '</a>
													';
	}
	$__compilerTemp7 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canEditImage', array())) {
		$__compilerTemp7 .= '
														<a href="' . $__templater->func('link', array('media/edit-image', $__vars['mediaItem'], ), true) . '" class="menu-linkRow">' . 'xfmg_edit_image' . '</a>
													';
	}
	$__compilerTemp7 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canChangeThumbnail', array())) {
		$__compilerTemp7 .= '
														<a href="' . $__templater->func('link', array('media/change-thumbnail', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'xfmg_change_thumbnail' . '</a>
													';
	}
	$__compilerTemp7 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canMove', array())) {
		$__compilerTemp7 .= '
														<a href="' . $__templater->func('link', array('media/move', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'xfmg_move_media_item' . '</a>
													';
	}
	$__compilerTemp7 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canDelete', array('soft', ))) {
		$__compilerTemp7 .= '
														<a href="' . $__templater->func('link', array('media/delete', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'xfmg_delete_media_item' . '</a>
													';
	}
	$__compilerTemp7 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canViewModeratorLogs', array())) {
		$__compilerTemp7 .= '
														<a href="' . $__templater->func('link', array('media/moderator-actions', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Moderator actions' . '</a>
													';
	}
	$__compilerTemp7 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canViewModeratorLogs', array())) {
		$__compilerTemp7 .= '
														<a href="' . $__templater->func('link', array('media/moderator-actions', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Moderator actions' . '</a>
													';
	}
	$__compilerTemp7 .= '
													' . '
													';
	if ($__templater->method($__vars['mediaItem'], 'canUseInlineModeration', array())) {
		$__compilerTemp7 .= '
														';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__compilerTemp7 .= '

														<div class="menu-footer"
															 data-xf-init="inline-mod"
															 data-type="xfmg_media"
															 data-href="' . $__templater->func('link', array('inline-mod', ), true) . '"
															 data-toggle=".js-mediaInlineModToggle">
															' . $__templater->formCheckBox(array(
		), array(array(
			'class' => 'js-mediaInlineModToggle',
			'value' => $__vars['mediaItem']['media_id'],
			'label' => 'Select for moderation',
			'_type' => 'option',
		))) . '
														</div>
													';
	}
	$__compilerTemp7 .= '
													' . '
												';
	if (strlen(trim($__compilerTemp7)) > 0) {
		$__compilerTemp5 .= '
									<div class="buttonGroup-buttonWrapper">
										' . $__templater->button('&#8226;&#8226;&#8226;', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'title' => 'More options',
		), '', array(
		)) . '
										<div class="menu" data-menu="menu" aria-hidden="true">
											<div class="menu-content">
												<h4 class="menu-header">' . 'More options' . '</h4>
												' . $__compilerTemp7 . '
											</div>
										</div>
									</div>
								';
	}
	$__compilerTemp5 .= '
							';
	if (strlen(trim($__compilerTemp5)) > 0) {
		$__compilerTemp4 .= '
						<div class="buttonGroup">
							' . $__compilerTemp5 . '
						</div>
					';
	}
	$__compilerTemp4 .= '
				';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__finalCompiled .= '
			<div class="block-outer-opposite">
				' . $__compilerTemp4 . '
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>

';
	if ($__templater->method($__vars['mediaItem'], 'canViewComments', array())) {
		$__finalCompiled .= '
	<div class="columnContainer">
		<div class="columnContainer-comments">
			' . $__templater->callMacro('xfmg_comment_macros', 'comment_list', array(
			'comments' => $__vars['comments'],
			'content' => $__vars['mediaItem'],
			'linkPrefix' => 'media/media-comments',
			'link' => 'media',
			'page' => $__vars['page'],
			'perPage' => $__vars['perPage'],
			'totalItems' => $__vars['totalItems'],
			'canInlineMod' => $__vars['canInlineModComments'],
		), $__vars) . '
		</div>

		<div class="columnContainer-sidebar">
			' . $__templater->callMacro('xfmg_media_view_macros', 'info_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '

			' . $__templater->callMacro('xfmg_media_view_macros', 'extra_info_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '

			' . $__templater->callMacro('xfmg_media_view_macros', 'additional_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '

			' . $__templater->callMacro('xfmg_media_view_macros', 'exif_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '

			' . $__templater->callMacro('xfmg_media_view_macros', 'user_tags_sidebar', array(
			'mediaNotes' => $__vars['mediaNotes'],
		), $__vars) . '

			' . $__templater->callMacro('xfmg_media_view_macros', 'share_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('infoSidebar', '
		' . $__templater->callMacro('xfmg_media_view_macros', 'info_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '
	', 'replace');
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('extraInfoSidebar', '
		' . $__templater->callMacro('xfmg_media_view_macros', 'extra_info_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '
	', 'replace');
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('additionalSidebar', '
		' . $__templater->callMacro('xfmg_media_view_macros', 'additional_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '
	', 'replace');
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('exifSidebar', '
		' . $__templater->callMacro('xfmg_media_view_macros', 'exif_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '
	', 'replace');
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('userTagsSidebar', '
		' . $__templater->callMacro('xfmg_media_view_macros', 'user_tags_sidebar', array(
			'mediaNotes' => $__vars['mediaNotes'],
		), $__vars) . '
	', 'replace');
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('shareSidebar', '
		' . $__templater->callMacro('xfmg_media_view_macros', 'share_sidebar', array(
			'mediaItem' => $__vars['mediaItem'],
		), $__vars) . '
	', 'replace');
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);