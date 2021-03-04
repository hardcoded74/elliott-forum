<?php
// FROM HASH: 2efec09da9a2cbbb52819bc24c7a6e81
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped('New posts');
	$__finalCompiled .= '
';
	if ($__vars['xf']['options']['forumsDefaultPage'] != 'new_posts') {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('New posts');
		$__templater->pageParams['pageNumber'] = $__vars['page'];
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->setPageParam('uix_mainTabSets', 'forum');
	$__finalCompiled .= '
';
	$__templater->setPageParam('uix_mainTabActive', 'new_posts');
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['xf']['options']['boardDescription'],
	), $__vars) . '

';
	if (($__vars['page'] > 1) OR $__vars['originalFindNewId']) {
		$__finalCompiled .= '
	';
		$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '
';
	if ($__vars['page'] <= 1) {
		$__finalCompiled .= '
	' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
			'canonicalUrl' => $__templater->func('link', array('canonical:' . $__vars['selfRoute'], ), false),
		), $__vars) . '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('new_posts');
	$__templater->wrapTemplate('forum_overview_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebarbb8936d24c85f5840b47acd6b858bf1a', $__templater->widgetPosition('forum_new_posts_sidebar', array()), 'replace');
	$__finalCompiled .= '
';
	if ($__vars['xf']['options']['forumsDefaultPage'] === 'new_posts') {
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('_xfWidgetPositionSidebar3984593f56d049a294b91ac7c345d272', $__templater->widgetPosition('thuix_index_page', array()), 'replace');
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

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="thread" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	' . '

	<div class="block-container">
		' . $__templater->callMacro('whats_new_posts', 'filter_bar', array(
		'findNew' => $__vars['findNew'],
		'rerunRoute' => 'forums/new-posts',
		'rerunQuery' => array('f' => $__vars['findNew']['find_new_id'], ),
		'filterRoute' => 'forums/new-posts',
	), $__vars) . '

		' . $__templater->callMacro('whats_new_posts', 'results', array(
		'findNew' => $__vars['findNew'],
		'threads' => $__vars['threads'],
		'latestRoute' => 'forums/new-posts',
	), $__vars) . '
	</div>

	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['findNew']['result_count'],
			'link' => 'forums/new-posts',
			'params' => array('f' => $__vars['findNew']['find_new_id'], ),
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
			
			';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
						' . $__templater->callMacro('whats_new_posts', 'buttons', array(
			'findNew' => $__vars['findNew'],
			'canInlineMod' => $__vars['canInlineMod'],
		), $__vars) . '
					';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__finalCompiled .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
					' . $__compilerTemp2 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= '
			
			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);