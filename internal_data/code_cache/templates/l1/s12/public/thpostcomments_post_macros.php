<?php
// FROM HASH: 6020d5060bce704677cd6eed967a9709
return array(
'macros' => array('comment' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
		'thread' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<article class="message message--depth' . $__templater->escape($__vars['post']['thpostcomments_depth']) . ' message--simple' . ($__templater->method($__vars['post'], 'isIgnored', array()) ? ' is-ignored' : '') . ' js-inlineModContainer"
			 data-author="' . ($__templater->escape($__vars['post']['User']['username']) ?: $__templater->escape($__vars['post']['username'])) . '"
			 data-content="profile-post-' . $__templater->escape($__vars['post']['post_id']) . '"
			 id="js-post-' . $__templater->escape($__vars['post']['post_id']) . '">

		';
	if ($__vars['post']['thpostcomments_depth'] > 0) {
		$__finalCompiled .= '
			<div class="th_messageCollapseTrigger">
					<i class="fa fa-chevron-down"></i>
			</div>
		';
	}
	$__finalCompiled .= '
		<span class="u-anchorTarget" id="post-' . $__templater->escape($__vars['post']['post_id']) . '"></span>
		<div class="message-inner">
			<div class="message-cell message-cell--user">
				' . $__templater->callMacro('message_macros', 'user_info_simple', array(
		'user' => $__vars['post']['User'],
		'fallbackName' => $__vars['post']['username'],
	), $__vars) . '
			</div>
			<div class="message-cell message-cell--main">
				<div class="message-main js-quickEditTarget">
					<div class="message-content js-messageContent">
						<header class="message-attribution message-attribution--plain">
							<ul class="listInline listInline--bullet">
								<li class="message-attribution-user">
									' . $__templater->func('avatar', array($__vars['post']['User'], 'xxs', false, array(
	))) . '
									<h4 class="attribution">
										' . $__templater->callMacro('profile_post_macros', 'attribution', array(
		'profilePost' => $__vars['post'],
		'showTargetUser' => $__vars['showTargetUser'],
	), $__vars) . '
									</h4>
								</li>
								<li><a href="' . $__templater->func('link', array('posts', $__vars['post'], ), true) . '"
									   class="u-concealed"
									   rel="nofollow">
									' . $__templater->func('date_dynamic', array($__vars['post']['post_date'], array(
	))) . '
									</a>
								</li>
							</ul>
						</header>

						<article class="message-body">
							' . $__templater->func('bb_code', array($__vars['post']['message'], 'post', $__vars['post'], ), true) . '
						</article>

						';
	if ($__vars['post']['attach_count']) {
		$__finalCompiled .= '
							' . $__templater->callMacro('message_macros', 'attachments', array(
			'attachments' => $__vars['post']['Attachments'],
			'message' => $__vars['post'],
			'canView' => $__templater->method($__vars['thread'], 'canViewAttachments', array()),
		), $__vars) . '
						';
	}
	$__finalCompiled .= '
					</div>


					<footer class="message-footer">
						<div class="message-actionBar actionBar">
							';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
										';
	if ($__templater->method($__vars['post'], 'canLike', array())) {
		$__compilerTemp1 .= '
											<a href="' . $__templater->func('link', array('posts/like', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--like"
											   data-xf-click="like"
											   data-like-list="< .message | .js-likeList">
												';
		if ($__templater->method($__vars['post'], 'isLiked', array())) {
			$__compilerTemp1 .= '
													' . 'Unlike' . '
													';
		} else {
			$__compilerTemp1 .= '
													' . 'Like' . '
												';
		}
		$__compilerTemp1 .= '
											</a>
										';
	}
	$__compilerTemp1 .= '
										';
	if ($__templater->method($__vars['post'], 'canComment', array())) {
		$__compilerTemp1 .= '
											<a href="' . $__templater->func('link', array('posts/comment', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--comment"
											   title="' . $__templater->filter('Reply, quoting this message', array(array('for_attr', array()),), true) . '"
											   data-editor-target="#js-post-' . $__templater->escape($__vars['post']['post_id']) . '"
											   data-xf-click="comment">' . 'Reply' . '</a>
										';
	}
	$__compilerTemp1 .= '
									';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
								<div class="actionBar-set actionBar-set--external">
									' . $__compilerTemp1 . '
								</div>
							';
	}
	$__finalCompiled .= '

							';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['post'], 'canUseInlineModeration', array())) {
		$__compilerTemp2 .= '
											<span class="actionBar-action actionBar-action--inlineMod">
												' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['post']['post_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'label' => 'Select for moderation',
			'hiddenlabel' => 'true',
			'_type' => 'option',
		))) . '
											</span>
										';
	}
	$__compilerTemp2 .= '

										';
	if ($__templater->method($__vars['post'], 'canReport', array())) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('posts/report', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--report"
											   data-xf-click="overlay">' . 'Report' . '</a>
										';
	}
	$__compilerTemp2 .= '

										';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['post'], 'canEdit', array())) {
		$__compilerTemp2 .= '
											';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('posts/edit', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--edit actionBar-action--menuItem"
											   data-xf-click="quick-edit"
											   data-editor-target="#js-post-' . $__templater->escape($__vars['post']['post_id']) . ' .js-quickEditTarget"
											   data-menu-closer="true">' . 'Edit' . '</a>

											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '
										';
	if ($__vars['post']['edit_count'] AND $__templater->method($__vars['post'], 'canViewHistory', array())) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('posts/history', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--history actionBar-action--menuItem"
											   data-xf-click="toggle"
											   data-target="#js-post-' . $__templater->escape($__vars['post']['post_id']) . ' .js-historyTarget"
											   data-menu-closer="true">' . 'History' . '</a>

											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['post'], 'canDelete', array('soft', ))) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('posts/delete', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
											   data-xf-click="overlay">' . 'Delete' . '</a>

											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['post'], 'canCleanSpam', array())) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('spam-cleaner', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--spam actionBar-action--menuItem"
											   data-xf-click="overlay">' . 'Spam' . '</a>

											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['post']['ip_id']) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('posts/ip', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
											   data-xf-click="overlay">' . 'IP' . '</a>

											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['post'], 'canWarn', array())) {
		$__compilerTemp2 .= '

											<a href="' . $__templater->func('link', array('posts/warn', $__vars['post'], ), true) . '"
											   class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>

											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
											';
	} else if ($__vars['post']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['post']['warning_id'], ), ), true) . '"
											   class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
											   data-xf-click="overlay">' . 'View warning' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '

										';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp2 .= '
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
	$__compilerTemp2 .= '
									';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
								<div class="actionBar-set actionBar-set--internal">
									' . $__compilerTemp2 . '
								</div>
							';
	}
	$__finalCompiled .= '
						</div>
					</footer>
				</div>
			</div>
		</div>
	</article>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);