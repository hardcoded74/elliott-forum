<?php
// FROM HASH: 4dd801c4c96ca908b44ce1e022d1fc8e
return array(
'macros' => array('navigation' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'pageSelected' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('findThreadsNavStyle', ), false) == 'tabs') {
		$__finalCompiled .= '
		<div class="tabs tabs--standalone">
			<div class="hScroller" data-xf-init="h-scroller">
				<span class="hScroller-scroll">
					' . $__templater->callMacro(null, 'links', array(
			'pageSelected' => $__vars['pageSelected'],
			'baseClass' => 'tabs-tab',
			'selectedClass' => 'is-active',
		), $__vars) . '
				</span>
			</div>
		</div>
	';
	} else {
		$__finalCompiled .= '
		';
		$__templater->modifySideNavHtml(null, '
			<div class="block">
				<div class="block-container">
					<h3 class="block-header">' . 'Thread lists' . '</h3>
					<div class="block-body">
						' . $__templater->callMacro(null, 'links', array(
			'pageSelected' => $__vars['pageSelected'],
			'baseClass' => 'blockLink',
			'selectedClass' => 'is-selected',
		), $__vars) . '
					</div>
				</div>
			</div>

			' . $__templater->widgetPosition('find_threads_sidenav', array()) . '
		', 'replace');
		$__finalCompiled .= '
		';
		$__templater->setPageParam('sideNavTitle', 'Thread lists');
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'links' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'pageSelected' => '!',
		'baseClass' => '!',
		'selectedClass' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'started') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array('find-threads/started', ), true) . '" rel="nofollow">' . 'Your threads' . '</a>
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'contributed') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array('find-threads/contributed', ), true) . '" rel="nofollow">' . 'Threads with your posts' . '</a>
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'unanswered') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array('find-threads/unanswered', ), true) . '" rel="nofollow">' . 'Unanswered threads' . '</a>
	<a class="' . $__templater->escape($__vars['baseClass']) . ' ' . (($__vars['pageSelected'] == 'watched') ? $__templater->escape($__vars['selectedClass']) : '') . '"
		href="' . $__templater->func('link', array('watched/threads', ), true) . '" rel="nofollow">' . 'Watched threads' . '</a>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['pageSelected'] == 'started') {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Threads started by ' . $__templater->escape($__vars['user']['username']) . '');
		$__templater->pageParams['pageNumber'] = $__vars['page'];
		$__finalCompiled .= '
';
	} else if ($__vars['pageSelected'] == 'contributed') {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Threads with posts by ' . $__templater->escape($__vars['user']['username']) . '');
		$__templater->pageParams['pageNumber'] = $__vars['page'];
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Threads with no replies');
		$__templater->pageParams['pageNumber'] = $__vars['page'];
		$__finalCompiled .= '
	';
		$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro(null, 'navigation', array(
		'pageSelected' => $__vars['pageSelected'],
	), $__vars) . '

';
	if (!$__templater->test($__vars['threads'], 'empty', array())) {
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

	<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="thread" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">

		';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
					' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'find-threads/' . $__vars['pageSelected'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

					';
		if ($__vars['canInlineMod']) {
			$__compilerTemp1 .= '
						<div class="block-outer-opposite">
							<div class="buttonGroup">
								' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
							</div>
						</div>
					';
		}
		$__compilerTemp1 .= '
				';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
			<div class="block-outer">
				' . $__compilerTemp1 . '
			</div>
		';
		}
		$__finalCompiled .= '

		<div class="block-container">
			<div class="block-body">
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['threads'])) {
			foreach ($__vars['threads'] AS $__vars['thread']) {
				$__finalCompiled .= '
						';
				$__vars['extra'] = '';
				$__finalCompiled .= '
						' . $__templater->callMacro('thread_list_macros', 'item', array(
					'thread' => $__vars['thread'],
					'allowEdit_xxx' => false,
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			</div>
		</div>

		';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
				' . $__templater->func('page_nav', array(array(
			'link' => 'find-threads/' . $__vars['pageSelected'],
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '
			';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__finalCompiled .= '
			' . $__compilerTemp2 . '
		';
		}
		$__finalCompiled .= '
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'There are no threads to display.' . '</div>
';
	}
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);