<?php
// FROM HASH: 08752374879eb164a27852bef45bf681
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['forum']['Node']['title']));
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped($__templater->filter($__vars['forum']['Node']['description'], array(array('raw', array()),), true));
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '

';
	$__templater->setPageParam('uix_mainTabSets', 'forum');
	$__finalCompiled .= '
';
	$__templater->setPageParam('uix_mainTabActive', 'forum_list');
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:forums', $__vars['forum'], $__vars['canonicalFilters'] + array('page' => $__vars['page'], ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('head.' . 'rss_forum', $__templater->preEscaped('<link rel="alternate" type="application/rss+xml" title="' . $__templater->filter('RSS feed for ' . $__vars['forum']['title'] . '', array(array('for_attr', array()),), true) . '" href="' . $__templater->func('link', array('forums/index.rss', $__vars['forum'], ), true) . '" />'));
	$__finalCompiled .= '

' . $__templater->callMacro('forum_macros', 'forum_page_options', array(
		'forum' => $__vars['forum'],
	), $__vars) . '
';
	$__templater->breadcrumbs($__templater->method($__vars['forum'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['forum'], 'canCreateThread', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('
		' . 'Post thread' . '
	', array(
			'href' => $__templater->func('link', array('forums/post-thread', $__vars['forum'], ), false),
			'class' => 'button--cta uix_quickPost--button',
			'icon' => 'write',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	if ($__vars['pendingApproval']) {
		$__finalCompiled .= '
	<div class="blockMessage blockMessage--important">' . 'Your content has been submitted and will be displayed pending approval by a moderator.' . '</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['nodeTree']) {
		$__finalCompiled .= '
	' . $__templater->callAdsMacro('forum_view_above_node_list', array(
			'forum' => $__vars['forum'],
		), $__vars) . '
';
		if ($__templater->func('property', array('th_enableGrid_nodes', ), false)) {
			$__finalCompiled .= '
	' . $__templater->includeTemplate('th_forum_list_grid_nodes', $__vars) . '
	<div class="block thNodes__nodeList">
';
		}
		$__finalCompiled .= '

	<div class="block uix_nodeList block">
		<div class="block-container">
			<div class="block-body">
				' . $__templater->callMacro('forum_list', 'node_list', array(
			'children' => $__vars['nodeTree'],
			'extras' => $__vars['nodeExtras'],
			'depth' => '2',
		), $__vars) . '
			</div>
		</div>
	</div>
	
';
		if ($__templater->func('property', array('th_enableGrid_nodes', ), false)) {
			$__finalCompiled .= '
	</div>
';
		}
		$__finalCompiled .= '
' . $__templater->callAdsMacro('forum_view_below_node_list', array(
			'forum' => $__vars['forum'],
		), $__vars) . '
';
		if ($__templater->func('property', array('th_enableStyling_nodes', ), false) OR $__templater->func('property', array('th_enableGrid_nodes', ), false)) {
			$__finalCompiled .= '
	';
			$__templater->includeCss('th_nodeStyling_nodes.css');
			$__finalCompiled .= '
';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callAdsMacro('forum_view_above_thread_list', array(
		'forum' => $__vars['forum'],
	), $__vars) . '
<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="thread" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">

	<div class="block-outer">';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['canInlineMod']) {
		$__compilerTemp2 .= '
							' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
						';
	}
	$__compilerTemp2 .= '
						';
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp2 .= '
							' . $__templater->button('
								' . 'Mark read' . '
							', array(
			'href' => $__templater->func('link', array('forums/mark-read', $__vars['forum'], array('date' => $__vars['xf']['time'], ), ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
						';
	}
	$__compilerTemp2 .= '
						';
	if ($__templater->method($__vars['forum'], 'canWatch', array())) {
		$__compilerTemp2 .= '
							';
		$__compilerTemp3 = '';
		if ($__vars['forum']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp3 .= 'Unwatch';
		} else {
			$__compilerTemp3 .= 'Watch';
		}
		$__compilerTemp2 .= $__templater->button('
								' . $__compilerTemp3 . '
							', array(
			'href' => $__templater->func('link', array('forums/watch', $__vars['forum'], ), false),
			'class' => 'button--link',
			'data-xf-click' => 'switch-overlay',
			'data-sk-watch' => 'Watch',
			'data-sk-unwatch' => 'Unwatch',
		), '', array(
		)) . '
						';
	}
	$__compilerTemp2 .= '
					';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
			<div class="block-outer-opposite">
				<div class="buttonGroup">
					' . $__compilerTemp2 . '
				</div>
			</div>
		';
	}
	$__finalCompiled .= trim('
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'forums',
		'data' => $__vars['forum'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		' . $__compilerTemp1 . '
		') . '</div>

	';
	$__vars['dateLimits'] = array('-1' => 'Any time', '7' => '' . '7' . ' days', '14' => '' . '14' . ' days', '30' => '' . '30' . ' days', '60' => '' . '2' . ' months', '90' => '' . '3' . ' months', '182' => '' . '6' . ' months', '365' => '1 year', );
	$__finalCompiled .= '
	';
	$__vars['sortOrders'] = array('last_post_date' => 'Last message', 'post_date' => 'First message', 'title' => 'Title', 'reply_count' => 'Replies', 'view_count' => 'Views', 'first_post_reaction_score' => 'First message reaction score', );
	$__finalCompiled .= '

	<div class="block-container uix_discussionList">
		<div class="block-filterBar">
			<div class="filterBar">
				';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
							';
	if ($__vars['filters']['prefix_id']) {
		$__compilerTemp4 .= '
								<li><a href="' . $__templater->func('link', array('forums', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('prefix_id', null, )),), false), ), true) . '"
									   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Prefix' . $__vars['xf']['language']['label_separator'] . '</span>
									' . $__templater->func('prefix_title', array('thread', $__vars['filters']['prefix_id'], ), true) . '</a></li>
							';
	}
	$__compilerTemp4 .= '
							';
	if ($__vars['filters']['starter_id'] AND $__vars['starterFilter']) {
		$__compilerTemp4 .= '
								<li><a href="' . $__templater->func('link', array('forums', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('starter_id', null, )),), false), ), true) . '"
									   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Started by' . $__vars['xf']['language']['label_separator'] . '</span>
									' . $__templater->escape($__vars['starterFilter']['username']) . '</a></li>
							';
	}
	$__compilerTemp4 .= '
							';
	if ($__vars['filters']['last_days'] AND $__vars['dateLimits'][$__vars['filters']['last_days']]) {
		$__compilerTemp4 .= '
								<li><a href="' . $__templater->func('link', array('forums', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array('last_days', null, )),), false), ), true) . '"
									   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Last updated' . $__vars['xf']['language']['label_separator'] . '</span>
									' . $__templater->escape($__vars['dateLimits'][$__vars['filters']['last_days']]) . '</a></li>
							';
	}
	$__compilerTemp4 .= '
							';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp4 .= '
								<li><a href="' . $__templater->func('link', array('forums', $__vars['forum'], $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
									   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
									' . $__templater->escape($__vars['sortOrders'][$__vars['filters']['order']]) . '
									' . $__templater->fontAwesome((($__vars['filters']['direction'] == 'asc') ? 'fa-angle-up' : 'fa-angle-down'), array(
		)) . '
									<span class="u-srOnly">';
		if ($__vars['filters']['direction'] == 'asc') {
			$__compilerTemp4 .= 'Ascending';
		} else {
			$__compilerTemp4 .= 'Descending';
		}
		$__compilerTemp4 .= '</span>
									</a></li>
							';
	}
	$__compilerTemp4 .= '
						';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__finalCompiled .= '
					<ul class="filterBar-filters">
						' . $__compilerTemp4 . '
					</ul>
				';
	}
	$__finalCompiled .= '

				<a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
				<div class="menu menu--wide" data-menu="menu" aria-hidden="true"
					 data-href="' . $__templater->func('link', array('forums/filters', $__vars['forum'], $__vars['filters'], ), true) . '"
					 data-load-target=".js-filterMenuBody">
					<div class="menu-content">
						<h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
						<div class="js-filterMenuBody">
							<div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		';
	$__vars['qtPos'] = $__templater->preEscaped(((($__vars['sortInfo']['order'] == 'last_post_date') AND ($__vars['sortInfo']['direction'] == 'asc')) ? 'bottom' : 'top'));
	$__finalCompiled .= '

		<div class="block-body">
			<div class="structItemContainer">
				' . $__templater->callMacro('thread_list_macros', 'quick_thread', array(
		'forum' => $__vars['forum'],
		'page' => $__vars['page'],
		'order' => $__vars['sortInfo']['order'],
		'direction' => $__vars['sortInfo']['direction'],
	), $__vars) . '
				';
	if (!$__templater->test($__vars['stickyThreads'], 'empty', array()) OR !$__templater->test($__vars['threads'], 'empty', array())) {
		$__finalCompiled .= '
					';
		if (!$__templater->test($__vars['stickyThreads'], 'empty', array())) {
			$__finalCompiled .= '
						';
			if ($__templater->func('property', array('uix_separateThreads', ), false) AND (!$__templater->test($__vars['stickyThreads'], 'empty', array()) AND !$__templater->test($__vars['threads'], 'empty', array()))) {
				$__finalCompiled .= '
							<h3 class="block-minorHeader uix_threadListSeparator">
								' . 'Sticky threads' . '
								';
				if ($__templater->func('property', array('uix_collapsibleStickyThreads', ), false)) {
					$__finalCompiled .= '
									<span class="uix_threadCollapseTrigger ' . ((!$__templater->func('is_toggled', array('thuixfst-' . $__vars['forum']['node_id'], ), false)) ? 'is-active' : '') . '"
										  data-xf-click="toggle"
										  data-target="< :up :next"
										  data-xf-init="toggle-storage"
										  data-storage-key="thuixfst-' . $__templater->escape($__vars['forum']['node_id']) . '">
										' . $__templater->fontAwesome('fa-chevron-down', array(
						'class' => 'uix_icon uix_icon--chevron-down',
					)) . '
									</span>
								';
				}
				$__finalCompiled .= '
							</h3>
						';
			}
			$__finalCompiled .= '
						<div class="uix_stickyContainerOuter  ' . ((!$__templater->func('is_toggled', array('thuixfst-' . $__vars['forum']['node_id'], ), false)) ? 'is-active' : '') . '">
							<div class="uix_stickyContainerInner">
								<div class="structItemContainer-group structItemContainer-group--sticky">
									';
			if ($__templater->isTraversable($__vars['stickyThreads'])) {
				foreach ($__vars['stickyThreads'] AS $__vars['thread']) {
					$__finalCompiled .= '
										' . $__templater->callMacro('thread_list_macros', 'item', array(
						'thread' => $__vars['thread'],
						'forum' => $__vars['forum'],
					), $__vars) . '
									';
				}
			}
			$__finalCompiled .= '
								</div>
							</div>
						</div>

						' . $__templater->callAdsMacro('forum_view_below_stickies', array(
				'forum' => $__vars['forum'],
			), $__vars) . '
					';
		}
		$__finalCompiled .= '

					';
		if ($__templater->func('property', array('uix_separateThreads', ), false) AND (!$__templater->test($__vars['stickyThreads'], 'empty', array()) AND !$__templater->test($__vars['threads'], 'empty', array()))) {
			$__finalCompiled .= '
						<h3 class="block-minorHeader uix_threadListSeparator">' . 'Normal threads' . '</h3>
					';
		}
		$__finalCompiled .= '
					<div class="structItemContainer-group js-threadList">
						';
		if (!$__templater->test($__vars['threads'], 'empty', array())) {
			$__finalCompiled .= '
							';
			if ($__templater->isTraversable($__vars['threads'])) {
				foreach ($__vars['threads'] AS $__vars['thread']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('thread_list_macros', 'item', array(
						'thread' => $__vars['thread'],
						'forum' => $__vars['forum'],
					), $__vars) . '
							';
				}
			}
			$__finalCompiled .= '
							';
			if ($__vars['showDateLimitDisabler']) {
				$__finalCompiled .= '
								<div class="structItem structItem--note">
									<div class="structItem-cell">
										<a href="' . $__templater->func('link', array('forums', $__vars['forum'], array('page' => $__vars['page'], 'no_date_limit' => 1, ) + $__vars['filters'], ), true) . '">
											' . 'Show older items' . '
										</a>
									</div>
								</div>
							';
			}
			$__finalCompiled .= '
						';
		}
		$__finalCompiled .= '
					</div>
					';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
					<div class="structItemContainer-group js-threadList">
						<div class="structItem js-emptyThreadList">
							<div class="structItem-cell">' . 'There are no threads matching your filters.' . '</div>
						</div>
					</div>
					';
	} else {
		$__finalCompiled .= '
					<div class="structItemContainer-group js-threadList">
						<div class="structItem js-emptyThreadList">
							<div class="structItem-cell">' . 'There are no threads in this forum.' . '</div>
						</div>
					</div>
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'forums',
		'data' => $__vars['forum'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
		';
	if (!$__templater->method($__vars['forum'], 'canCreateThread', array())) {
		$__finalCompiled .= '
			<div class="block-outer-opposite">
				';
		if ($__vars['xf']['visitor']['user_id']) {
			$__finalCompiled .= '
					<span class="button is-disabled">
						' . 'You have insufficient privileges to post threads here.' . '
						<!-- this is not interactive so shouldn\'t be a button element -->
					</span>
				';
		} else {
			$__finalCompiled .= '
					' . $__templater->button('
						' . 'You must log in or register to post here.' . '
					', array(
				'href' => $__templater->func('link', array('login', ), false),
				'class' => 'button--link button--wrap',
				'overlay' => 'true',
			), '', array(
			)) . '
				';
		}
		$__finalCompiled .= '
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>
' . $__templater->callAdsMacro('forum_view_below_thread_list', array(
		'forum' => $__vars['forum'],
	), $__vars) . '

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebarfe322c91013383793fda9cb0d7e31c1f', $__templater->widgetPosition('forum_view_sidebar', array(
		'forum' => $__vars['forum'],
	)), 'replace');
	return $__finalCompiled;
}
);