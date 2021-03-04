<?php
// FROM HASH: 78ed13fbd4f54270c2ef6fe2cbd77fb1
return array(
'macros' => array('uix_sidebarTrigger__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'location' => '',
		'content' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__vars['location'] == $__templater->func('property', array('uix_sidebarTriggerPosition', ), false)) OR ($__vars['location'] == 'navigation')) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_search__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'location' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__templater->func('property', array('uix_searchPosition', ), false) == $__vars['location']) OR ($__vars['location'] == 'navigation')) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_whatsNew__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'location' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__vars['xf']['visitor']['user_id'] AND ($__templater->func('property', array('uix_userTabsPosition', ), false) == $__vars['location'])) OR ($__vars['location'] == 'navigation')) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_loginTabs__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'location' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__templater->func('property', array('uix_loginTriggerPosition', ), false) == $__vars['location']) OR ($__vars['location'] == 'navigation')) {
		$__finalCompiled .= '
		';
		if ($__vars['location'] == 'tablinks') {
			$__finalCompiled .= '
		';
		}
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_userTabs__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'location' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__templater->func('property', array('uix_userTabsPosition', ), false) == $__vars['location']) OR ($__vars['location'] == 'navigation')) {
		$__finalCompiled .= '
		';
		if ($__vars['location'] == 'tablinks') {
			$__finalCompiled .= '
		';
		}
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_socialMedia__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '',
		'location' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__templater->func('property', array('uix_socialMediaPosition', ), false) == $__vars['location']) OR ($__vars['location'] == 'copyright')) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_visitorTabs__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'socialMediaContent' => '',
		'searchContent' => '!',
		'location' => '!',
		'whatsNewContent' => '!',
		'visitorContent' => '!',
		'loginTabsContent' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
		';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
							';
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp2 .= '
								' . $__templater->callMacro(null, 'uix_userTabs__component', array(
			'content' => $__vars['visitorContent'],
			'location' => $__vars['location'],
		), $__vars) . '
								';
	} else {
		$__compilerTemp2 .= '
								' . $__templater->callMacro(null, 'uix_loginTabs__component', array(
			'content' => $__vars['loginTabsContent'],
			'location' => $__vars['location'],
		), $__vars) . '
							';
	}
	$__compilerTemp2 .= '
						';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
					<div class="p-navgroup p-account ' . ($__vars['xf']['visitor']['user_id'] ? 'p-navgroup--member' : 'p-navgroup--guest') . '">
						' . $__compilerTemp2 . '
					</div>
				';
	}
	$__compilerTemp1 .= '
				';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
							' . $__templater->callMacro(null, 'uix_whatsNew__component', array(
		'content' => $__vars['whatsNewContent'],
		'location' => $__vars['location'],
	), $__vars) . '
							' . $__templater->callMacro(null, 'uix_search__component', array(
		'content' => $__vars['searchContent'],
		'location' => $__vars['location'],
	), $__vars) . '
						';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp1 .= '
					<div class="p-navgroup p-discovery' . ((!$__templater->method($__vars['xf']['visitor'], 'canSearch', array())) ? ' p-discovery--noSearch' : '') . '">
						' . $__compilerTemp3 . '
					</div>
				';
	}
	$__compilerTemp1 .= '
				' . $__templater->callMacro(null, 'uix_socialMedia__component', array(
		'content' => $__vars['socialMediaContent'],
		'location' => $__vars['location'],
	), $__vars) . '
			';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
			' . $__compilerTemp1 . '
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
}
),
'uix_titlebar__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'location' => '!',
		'content' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('uix_titlebarLocation', ), false) === $__vars['location']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_sidebar__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'location' => '!',
		'content' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('uix_sidebarLocation', ), false) === $__vars['location']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_logo__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->escape($__vars['content']) . '
';
	return $__finalCompiled;
}
),
'uix_staffbar__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->escape($__vars['content']) . '
';
	return $__finalCompiled;
}
),
'uix_sidebarNav__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->escape($__vars['content']) . '
';
	return $__finalCompiled;
}
),
'uix_mainTabComponent' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'location' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__templater->func('property', array('uix_tabBarLocation', ), false) === $__vars['location']) AND $__templater->func('property', array('uix_enableMainTabs', ), false)) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_canvasTab' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'icon' => '!',
		'location' => '!',
		'canvas' => '!',
		'type' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['location'] == $__vars['canvas']) {
		$__finalCompiled .= '
		<div class="uix_canvasTab uix_canvasTab__' . $__templater->escape($__vars['type']) . '" data-target="uix_canvasPanel__' . $__templater->escape($__vars['type']) . '">
			' . $__templater->fontAwesome('fa-' . $__templater->escape($__vars['icon']), array(
			'class' => 'uix_icon uix_icon--' . $__vars['icon'],
		)) . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_canvasTabs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'location' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="uix_canvas__tabs">
		' . $__templater->callMacro(null, 'uix_canvasTab', array(
		'icon' => 'list',
		'type' => 'navigation',
		'location' => $__templater->func('property', array('uix_canvas_location_navigation', ), false),
		'canvas' => $__vars['location'],
	), $__vars) . '
		' . $__templater->callMacro(null, 'uix_canvasTab', array(
		'icon' => 'grid',
		'type' => 'sidebar',
		'location' => $__templater->func('property', array('uix_canvas_location_sidebar', ), false),
		'canvas' => $__vars['location'],
	), $__vars) . '
		' . $__templater->callMacro(null, 'uix_canvasTab', array(
		'icon' => 'user',
		'type' => 'account',
		'location' => $__templater->func('property', array('uix_canvas_location_visitorTabs', ), false),
		'canvas' => $__vars['location'],
	), $__vars) . '
		' . $__templater->callMacro(null, 'uix_canvasTab', array(
		'icon' => 'email',
		'type' => 'conversations',
		'location' => $__templater->func('property', array('uix_canvas_location_visitorTabs', ), false),
		'canvas' => $__vars['location'],
	), $__vars) . '
		' . $__templater->callMacro(null, 'uix_canvasTab', array(
		'icon' => 'alert',
		'type' => 'alerts',
		'location' => $__templater->func('property', array('uix_canvas_location_visitorTabs', ), false),
		'canvas' => $__vars['location'],
	), $__vars) . '
		' . $__templater->callMacro(null, 'uix_canvasTab', array(
		'icon' => 'star',
		'type' => 'custom',
		'location' => $__templater->func('property', array('uix_canvas_location_custom', ), false),
		'canvas' => $__vars['location'],
	), $__vars) . '
	</div>
';
	return $__finalCompiled;
}
),
'breadcrumbs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'breadcrumbHasCrumbs' => '!',
		'crumbContent' => '!',
		'variant' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	if ($__vars['breadcrumbHasCrumbs']) {
		$__compilerTemp1 .= '
						';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
									' . $__templater->escape($__vars['crumbContent']) . '
								';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__compilerTemp1 .= '
							<ul class="p-breadcrumbs ' . ($__vars['variant'] ? ('p-breadcrumbs--' . $__templater->escape($__vars['variant'])) : '') . '"
								itemscope itemtype="https://schema.org/BreadcrumbList">
								' . $__compilerTemp2 . '
							</ul>
						';
		}
		$__compilerTemp1 .= '
					';
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="breadcrumb block ' . ($__vars['variant'] ? ('p-breadcrumb--' . $__templater->escape($__vars['variant'])) : '') . '">
			<div class="pageContent">
				' . $__compilerTemp1 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'crumb' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'href' => '!',
		'value' => '!',
		'position' => 0,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<a href="' . $__templater->escape($__vars['href']) . '" itemprop="item">
			';
	if (($__vars['href'] == $__vars['xf']['homePageUrl']) AND $__templater->func('property', array('uix_homeCrumbIcon', ), false)) {
		$__finalCompiled .= '
				' . $__templater->fontAwesome('fa-home', array(
			'class' => 'uix_icon uix_icon--home',
		)) . '
				<span style="display: none;" itemprop="name">' . $__templater->escape($__vars['value']) . '</span>
				';
	} else {
		$__finalCompiled .= '
				<span itemprop="name">' . $__templater->escape($__vars['value']) . '</span>
			';
	}
	$__finalCompiled .= '
		</a>
		';
	if ($__vars['position']) {
		$__finalCompiled .= '<meta itemprop="position" content="' . $__templater->escape($__vars['position']) . '" />';
	}
	$__finalCompiled .= '
	</li>
';
	return $__finalCompiled;
}
),
'uix_topBreadcrumb__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'location' => '',
		'content' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('uix_topBreadcrumbLocation', ), false) === $__vars['location']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_bottomBreadcrumb__component' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'location' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('uix_bottomBreadcrumbLocation', ), false) == $__vars['location']) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_notices' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'location' => '!',
		'content' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['location'] == $__templater->func('property', array('uix_noticeLocation', ), false)) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'insideContent' => '!',
		'location' => '!',
		'content' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__vars['insideContent'] AND ($__vars['location'] == 'insideContent')) OR (($__vars['location'] == 'bottom') AND (!$__vars['insideContent']))) {
		$__finalCompiled .= '
		' . $__templater->escape($__vars['content']) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'uix_logoBlock' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
						';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
										' . $__templater->callAdsMacro('container_header', array(), $__vars) . '
									';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
							<header class="p-header p-header--hasExtraContent" id="header">
								<div class="p-header-inner">
									' . $__templater->escape($__vars['content']) . '
									' . $__compilerTemp1 . '

								</div>
							</header>
							';
	} else {
		$__finalCompiled .= '
							<header class="p-header" id="header">
								<div class="p-header-inner">
									' . $__templater->escape($__vars['content']) . '
								</div>
							</header>
						';
	}
	$__finalCompiled .= '
					';
	return $__finalCompiled;
}
),
'canvasNavPanel' => array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
							<div class="offCanvasMenu-header">
								' . 'Menu' . '
								<a class="offCanvasMenu-closer" data-menu-close="true" role="button" tabindex="0" aria-label="' . $__templater->filter('Close', array(array('for_attr', array()),), true) . '"></a>
							</div>
							';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
								<div class="p-offCanvasAccountLink">
									<a href="' . $__templater->func('link', array('account', ), true) . '" class="offCanvasMenu-link">
										' . $__templater->func('avatar', array($__vars['xf']['visitor'], 'xxs', false, array(
			'href' => '',
		))) . '
										' . $__templater->escape($__vars['xf']['visitor']['username']) . '
									</a>
									<hr class="offCanvasMenu-separator" />
								</div>
							';
	}
	$__finalCompiled .= '
							<div class="js-offCanvasNavTarget"></div>
							';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
								<div class="offCanvasMenu-linkHolder u-ripple ' . (($__vars['uix_myAccountLinks'] == 'profile') ? 'is-selected' : '') . '"><div class="p-navEl__inner"><a data-nav-id="profile" href="' . $__templater->func('link', array('members', $__vars['xf']['visitor'], ), true) . '" class="offCanvasMenu-link">' . 'Profile' . '</a></div></div>
								<div class="offCanvasMenu-linkHolder u-ripple ' . (($__vars['uix_myAccountLinks'] == 'alerts') ? 'is-selected' : '') . '"><div class="p-navEl__inner"><a data-nav-id="alerts" href="' . $__templater->func('link', array('account/alerts', ), true) . '" class="offCanvasMenu-link">' . 'Forum Alerts' . '</a></div></div>
								<div class="offCanvasMenu-linkHolder u-ripple ' . (($__vars['uix_myAccountLinks'] == 'settings') ? 'is-selected' : '') . '"><div class="p-navEl__inner"><a data-nav-id="settings" href="' . $__templater->func('link', array('account/preferences', ), true) . '" class="offCanvasMenu-link">' . 'Settings' . '</a></div></div>
							';
	}
	$__finalCompiled .= '
							';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
										' . $__templater->filter($__vars['uix_sidebarNavWidgets'], array(array('raw', array()),), true) . '
									';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
								<div class="uix_sidebarNav__inner__widgets">
									' . $__compilerTemp1 . '
								</div>
							';
	}
	$__finalCompiled .= '
						';
	return $__finalCompiled;
}
),
'nav_entry' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'sidebarNav' => '',
		'navId' => '!',
		'nav' => '!',
		'selected' => false,
		'shortcut' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="p-navEl u-ripple ' . ($__vars['selected'] ? 'is-selected' : '') . '" ' . ($__vars['nav']['children'] ? 'data-has-children="true"' : '') . '>
		';
	if ($__vars['sidebarNav']) {
		$__finalCompiled .= '
			<div class="p-navEl__inner">
				';
	}
	$__finalCompiled .= '
			';
	if ($__vars['nav']['href']) {
		$__finalCompiled .= '
				' . $__templater->callMacro(null, 'nav_link', array(
			'navId' => $__vars['navId'],
			'nav' => $__vars['nav'],
			'class' => 'p-navEl-link ' . ($__vars['nav']['children'] ? 'p-navEl-link--splitMenu' : ''),
			'shortcut' => ($__vars['nav']['children'] ? false : $__vars['shortcut']),
		), $__vars) . '
				';
		if ($__vars['nav']['children']) {
			$__finalCompiled .= '
					<a data-xf-key="' . $__templater->escape($__vars['shortcut']) . '"
					   data-xf-click="menu"
					   data-menu-pos-ref="< .p-navEl"
					   class="p-navEl-splitTrigger"
					   role="button"
					   tabindex="0"
					   aria-label="' . $__templater->filter('Toggle expanded', array(array('for_attr', array()),), true) . '"
					   aria-expanded="false"
					   aria-haspopup="true">
					</a>
				';
		}
		$__finalCompiled .= '
				';
	} else if ($__vars['nav']['children']) {
		$__finalCompiled .= '
				<a data-xf-key="' . $__templater->escape($__vars['shortcut']) . '"
				   class="p-navEl-linkHolder"
				   data-menu-pos-ref="< .p-navEl"
				   data-xf-click="menu"
				   role="button"
				   tabindex="0"
				   aria-expanded="false"
				   aria-haspopup="true">
					' . $__templater->callMacro(null, 'nav_link', array(
			'navId' => $__vars['navId'],
			'nav' => $__vars['nav'],
			'class' => 'p-navEl-link p-navEl-link--menuTrigger',
		), $__vars) . '
				</a>
				';
	} else {
		$__finalCompiled .= '
				' . $__templater->callMacro(null, 'nav_link', array(
			'navId' => $__vars['navId'],
			'nav' => $__vars['nav'],
			'class' => 'p-navEl-link',
			'shortcut' => $__vars['shortcut'],
		), $__vars) . '
			';
	}
	$__finalCompiled .= '
			';
	if ($__vars['nav']['children']) {
		$__finalCompiled .= '
				';
		if (!$__vars['sidebarNav']) {
			$__finalCompiled .= '
					<div class="menu menu--structural" data-menu="menu" aria-hidden="true">
						<div class="menu-content">
							';
			if ($__templater->isTraversable($__vars['nav']['children'])) {
				foreach ($__vars['nav']['children'] AS $__vars['childNavId'] => $__vars['child']) {
					$__finalCompiled .= '
								' . $__templater->callMacro(null, 'nav_menu_entry', array(
						'navId' => $__vars['childNavId'],
						'nav' => $__vars['child'],
					), $__vars) . '
							';
				}
			}
			$__finalCompiled .= '
						</div>
					</div>
				';
		}
		$__finalCompiled .= '
			';
	}
	$__finalCompiled .= '
			';
	if ($__vars['sidebarNav'] AND ($__vars['nav']['children'] AND $__templater->func('property', array('uix_tablinksInSideNav', ), false))) {
		$__finalCompiled .= '
				<a class="uix_sidebarNav--trigger ';
		if ((!$__templater->func('property', array('uix_sideNavCollapsed', ), false)) AND $__vars['selected']) {
			$__finalCompiled .= 'is-expanded';
		}
		$__finalCompiled .= '" rel="nofollow">' . $__templater->fontAwesome('fa-chevron-down', array(
			'class' => 'uix_icon uix_icon--chevron-down',
		)) . '</a>
			';
	}
	$__finalCompiled .= '
			';
	if ($__vars['sidebarNav']) {
		$__finalCompiled .= '
				</div>
		';
	}
	$__finalCompiled .= '
		';
	if ($__templater->func('property', array('uix_tablinksInSideNav', ), false)) {
		$__finalCompiled .= '
			';
		if ($__vars['sidebarNav']) {
			$__finalCompiled .= '
				<div ';
			if ($__vars['nav']['children']) {
				$__finalCompiled .= 'data-menu="false"';
			}
			$__finalCompiled .= ' class="uix_sidebarNav__subNav ';
			if ((!$__templater->func('property', array('uix_sideNavCollapsed', ), false)) AND $__vars['selected']) {
				$__finalCompiled .= 'subNav--expand';
			}
			$__finalCompiled .= '">
			<div class="uix_sidebarNav__subNavInner">
				';
			if ($__templater->isTraversable($__vars['nav']['children'])) {
				foreach ($__vars['nav']['children'] AS $__vars['childNavId'] => $__vars['child']) {
					$__finalCompiled .= '
					' . $__templater->callMacro(null, 'nav_menu_entry', array(
						'navId' => $__vars['childNavId'],
						'nav' => $__vars['child'],
					), $__vars) . '
				';
				}
			}
			$__finalCompiled .= '
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
	return $__finalCompiled;
}
),
'nav_link' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'navId' => '!',
		'nav' => '!',
		'class' => '',
		'titleHtml' => '',
		'shortcut' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['tag'] = ($__vars['nav']['href'] ? 'a' : 'span');
	$__finalCompiled .= '
	<' . $__templater->escape($__vars['tag']) . ' ' . ($__vars['nav']['href'] ? (('href="' . $__templater->escape($__vars['nav']['href'])) . '"') : '') . '
			class="' . $__templater->escape($__vars['class']) . ' ' . $__templater->escape($__vars['nav']['attributes']['class']) . '"
			' . $__templater->func('attributes', array($__vars['nav']['attributes'], array('class', ), ), true) . '
			' . (($__vars['shortcut'] !== false) ? (('data-xf-key="' . $__templater->escape($__vars['shortcut'])) . '"') : '') . '
			data-nav-id="' . $__templater->escape($__vars['navId']) . '">';
	if ($__vars['nav']['icon']) {
		$__finalCompiled .= $__templater->fontAwesome($__templater->escape($__vars['nav']['icon']), array(
		)) . ' ';
	}
	$__finalCompiled .= '<span>' . ($__vars['titleHtml'] ? $__templater->filter($__vars['titleHtml'], array(array('raw', array()),), true) : $__templater->escape($__vars['nav']['title'])) . '</span>';
	if ($__vars['nav']['counter']) {
		$__finalCompiled .= ' <span class="badge badge--highlighted">' . $__templater->filter($__vars['nav']['counter'], array(array('number', array()),), true) . '</span>';
	}
	$__finalCompiled .= '</' . $__templater->escape($__vars['tag']) . '>
';
	return $__finalCompiled;
}
),
'nav_menu_entry' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'navId' => '!',
		'nav' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->callMacro(null, 'nav_link', array(
		'navId' => $__vars['navId'],
		'nav' => $__vars['nav'],
		'class' => 'menu-linkRow u-indentDepth' . $__vars['depth'] . ' js-offCanvasCopy',
	), $__vars) . '
	';
	if ($__vars['nav']['children']) {
		$__finalCompiled .= '
		';
		if ($__templater->isTraversable($__vars['nav']['children'])) {
			foreach ($__vars['nav']['children'] AS $__vars['childNavId'] => $__vars['child']) {
				$__finalCompiled .= '
			' . $__templater->callMacro(null, 'nav_menu_entry', array(
					'navId' => $__vars['childNavId'],
					'nav' => $__vars['child'],
					'depth' => ($__vars['depth'] + 1),
				), $__vars) . '
		';
			}
		}
		$__finalCompiled .= '
		';
		if ($__vars['depth'] == 0) {
			$__finalCompiled .= '
			<hr class="menu-separator" />
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
	$__finalCompiled .= '<!DOCTYPE html>

';
	$__vars['siteName'] = $__vars['xf']['options']['boardTitle'];
	$__finalCompiled .= '
';
	$__vars['h1'] = $__templater->preEscaped($__templater->func('page_h1', array($__vars['siteName'])));
	$__finalCompiled .= '
';
	$__vars['title'] = $__templater->preEscaped($__templater->func('page_title', array('%s | %s', $__vars['xf']['options']['boardTitle'], null)));
	$__finalCompiled .= '
';
	$__vars['description'] = $__templater->preEscaped($__templater->func('page_description'));
	$__finalCompiled .= '

';
	$__vars['uix_htmlClasses'] = $__templater->preEscaped('');
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('uix_pageWidthToggle', ), false) != 'disabled') {
		$__finalCompiled .= '
	' . '
	';
		$__vars['uix_htmlClasses'] = $__templater->preEscaped($__templater->escape($__vars['uix_htmlClasses']) . ' uix_page--' . $__templater->escape($__vars['uix_pageWidth']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('uix_navigationType', ), false) == 'sidebarNav') {
		$__finalCompiled .= '
	' . '
	';
		if (!$__vars['uix_sidebarNavCollapsed']) {
			$__finalCompiled .= '
		';
			$__vars['uix_htmlClasses'] = $__templater->preEscaped($__templater->escape($__vars['uix_htmlClasses']) . ' sidebarNav--active');
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('uix_collapsibleSidebar', ), false)) {
		$__finalCompiled .= '
	' . '
	';
		if ($__vars['uix_sidebarCollapsed']) {
			$__finalCompiled .= '
		';
			$__vars['uix_htmlClasses'] = $__templater->preEscaped($__templater->escape($__vars['uix_htmlClasses']) . ' uix_sidebarCollapsed');
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['uix_showWelcomeSection']) {
		$__finalCompiled .= '
	';
		$__vars['uix_htmlClasses'] = $__templater->preEscaped($__templater->escape($__vars['uix_htmlClasses']) . ' uix_hasWelcomeSection');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . '
';
	$__vars['uix_subNavContentStatic'] = '0';
	$__finalCompiled .= '

' . '

' . '

';
	$__compilerTemp1 = '';
	if ($__vars['sidebar']) {
		$__compilerTemp1 .= '
		';
		if ($__templater->func('property', array('uix_collapsibleSidebar', ), false) AND $__vars['uix_canCollapseSidebar']) {
			$__compilerTemp1 .= '
			<a class="uix_sidebarTrigger__component uix_sidebarTrigger ' . (($__templater->func('property', array('uix_sidebarTriggerPosition', ), false) == 'sectionLinks') ? 'p-navgroup-link' : 'button') . '" data-xf-init="tooltip" title="' . 'Sidebar' . '" rel="nofollow">
				' . $__templater->fontAwesome('fa-ellipsis-v', array(
				'class' => 'mdi mdi-dots-vertical',
			)) . '
				<span class="uix_sidebarTrigger--phrase">' . 'Sidebar' . '</span>
			</a>
		';
		}
		$__compilerTemp1 .= '
		';
		if ($__templater->func('property', array('uix_sidebarMobileCanvas', ), false)) {
			$__compilerTemp1 .= '
			<a class="uix_sidebarCanvasTrigger uix_sidebarTrigger__component p-navgroup-link" data-xf-init="tooltip" title="' . 'Sidebar' . '">
				' . $__templater->fontAwesome('fa-ellipsis-v', array(
				'class' => 'mdi mdi-dots-vertical',
			)) . '
				<span class="uix_sidebarTrigger--phrase">' . 'Sidebar' . '</span>
			</a>
		';
		}
		$__compilerTemp1 .= '
	';
	}
	$__vars['uix_sidebarTrigger__content'] = $__templater->preEscaped('
	' . $__compilerTemp1 . '
');
	$__finalCompiled .= '

' . '

' . '

';
	if (!$__vars['uix_hideNavigation']) {
		$__finalCompiled .= '
	';
		$__compilerTemp2 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canSearch', array())) {
			$__compilerTemp2 .= '
			<div class="uix_searchBar">
				<div class="uix_searchBarInner">
					<form action="' . $__templater->func('link', array('search/search', ), true) . '" method="post" class="uix_searchForm" data-xf-init="quick-search">
						<a class="uix_search--close">
							' . $__templater->fontAwesome('fa-window-close', array(
				'class' => 'uix_icon uix_icon--close',
			)) . '
						</a>
						' . $__templater->formTextBox(array(
				'autocomplete' => 'off',
				'class' => 'js-uix_syncValue uix_searchInput uix_searchDropdown__trigger',
				'data-uixsync' => 'search',
				'name' => 'keywords',
				'placeholder' => 'Search' . $__vars['xf']['language']['ellipsis'],
				'aria-label' => 'Search',
				'data-menu-autofocus' => 'true',
			)) . '
						<a href="' . $__templater->func('link', array('search', ), true) . '"
						   class="uix_search--settings u-ripple"
						   data-xf-key="' . $__templater->filter('/', array(array('for_attr', array()),), true) . '"
						   aria-label="' . $__templater->filter('Search', array(array('for_attr', array()),), true) . '"
						   aria-expanded="false"
						   aria-haspopup="true"
						   title="' . $__templater->filter('Search', array(array('for_attr', array()),), true) . '">
							' . $__templater->fontAwesome('fa-cog', array(
				'class' => 'uix_icon uix_icon--settings',
			)) . '
						</a>
						<span class="';
			if ($__templater->func('property', array('uix_searchButton', ), false)) {
				$__compilerTemp2 .= 'uix_search--submit';
			}
			$__compilerTemp2 .= ' uix_searchIcon">
							' . $__templater->fontAwesome('fa-search', array(
				'class' => 'uix_icon uix_icon--search',
			)) . '
						</span>
						' . $__templater->func('csrf_input') . '
					</form>
				</div>

				';
			if ($__templater->func('property', array('uix_searchIconBehavior', ), false) != 'dropdown') {
				$__compilerTemp2 .= '
					<a class="uix_searchIconTrigger p-navgroup-link p-navgroup-link--iconic p-navgroup-link--search u-ripple"
					   aria-label="' . 'Search' . '"
					   aria-expanded="false"
					   aria-haspopup="true"
					   title="' . 'Search' . '">
						<i aria-hidden="true"></i>
					</a>
				';
			}
			$__compilerTemp2 .= '

				';
			if ($__templater->func('property', array('uix_searchIconBehavior', ), false) != 'expand') {
				$__compilerTemp2 .= '
					<a href="' . $__templater->func('link', array('search', ), true) . '"
					   class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--search u-ripple js-uix_minimalSearch__target"
					   data-xf-click="menu"
					   aria-label="' . 'Search' . '"
					   aria-expanded="false"
					   aria-haspopup="true"
					   title="' . 'Search' . '">
						<i aria-hidden="true"></i>
					</a>
				';
			}
			$__compilerTemp2 .= '

				<div class="menu menu--structural menu--wide" data-menu="menu" aria-hidden="true">
					<form action="' . $__templater->func('link', array('search/search', ), true) . '" method="post"
						  class="menu-content"
						  data-xf-init="quick-search">
						<h3 class="menu-header">' . 'Search' . '</h3>
						' . '
						<div class="menu-row">
							';
			if ($__vars['searchConstraints']) {
				$__compilerTemp2 .= '
								<div class="inputGroup inputGroup--joined">
									' . $__templater->formTextBox(array(
					'name' => 'keywords',
					'class' => 'js-uix_syncValue',
					'data-uixsync' => 'search',
					'placeholder' => 'Search' . $__vars['xf']['language']['ellipsis'],
					'aria-label' => 'Search',
					'data-menu-autofocus' => 'true',
				)) . '
									';
				$__compilerTemp3 = array(array(
					'value' => '',
					'label' => 'Everywhere',
					'_type' => 'option',
				));
				if ($__templater->isTraversable($__vars['searchConstraints'])) {
					foreach ($__vars['searchConstraints'] AS $__vars['constraintName'] => $__vars['constraint']) {
						$__compilerTemp3[] = array(
							'value' => $__templater->filter($__vars['constraint'], array(array('json', array()),), false),
							'label' => $__templater->escape($__vars['constraintName']),
							'_type' => 'option',
						);
					}
				}
				$__compilerTemp2 .= $__templater->formSelect(array(
					'name' => 'constraints',
					'class' => 'js-quickSearch-constraint',
					'aria-label' => 'Search within',
				), $__compilerTemp3) . '
								</div>
								';
			} else {
				$__compilerTemp2 .= '
								' . $__templater->formTextBox(array(
					'name' => 'keywords',
					'class' => 'js-uix_syncValue',
					'data-uixsync' => 'search',
					'placeholder' => 'Search' . $__vars['xf']['language']['ellipsis'],
					'aria-label' => 'Search',
					'data-menu-autofocus' => 'true',
				)) . '
							';
			}
			$__compilerTemp2 .= '
						</div>

						' . '
						<div class="menu-row">
							' . $__templater->formCheckBox(array(
				'standalone' => 'true',
			), array(array(
				'name' => 'c[title_only]',
				'label' => 'Search titles only',
				'_type' => 'option',
			))) . '
						</div>
						' . '
						<div class="menu-row">
							<div class="inputGroup">
								<span class="inputGroup-text" id="ctrl_search_menu_by_member">' . 'By' . $__vars['xf']['language']['label_separator'] . '</span>
								<input type="text" class="input" name="c[users]" data-xf-init="auto-complete" placeholder="' . $__templater->filter('Member', array(array('for_attr', array()),), true) . '" aria-labelledby="ctrl_search_menu_by_member" />
							</div>
						</div>
						<div class="menu-footer">
							<span class="menu-footer-controls">
								' . $__templater->button('', array(
				'type' => 'submit',
				'class' => 'button--primary',
				'icon' => 'search',
			), '', array(
			)) . '
								' . $__templater->button('Advanced search' . $__vars['xf']['language']['ellipsis'], array(
				'href' => $__templater->func('link', array('search', ), false),
			), '', array(
			)) . '
							</span>
						</div>

						' . $__templater->func('csrf_input') . '
					</form>
				</div>


				<div class="menu menu--structural menu--wide uix_searchDropdown__menu" aria-hidden="true">
					<form action="' . $__templater->func('link', array('search/search', ), true) . '" method="post"
						  class="menu-content"
						  data-xf-init="quick-search">
						' . '
						';
			if ($__vars['searchConstraints']) {
				$__compilerTemp2 .= '
							<div class="menu-row">

								<div class="inputGroup">
									<input name="keywords"
										   class="js-uix_syncValue"
										   data-uixsync="search"
										   placeholder="' . 'Search' . $__vars['xf']['language']['ellipsis'] . '"
										   aria-label="' . 'Search' . '"
										   type="hidden" />
									';
				$__compilerTemp4 = array(array(
					'value' => '',
					'label' => 'Everywhere',
					'_type' => 'option',
				));
				if ($__templater->isTraversable($__vars['searchConstraints'])) {
					foreach ($__vars['searchConstraints'] AS $__vars['constraintName'] => $__vars['constraint']) {
						$__compilerTemp4[] = array(
							'value' => $__templater->filter($__vars['constraint'], array(array('json', array()),), false),
							'label' => $__templater->escape($__vars['constraintName']),
							'_type' => 'option',
						);
					}
				}
				$__compilerTemp2 .= $__templater->formSelect(array(
					'name' => 'constraints',
					'class' => 'js-quickSearch-constraint',
					'aria-label' => 'Search within',
				), $__compilerTemp4) . '
								</div>
							</div>
							';
			} else {
				$__compilerTemp2 .= '
							<input name="keywords"
								   class="js-uix_syncValue"
								   data-uixsync="search"
								   placeholder="' . 'Search' . $__vars['xf']['language']['ellipsis'] . '"
								   aria-label="' . 'Search' . '"
								   type="hidden" />
						';
			}
			$__compilerTemp2 .= '

						' . '
						<div class="menu-row">
							' . $__templater->formCheckBox(array(
				'standalone' => 'true',
			), array(array(
				'name' => 'c[title_only]',
				'label' => 'Search titles only',
				'_type' => 'option',
			))) . '
						</div>
						' . '
						<div class="menu-row">
							<div class="inputGroup">
								<span class="inputGroup-text">' . 'By' . $__vars['xf']['language']['label_separator'] . '</span>
								<input class="input" name="c[users]" data-xf-init="auto-complete" placeholder="' . 'Member' . '" />
							</div>
						</div>
						<div class="menu-footer">
							<span class="menu-footer-controls">
								' . $__templater->button('', array(
				'type' => 'submit',
				'class' => 'button--primary',
				'icon' => 'search',
			), '', array(
			)) . '
								' . $__templater->button('Advanced' . $__vars['xf']['language']['ellipsis'], array(
				'href' => $__templater->func('link', array('search', ), false),
				'rel' => 'nofollow',
			), '', array(
			)) . '
							</span>
						</div>

						' . $__templater->func('csrf_input') . '
					</form>
				</div>
			</div>
		';
		}
		$__vars['uix_search__component'] = $__templater->preEscaped('
		' . $__compilerTemp2 . '
	');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . '

' . '

';
	if (!$__templater->func('property', array('uix_removeWhatsNewButtons', ), false)) {
		$__finalCompiled .= '
';
		$__vars['uix_whatsNew__component'] = $__templater->preEscaped('
	<a href="' . $__templater->func('link', array('whats-new', ), true) . '"
	   class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--whatsnew"
	   title="' . $__templater->filter('What\'s new', array(array('for_attr', array()),), true) . '">
		<i aria-hidden="true"></i>
		<span class="p-navgroup-linkText">' . 'What\'s new' . '</span>
	</a>
');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . '

' . '

';
	$__compilerTemp5 = '';
	if ($__vars['template'] != 'login') {
		$__compilerTemp5 .= '
		';
		if ($__templater->func('property', array('uix_loginStyle', ), false) == 'dropdown') {
			$__compilerTemp5 .= '
			<a href="' . $__templater->func('link', array('login', ), true) . '" class="p-navgroup-link p-navgroup-link--textual p-navgroup-link--logIn" data-xf-click="menu">
				<i></i>
				<span class="p-navgroup-linkText">' . 'Log in' . '</span>
			</a>
			<div class="menu menu--structural menu--medium" data-menu="menu" aria-hidden="true" data-href="' . $__templater->func('link', array('login', ), true) . '"></div>
		';
		} else if ($__templater->func('property', array('uix_loginStyle', ), false) == 'modal') {
			$__compilerTemp5 .= '
			<a href="' . $__templater->func('link', array('login', ), true) . '" class="p-navgroup-link u-ripple p-navgroup-link--textual p-navgroup-link--logIn" data-xf-click="overlay" data-follow-redirects="on">
				<i></i>
				<span class="p-navgroup-linkText">' . 'Log in' . '</span>
			</a>
		';
		} else if ($__templater->func('property', array('uix_loginStyle', ), false) == 'link') {
			$__compilerTemp5 .= '
			<a href="' . $__templater->func('link', array('login', ), true) . '" class="p-navgroup-link u-ripple p-navgroup-link--textual p-navgroup-link--logIn" data-follow-redirects="on">
				<i></i>
				<span class="p-navgroup-linkText">' . 'Log in' . '</span>
			</a>
		';
		} else if ($__templater->func('property', array('uix_loginStyle', ), false) == 'slidingPanel') {
			$__compilerTemp5 .= '
			<a href="' . $__templater->func('link', array('login', ), true) . '" id="uix_loginPanel--trigger" class="p-navgroup-link u-ripple p-navgroup-link--textual p-navgroup-link--logIn" rel="nofollow" data-follow-redirects="on">
				<i></i>
				<span class="p-navgroup-linkText">' . 'Log in' . '</span>
			</a>
		';
		}
		$__compilerTemp5 .= '
	';
	}
	$__compilerTemp6 = '';
	if ($__vars['template'] != 'register_form') {
		$__compilerTemp6 .= '
		';
		if ($__templater->func('property', array('uix_loginStyle', ), false) != 'link') {
			$__compilerTemp6 .= '
			<a href="' . $__templater->func('link', array('register', ), true) . '" class="p-navgroup-link u-ripple p-navgroup-link--textual p-navgroup-link--register" data-xf-click="overlay" data-follow-redirects="on">
				<i></i>
				<span class="p-navgroup-linkText">' . 'Register' . '</span>
			</a>
		';
		} else {
			$__compilerTemp6 .= '
			<a href="' . $__templater->func('link', array('register', ), true) . '" class="p-navgroup-link u-ripple p-navgroup-link--textual p-navgroup-link--register" data-follow-redirects="on">
				<i></i>
				<span class="p-navgroup-linkText">' . 'Register' . '</span>
			</a>
		';
		}
		$__compilerTemp6 .= '
	';
	}
	$__vars['uix_loginTabs__component'] = $__templater->preEscaped('
	' . $__compilerTemp5 . '
	' . $__compilerTemp6 . '
');
	$__finalCompiled .= '

' . '

' . '

';
	if (!$__vars['uix_hideNavigation']) {
		$__finalCompiled .= '
	';
		$__compilerTemp7 = '';
		if (($__vars['xf']['visitor']['user_state'] == 'rejected') OR ($__vars['xf']['visitor']['user_state'] == 'disabled')) {
			$__compilerTemp7 .= '
			<a href="' . $__templater->func('link', array('account', ), true) . '"
			   class="p-navgroup-link u-ripple p-navgroup-link--iconic p-navgroup-link--user">
				' . $__templater->func('avatar', array($__vars['xf']['visitor'], 'xxs', false, array(
				'href' => '',
			))) . '
				<span class="p-navgroup-linkText">' . $__templater->escape($__vars['xf']['visitor']['username']) . '</span>
			</a>

			<a href="' . $__templater->func('link', array('logout', null, array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '" class="p-navgroup-link">
				<span class="p-navgroup-linkText">' . 'Log out' . '</span>
			</a>
			';
		} else {
			$__compilerTemp7 .= '
			<a href="' . $__templater->func('link', array('account', ), true) . '"
			   class="p-navgroup-link u-ripple p-navgroup-link--iconic p-navgroup-link--user"
			   data-xf-click="menu"
			   data-xf-key="' . $__templater->filter('m', array(array('for_attr', array()),), true) . '"
			   data-menu-pos-ref="< .p-navgroup"
			   aria-expanded="false"
			   aria-haspopup="true">
				' . $__templater->func('avatar', array($__vars['xf']['visitor'], 'xxs', false, array(
				'href' => '',
			))) . '
				<span class="p-navgroup-linkText">' . $__templater->escape($__vars['xf']['visitor']['username']) . '</span>
			</a>
			<div class="menu menu--structural menu--wide menu--account" data-menu="menu" aria-hidden="true"
				 data-href="' . $__templater->func('link', array('account/visitor-menu', ), true) . '"
				 data-load-target=".js-visitorMenuBody">
				<div class="menu-content js-visitorMenuBody">
					<div class="menu-row">
						' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '
					</div>
				</div>
			</div>

			<a href="' . $__templater->func('link', array('conversations', ), true) . '"
			   class="p-navgroup-link u-ripple p-navgroup-link--iconic p-navgroup-link--conversations js-badge--conversations badgeContainer' . ($__vars['xf']['visitor']['conversations_unread'] ? ' badgeContainer--highlighted' : '') . '"
			   data-badge="' . $__templater->filter($__vars['xf']['visitor']['conversations_unread'], array(array('number', array()),), true) . '"
			   data-xf-click="menu"
			   data-xf-key="' . $__templater->filter(',', array(array('for_attr', array()),), true) . '"
			   data-menu-pos-ref="< .p-navgroup"
			   aria-label="' . $__templater->filter('Inbox', array(array('for_attr', array()),), true) . '"
			   aria-expanded="false"
			   aria-haspopup="true">
				<i aria-hidden="true"></i>
				<span class="p-navgroup-linkText">' . '' . '</span>
			</a>
			<div class="menu menu--structural menu--medium" data-menu="menu" aria-hidden="true"
				 data-href="' . $__templater->func('link', array('conversations/popup', ), true) . '"
				 data-nocache="true"
				 data-load-target=".js-convMenuBody">
				<div class="menu-content">
					<h3 class="menu-header">' . 'Conversations' . '</h3>
					<div class="js-convMenuBody">
						<div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
					</div>
					<div class="menu-footer menu-footer--split">
						<span class="menu-footer-main">
							<a href="' . $__templater->func('link', array('conversations', ), true) . '">' . 'Show all' . $__vars['xf']['language']['ellipsis'] . '</a>
						</span>
						';
			if ($__templater->method($__vars['xf']['visitor'], 'canStartConversation', array())) {
				$__compilerTemp7 .= '
							<span class="menu-footer-opposite">
								<a href="' . $__templater->func('link', array('conversations/add', ), true) . '">' . 'Start a new conversation' . '</a>
							</span>
						';
			}
			$__compilerTemp7 .= '
					</div>
				</div>
			</div>

			<a href="' . $__templater->func('link', array('account/alerts', ), true) . '"
			   class="p-navgroup-link u-ripple p-navgroup-link--iconic p-navgroup-link--alerts js-badge--alerts badgeContainer' . ($__vars['xf']['visitor']['alerts_unread'] ? ' badgeContainer--highlighted' : '') . '"
			   data-badge="' . $__templater->filter($__vars['xf']['visitor']['alerts_unread'], array(array('number', array()),), true) . '"
			   data-xf-click="menu"
			   data-xf-key="' . $__templater->filter('.', array(array('for_attr', array()),), true) . '"
			   data-menu-pos-ref="< .p-navgroup"
			   aria-label="' . $__templater->filter('Forum Alerts', array(array('for_attr', array()),), true) . '"
			   aria-expanded="false"
			   aria-haspopup="true">
				<i aria-hidden="true"></i>
				<span class="p-navgroup-linkText">' . '' . '</span>
			</a>
			<div class="menu menu--structural menu--medium" data-menu="menu" aria-hidden="true"
				 data-href="' . $__templater->func('link', array('account/alerts-popup', ), true) . '"
				 data-nocache="true"
				 data-load-target=".js-alertsMenuBody">
				<div class="menu-content">
					<h3 class="menu-header">' . 'Forum Alerts' . '</h3>
					<div class="js-alertsMenuBody">
						<div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
					</div>
					<div class="menu-footer menu-footer--split">
						<span class="menu-footer-main">
							<a href="' . $__templater->func('link', array('account/alerts', ), true) . '">' . 'Show all' . $__vars['xf']['language']['ellipsis'] . '</a>
						</span>
						<span class="menu-footer-opposite">
							<a href="' . $__templater->func('link', array('account/preferences', ), true) . '">' . 'Preferences' . '</a>
						</span>
					</div>
				</div>
			</div>
		';
		}
		$__vars['uix_userTabs__component'] = $__templater->preEscaped('
		' . $__compilerTemp7 . '
	');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . '

';
	$__vars['uix_socialMediaContent'] = $__templater->preEscaped('
	' . $__templater->includeTemplate('uix_socialMedia', $__vars) . '
');
	$__finalCompiled .= '

' . '

' . '

';
	if (!$__vars['uix_hideNavigation']) {
		$__finalCompiled .= '
	' . '
';
	}
	$__finalCompiled .= '

' . '

';
	$__compilerTemp8 = '';
	if ((!$__vars['uix_hidePageTitle']) OR ($__vars['pageAction'] AND ((!$__templater->func('property', array('uix_pageActionBreadcrumb', ), false)) AND ((!$__templater->func('property', array('uix_removePageAction', ), false)) OR ((!$__templater->func('in_array', array($__vars['template'], array('forum_list', 'forum_new_posts', 'thtopics_forum_newest_posts', 'thtrending_trending_view', 'thtopics_forum_latest_threads', 'thtopics_topic_list', ), ), false))))))) {
		$__compilerTemp8 .= '
		';
		$__compilerTemp9 = '';
		$__compilerTemp9 .= '
						';
		$__compilerTemp10 = '';
		$__compilerTemp10 .= '
									';
		$__compilerTemp11 = '';
		$__compilerTemp11 .= '
												';
		if (!$__vars['noH1']) {
			$__compilerTemp11 .= '
													<h1 class="p-title-value">' . $__templater->escape($__vars['h1']) . '</h1>
												';
		}
		$__compilerTemp11 .= '
											';
		if (strlen(trim($__compilerTemp11)) > 0) {
			$__compilerTemp10 .= '
										<div class="p-title ' . ($__vars['noH1'] ? 'p-title--noH1' : '') . '">
											' . $__compilerTemp11 . '
										</div>
									';
		}
		$__compilerTemp10 .= '

									';
		if (!$__templater->test($__vars['description'], 'empty', array())) {
			$__compilerTemp10 .= '
										<div class="p-description">' . $__templater->escape($__vars['description']) . '</div>
									';
		}
		$__compilerTemp10 .= '
								';
		if (strlen(trim($__compilerTemp10)) > 0) {
			$__compilerTemp9 .= '
							<div class="uix_headerInner">
								' . $__compilerTemp10 . '
							</div>
						';
		}
		$__compilerTemp9 .= '
						';
		$__compilerTemp12 = '';
		$__compilerTemp12 .= '
									';
		if ((!$__templater->func('property', array('uix_pageActionBreadcrumb', ), false)) AND ((!$__templater->func('property', array('uix_removePageAction', ), false)) OR ($__vars['template'] != 'forum_list'))) {
			$__compilerTemp12 .= '
										';
			$__compilerTemp13 = '';
			$__compilerTemp13 .= (isset($__templater->pageParams['pageAction']) ? $__templater->pageParams['pageAction'] : '');
			if (strlen(trim($__compilerTemp13)) > 0) {
				$__compilerTemp12 .= '
											<div class="p-title-pageAction">' . $__compilerTemp13 . '</div>
										';
			}
			$__compilerTemp12 .= '
									';
		}
		$__compilerTemp12 .= '
									' . $__templater->callMacro(null, 'uix_sidebarTrigger__component', array(
			'content' => $__vars['uix_sidebarTrigger__content'],
			'location' => 'titlebar',
		), $__vars) . '
								';
		if (strlen(trim($__compilerTemp12)) > 0) {
			$__compilerTemp9 .= '
							<div class="uix_headerInner--opposite">
								' . $__compilerTemp12 . '
							</div>
						';
		}
		$__compilerTemp9 .= '
					';
		if (!$__templater->test($__vars['headerHtml'], 'empty', array())) {
			$__compilerTemp8 .= '
			<div class="p-body-header">
				<div class="pageContent">
					' . $__templater->filter($__vars['headerHtml'], array(array('raw', array()),), true) . '
				</div>
			</div>
			';
		} else if (strlen(trim($__compilerTemp9)) > 0) {
			$__compilerTemp8 .= '
			<div class="p-body-header">
				<div class="pageContent">
					' . $__compilerTemp9 . '
				</div>
			</div>
		';
		}
		$__compilerTemp8 .= '
	';
	}
	$__vars['uix_titlebar__component'] = $__templater->preEscaped('
	' . $__compilerTemp8 . '
');
	$__finalCompiled .= '

' . '

' . '

';
	$__compilerTemp14 = '';
	if ($__vars['sidebar']) {
		$__compilerTemp14 .= '
		<div uix_component="MainSidebar" class="p-body-sidebar">
			<div data-ocm-class="offCanvasMenu-backdrop"></div>
			<div class="uix_sidebarInner ' . (($__templater->func('property', array('uix_stickySidebar', ), false) == 'top') ? ' uix_stickyBodyElement' : '') . '">
				<div class="uix_sidebar--scroller">
					' . $__templater->callAdsMacro('container_sidebar_above', array(), $__vars) . '
					' . $__templater->callMacro('uix_welcomeSection', 'welcomeSection', array(
			'location' => 'sidebar',
			'showWelcomeSection' => $__vars['uix_showWelcomeSection'],
		), $__vars) . '
					';
		if ($__templater->isTraversable($__vars['sidebar'])) {
			foreach ($__vars['sidebar'] AS $__vars['sidebarHtml']) {
				$__compilerTemp14 .= '
						' . $__templater->escape($__vars['sidebarHtml']) . '
					';
			}
		}
		$__compilerTemp14 .= '
					' . $__templater->callAdsMacro('container_sidebar_below', array(), $__vars) . '
				</div>
			</div>
		</div>
	';
	}
	$__vars['uix_sidebar__component'] = $__templater->preEscaped('
	' . $__compilerTemp14 . '
');
	$__finalCompiled .= '

' . '

' . '

';
	$__compilerTemp15 = '';
	if ($__templater->func('property', array('publicLogoUrl', ), false) OR $__templater->func('property', array('publicLogoUrl2x', ), false)) {
		$__compilerTemp15 .= '
				<img src="' . $__templater->func('base_url', array($__templater->func('property', array('publicLogoUrl', ), false), ), true) . '"
					 alt="' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '"
					 ' . ($__templater->func('property', array('publicLogoUrl2x', ), false) ? (('srcset="' . $__templater->func('base_url', array($__templater->func('property', array('publicLogoUrl2x', ), false), ), true)) . ' 2x"') : '') . ' />
				';
	} else {
		$__compilerTemp15 .= '
				<div class="uix_logo--text">';
		if ($__templater->func('property', array('uix_logoIcon', ), false)) {
			$__compilerTemp15 .= '<i class="' . $__templater->func('property', array('uix_logoIcon', ), true) . ' uix_logoIcon"></i>';
		}
		$__compilerTemp15 .= $__templater->func('property', array('uix_logoText', ), true) . '</div>
			';
	}
	$__compilerTemp16 = '';
	if ($__templater->func('property', array('uix_logoSmall', ), false)) {
		$__compilerTemp16 .= '
			<a class="uix_logoSmall" href="' . (($__vars['xf']['options']['logoLink'] AND $__vars['xf']['homePageUrl']) ? $__templater->escape($__vars['xf']['homePageUrl']) : $__templater->func('link', array('index', ), true)) . '">
				<img src="' . $__templater->func('base_url', array($__templater->func('property', array('uix_logoSmall', ), false), ), true) . '"
					 alt="' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '"
					 />
			</a>
		';
	}
	$__vars['uix_logo__component'] = $__templater->preEscaped('
	<div class="p-header-logo p-header-logo--image">
		<a class="uix_logo" href="' . (($__vars['xf']['options']['logoLink'] AND $__vars['xf']['homePageUrl']) ? $__templater->escape($__vars['xf']['homePageUrl']) : $__templater->func('link', array('index', ), true)) . '">
			' . $__compilerTemp15 . '
		</a>
		' . $__compilerTemp16 . '
	</div>
');
	$__finalCompiled .= '

' . '

' . '

';
	$__compilerTemp17 = '';
	$__compilerTemp18 = '';
	$__compilerTemp18 .= '
					' . $__templater->callMacro(null, 'uix_socialMedia__component', array(
		'content' => $__vars['uix_socialMediaContent'],
		'location' => 'staffBarLeft',
	), $__vars) . '
					';
	$__compilerTemp19 = '';
	$__compilerTemp19 .= '
									';
	if ($__vars['xf']['visitor']['is_moderator'] AND $__vars['xf']['session']['unapprovedCounts']['total']) {
		$__compilerTemp19 .= '
										<a href="' . $__templater->func('link', array('approval-queue', ), true) . '" class="p-staffBar-link badgeContainer badgeContainer--highlighted" data-badge="' . $__templater->filter($__vars['xf']['session']['unapprovedCounts']['total'], array(array('number', array()),), true) . '">
											' . 'Approval queue' . '
										</a>
									';
	}
	$__compilerTemp19 .= '

									';
	if ($__vars['xf']['visitor']['is_moderator'] AND ((!$__vars['xf']['options']['reportIntoForumId']) AND $__vars['xf']['session']['reportCounts']['total'])) {
		$__compilerTemp19 .= '
										<a href="' . $__templater->func('link', array('reports', ), true) . '"
										   class="p-staffBar-link badgeContainer badgeContainer--visible ' . ((($__vars['xf']['session']['reportCounts']['total'] AND ($__vars['xf']['session']['reportCounts']['lastBuilt'] > $__vars['xf']['session']['reportLastRead'])) OR $__vars['xf']['session']['reportCounts']['assigned']) ? ' badgeContainer--highlighted' : '') . '"
										   data-badge="' . ($__vars['xf']['session']['reportCounts']['assigned'] ? (($__templater->filter($__vars['xf']['session']['reportCounts']['assigned'], array(array('number', array()),), true) . ' / ') . $__templater->filter($__vars['xf']['session']['reportCounts']['total'], array(array('number', array()),), true)) : $__templater->filter($__vars['xf']['session']['reportCounts']['total'], array(array('number', array()),), true)) . '"
										   title="' . ($__vars['xf']['session']['reportCounts']['lastBuilt'] ? (($__templater->filter('Last report update' . $__vars['xf']['language']['label_separator'], array(array('for_attr', array()),), true) . ' ') . $__templater->func('date_time', array($__vars['xf']['session']['reportCounts']['lastBuilt'], ), true)) : '') . '">' . 'Reports' . '</a>
									';
	}
	$__compilerTemp19 .= '

									';
	$__compilerTemp20 = '';
	$__compilerTemp20 .= '
													' . '
													';
	if ($__vars['xf']['visitor']['is_moderator']) {
		$__compilerTemp20 .= '
														<a href="' . $__templater->func('link', array('approval-queue', ), true) . '" class="menu-linkRow">' . 'Approval queue' . '</a>
													';
	}
	$__compilerTemp20 .= '
													';
	if ($__vars['xf']['visitor']['is_moderator'] AND (!$__vars['xf']['options']['reportIntoForumId'])) {
		$__compilerTemp20 .= '
														<a href="' . $__templater->func('link', array('reports', ), true) . '" class="menu-linkRow" title="' . ($__vars['xf']['session']['reportCounts']['lastBuilt'] ? ('Last report update: ' . $__templater->func('date_time', array($__vars['xf']['session']['reportCounts']['lastBuilt'], ), true)) : '') . '">' . 'Reports' . '</a>
													';
	}
	$__compilerTemp20 .= '
													' . '
												';
	if (strlen(trim($__compilerTemp20)) > 0) {
		$__compilerTemp19 .= '
										<a class="p-staffBar-link menuTrigger" data-xf-key="alt+m" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Moderator' . '</a>
										<div class="menu" data-menu="menu" aria-hidden="true">
											<div class="menu-content">
												<h4 class="menu-header">' . 'Moderator tools' . '</h4>
												' . $__compilerTemp20 . '
											</div>
										</div>
									';
	}
	$__compilerTemp19 .= '

									';
	if ($__vars['xf']['visitor']['is_admin']) {
		$__compilerTemp19 .= '
										<a href="' . $__templater->func('base_url', array('admin.php', ), true) . '" class="p-staffBar-link" target="_blank">' . 'Admin' . '</a>
									';
	}
	$__compilerTemp19 .= '

									';
	$__compilerTemp21 = '';
	$__compilerTemp21 .= '
													' . '
													';
	if ($__vars['xf']['visitor']['is_admin']) {
		$__compilerTemp21 .= '
														<a href="' . $__templater->func('base_url', array('admin.php', ), true) . '" target="_blank" class="menu-linkRow">' . 'Admin' . '</a>
													';
	}
	$__compilerTemp21 .= '
													';
	if ($__vars['xf']['visitor']['is_moderator']) {
		$__compilerTemp21 .= '
														<a href="' . $__templater->func('link', array('approval-queue', ), true) . '" class="menu-linkRow">' . 'Approval queue' . '</a>
													';
	}
	$__compilerTemp21 .= '
													';
	if ($__vars['xf']['visitor']['is_moderator'] AND (!$__vars['xf']['options']['reportIntoForumId'])) {
		$__compilerTemp21 .= '
														<a href="' . $__templater->func('link', array('reports', ), true) . '" class="menu-linkRow" title="' . ($__vars['xf']['session']['reportCounts']['lastBuilt'] ? ('Last report update: ' . $__templater->func('date_time', array($__vars['xf']['session']['reportCounts']['lastBuilt'], ), true)) : '') . '">' . 'Reports' . '</a>
													';
	}
	$__compilerTemp21 .= '
													';
	if ($__vars['xf']['visitor']['is_moderator'] AND $__vars['xf']['session']['unapprovedCounts']['total']) {
		$__compilerTemp21 .= '
														<a href="' . $__templater->func('link', array('approval-queue', ), true) . '" class="menu-linkRow badgeContainer badgeContainer--highlighted" data-badge="' . $__templater->filter($__vars['xf']['session']['unapprovedCounts']['total'], array(array('number', array()),), true) . '">
															' . 'Approval queue' . '
														</a>
													';
	}
	$__compilerTemp21 .= '

													';
	if ($__vars['xf']['visitor']['is_moderator'] AND ((!$__vars['xf']['options']['reportIntoForumId']) AND $__vars['xf']['session']['reportCounts']['total'])) {
		$__compilerTemp21 .= '
														<a href="' . $__templater->func('link', array('reports', ), true) . '"
														   class="menu-linkRow badgeContainer badgeContainer--visible ' . ((($__vars['xf']['session']['reportCounts']['total'] AND ($__vars['xf']['session']['reportCounts']['lastBuilt'] > $__vars['xf']['session']['reportLastRead'])) OR $__vars['xf']['session']['reportCounts']['assigned']) ? ' badgeContainer--highlighted' : '') . '"
														   data-badge="' . ($__vars['xf']['session']['reportCounts']['assigned'] ? (($__templater->filter($__vars['xf']['session']['reportCounts']['assigned'], array(array('number', array()),), true) . ' / ') . $__templater->filter($__vars['xf']['session']['reportCounts']['total'], array(array('number', array()),), true)) : $__templater->filter($__vars['xf']['session']['reportCounts']['total'], array(array('number', array()),), true)) . '"
														   title="' . ($__vars['xf']['session']['reportCounts']['lastBuilt'] ? ('Last report update' . $__templater->func('date_time', array($__vars['xf']['session']['reportCounts']['lastBuilt'], ), true)) : '') . '">
															' . 'Reports' . '
														</a>
													';
	}
	$__compilerTemp21 .= '
													' . '
												';
	if (strlen(trim($__compilerTemp21)) > 0) {
		$__compilerTemp19 .= '
										<a href="' . $__templater->func('base_url', array('admin.php', ), true) . '" target="_blank" class="p-staffBar-link menuTrigger uix_adminTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">
											';
		if ($__vars['xf']['visitor']['is_admin']) {
			$__compilerTemp19 .= '
												' . 'Admin' . '
												';
		} else {
			$__compilerTemp19 .= '
												' . 'Moderator' . '
											';
		}
		$__compilerTemp19 .= '
										</a>
										<div class="menu" data-menu="menu" aria-hidden="true">
											<div class="menu-content">
												<h4 class="menu-header">' . 'Moderator tools' . '</h4>
												' . $__compilerTemp21 . '
											</div>
										</div>
									';
	}
	$__compilerTemp19 .= '
								';
	if (strlen(trim($__compilerTemp19)) > 0) {
		$__compilerTemp18 .= '
						' . '
						';
		$__vars['uix_alwaysStaffBar'] = '1';
		$__compilerTemp18 .= '
						<div class="p-staffBar-inner hScroller" data-xf-init="h-scroller">
							<div class="hScroller-scroll">
								' . $__compilerTemp19 . '
							</div>
						</div>
					';
	}
	$__compilerTemp18 .= '

					';
	$__compilerTemp22 = '';
	$__compilerTemp22 .= '
								' . $__templater->callMacro(null, 'uix_visitorTabs__component', array(
		'socialMediaContent' => $__vars['uix_socialMediaContent'],
		'whatsNewContent' => $__vars['uix_whatsNew__component'],
		'searchContent' => $__vars['uix_search__component'],
		'loginTabsContent' => $__vars['uix_loginTabs__component'],
		'visitorContent' => $__vars['uix_userTabs__component'],
		'location' => 'staffBar',
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp22)) > 0) {
		$__compilerTemp18 .= '
						<div class="p-nav-opposite">
							';
		if ($__templater->func('property', array('uix_staffBarBreakpoint', ), false) != '100%') {
			$__compilerTemp18 .= '
								';
			$__vars['uix_responsiveStaffBar'] = '1';
			$__compilerTemp18 .= '
								';
		} else {
			$__compilerTemp18 .= '
								';
			$__vars['uix_alwaysStaffBar'] = '1';
			$__compilerTemp18 .= '
							';
		}
		$__compilerTemp18 .= '
							' . $__compilerTemp22 . '
						</div>
					';
	}
	$__compilerTemp18 .= '

				';
	if (strlen(trim($__compilerTemp18)) > 0) {
		$__compilerTemp17 .= '
		<div class="p-staffBar ';
		if ($__templater->func('property', array('uix_stickyStaffBar', ), false)) {
			$__compilerTemp17 .= 'uix_stickyBar';
		}
		$__compilerTemp17 .= '">
			<div class="pageContent">
				' . $__compilerTemp18 . '
			</div>
		</div>
	';
	}
	$__vars['uix_staffbar__component'] = $__templater->preEscaped('
	' . $__compilerTemp17 . '
');
	$__finalCompiled .= '

';
	if ((!$__vars['uix_alwaysStaffBar']) AND $__vars['uix_responsiveStaffBar']) {
		$__finalCompiled .= '
	';
		$__vars['uix_htmlClasses'] = $__templater->preEscaped($__templater->escape($__vars['uix_htmlClasses']) . ' uix_responsiveStaffBar');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . '

' . '

';
	if (!$__vars['uix_hideNavigation']) {
		$__finalCompiled .= '
	';
		$__compilerTemp23 = '';
		$__compilerTemp24 = '';
		$__compilerTemp24 .= '
						';
		if ($__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false) != '100%') {
			$__compilerTemp24 .= '
							';
			if (!$__templater->test($__vars['selectedNavChildren'], 'empty', array())) {
				$__compilerTemp24 .= '
								';
				$__vars['uix_subNavContentStatic'] = '1';
				$__compilerTemp24 .= '
								';
				$__vars['uix_htmlClasses'] = $__templater->preEscaped($__templater->escape($__vars['uix_htmlClasses']) . ' uix_hasSectionLinks');
				$__compilerTemp24 .= '
								<div class="p-sectionLinks-inner hScroller" data-xf-init="h-scroller">
									<div class="hScroller-scroll">
										<ul class="p-sectionLinks-list">
											';
				$__vars['i'] = 0;
				if ($__templater->isTraversable($__vars['selectedNavChildren'])) {
					foreach ($__vars['selectedNavChildren'] AS $__vars['navId'] => $__vars['navEntry']) {
						$__vars['i']++;
						$__compilerTemp24 .= '
												<li>
													' . $__templater->callMacro(null, 'nav_entry', array(
							'navId' => $__vars['navId'],
							'nav' => $__vars['navEntry'],
							'shortcut' => 'alt+' . $__vars['i'],
						), $__vars) . '
												</li>
											';
					}
				}
				$__compilerTemp24 .= '
										</ul>
									</div>
								</div>
							';
			}
			$__compilerTemp24 .= '
							';
			$__compilerTemp25 = '';
			$__compilerTemp25 .= '
										';
			$__compilerTemp26 = '';
			$__compilerTemp26 .= '
												' . $__templater->callMacro(null, 'uix_visitorTabs__component', array(
				'socialMediaContent' => $__vars['uix_socialMediaContent'],
				'whatsNewContent' => $__vars['uix_whatsNew__component'],
				'searchContent' => $__vars['uix_search__component'],
				'loginTabsContent' => $__vars['uix_loginTabs__component'],
				'visitorContent' => $__vars['uix_userTabs__component'],
				'location' => 'tablinks',
			), $__vars) . '
											';
			if (strlen(trim($__compilerTemp26)) > 0) {
				$__compilerTemp25 .= '
											';
				$__vars['uix_subNavContentStatic'] = '1';
				$__compilerTemp25 .= '
											' . $__compilerTemp26 . '
										';
			}
			$__compilerTemp25 .= '
										' . $__templater->callMacro(null, 'uix_sidebarTrigger__component', array(
				'content' => $__vars['uix_sidebarTrigger__content'],
				'location' => 'sectionLinks',
			), $__vars) . '
									';
			if (strlen(trim($__compilerTemp25)) > 0) {
				$__compilerTemp24 .= '
								<div class="p-nav-opposite">
									' . $__compilerTemp25 . '
								</div>
							';
			}
			$__compilerTemp24 .= '
						';
		}
		$__compilerTemp24 .= '
					';
		if (strlen(trim($__compilerTemp24)) > 0) {
			$__compilerTemp23 .= '
			<div class="p-sectionLinks">
				<div class="pageContent">
					' . $__compilerTemp24 . '
				</div>
			</div>
			';
		} else if ($__vars['selectedNavEntry']) {
			$__compilerTemp23 .= '
			<div class="p-sectionLinks p-sectionLinks--empty"></div>
		';
		}
		$__vars['subNavHtml'] = $__templater->preEscaped('
		' . $__compilerTemp23 . '
	');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . '

';
	$__compilerTemp27 = '';
	if ($__templater->func('property', array('uix_stickySidebar', ), false) != 'disabled') {
		$__compilerTemp27 .= ' uix_stickyBodyElement';
	}
	$__compilerTemp28 = '';
	$__compilerTemp29 = '';
	$__compilerTemp29 .= '
							';
	$__vars['i'] = 0;
	if ($__templater->isTraversable($__vars['navTree'])) {
		foreach ($__vars['navTree'] AS $__vars['navSection'] => $__vars['navEntry']) {
			if (($__vars['navSection'] != $__vars['xf']['app']['defaultNavigationId'])) {
				$__vars['i']++;
				$__compilerTemp29 .= '
								<li class="uix_sidebarNavList__listItem">
									' . $__templater->callMacro(null, 'nav_entry', array(
					'sidebarNav' => '1',
					'navId' => $__vars['navSection'],
					'nav' => $__vars['navEntry'],
					'selected' => ($__vars['navSection'] == $__vars['pageSection']),
					'shortcut' => $__vars['i'],
				), $__vars) . '
								</li>
							';
			}
		}
	}
	$__compilerTemp29 .= '
						';
	if (strlen(trim($__compilerTemp29)) > 0) {
		$__compilerTemp28 .= '
					<ul class="uix_sidebarNavList js-offCanvasNavSource">
						' . $__compilerTemp29 . '
					</ul>
				';
	}
	$__compilerTemp30 = '';
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp30 .= '
					<ul class="uix_sidebarNavList">
						<li><div class="p-navEl u-ripple ' . (($__vars['uix_myAccountLinks'] == 'profile') ? 'is-selected' : '') . '"><div class="p-navEl__inner"><a data-nav-id="profile" href="' . $__templater->func('link', array('members', $__vars['xf']['visitor'], ), true) . '" class="p-navEl-link">' . 'Profile' . '</a></div></div></li>
						<li><div class="p-navEl u-ripple ' . (($__vars['uix_myAccountLinks'] == 'alerts') ? 'is-selected' : '') . '"><div class="p-navEl__inner"><a data-nav-id="alerts" href="' . $__templater->func('link', array('account/alerts', ), true) . '" class="p-navEl-link">' . 'Forum Alerts' . '</a></div></div></li>
						<li><div class="p-navEl u-ripple ' . (($__vars['uix_myAccountLinks'] == 'settings') ? 'is-selected' : '') . '"><div class="p-navEl__inner"><a data-nav-id="settings" href="' . $__templater->func('link', array('account/preferences', ), true) . '" class="p-navEl-link">' . 'Settings' . '</a></div></div></li>
					</ul>
				';
	}
	$__compilerTemp31 = '';
	$__compilerTemp32 = '';
	$__compilerTemp32 .= '
							' . $__templater->filter($__vars['uix_sidebarNavWidgets'], array(array('raw', array()),), true) . '
						';
	if (strlen(trim($__compilerTemp32)) > 0) {
		$__compilerTemp31 .= '
					<div class="uix_sidebarNav__inner__widgets">
						' . $__compilerTemp32 . '
					</div>
				';
	}
	$__vars['uix_sidebarNav__component'] = $__templater->preEscaped('
	<div class="uix_sidebarNav">
		<div class="uix_sidebarNav__inner ' . $__compilerTemp27 . '">
			<div class="uix_sidebar--scroller">
				' . $__compilerTemp28 . '
				' . $__compilerTemp30 . '
				' . $__compilerTemp31 . '
			</div>
		</div>
	</div>
');
	$__finalCompiled .= '

' . '

' . '

';
	$__compilerTemp33 = '';
	if ($__vars['uix_responsiveStaffBar'] AND (!$__vars['uix_alwaysStaffBar'])) {
		$__compilerTemp33 .= '
	,{
		"breakpoint": "' . $__templater->filter($__templater->func('property', array('uix_staffBarBreakpoint', ), false), array(array('escape', array('json', )),), true) . '",
		"offset": "' . $__templater->filter($__templater->func('property', array('uix_stickyStaffBarHeight', ), false), array(array('escape', array('json', )),), true) . '"
	}	
	';
	}
	$__compilerTemp34 = '';
	if ($__vars['uix_alwaysStaffBar']) {
		$__compilerTemp34 .= '
	,{
		"breakpoint": "0",
		"offset": "' . $__templater->filter($__templater->func('property', array('uix_stickyStaffBarHeight', ), false), array(array('escape', array('json', )),), true) . '"
	}	
	';
	}
	$__compilerTemp35 = '';
	if ($__templater->func('property', array('publicNavSticky', ), false) != 'none') {
		$__compilerTemp35 .= '
			,{
				"breakpoint": "0",
				"offset": "' . $__templater->filter($__templater->func('property', array('uix_stickyNavHeight', ), false), array(array('escape', array('json', )),), true) . '"
			}		
	';
		if (($__templater->func('property', array('publicNavSticky', ), false) == 'all') AND $__vars['uix_subNavContentStatic']) {
		}
		$__compilerTemp35 .= '		
			,{
				"breakpoint": "' . $__templater->filter($__templater->func('property', array('uix_viewportWidthRemoveSubNav', ), false), array(array('escape', array('json', )),), true) . '",
				"offset": "' . $__templater->filter($__templater->func('property', array('uix_stickySectionLinkHeight', ), false), array(array('escape', array('json', )),), true) . '"
			}
	';
	}
	$__vars['uix_mainTabsOffset'] = $__templater->preEscaped('
	[
		{
			"breakpoint": "0",
			"offset": "0"
		}
	' . $__compilerTemp33 . '
	' . $__compilerTemp34 . '
	' . $__compilerTemp35 . '
	]
');
	$__finalCompiled .= '

';
	$__compilerTemp36 = '';
	$__compilerTemp37 = '';
	$__compilerTemp37 .= '
						';
	if ($__vars['uix_mainTabSets']) {
		$__compilerTemp37 .= '
							';
		$__vars['uix_hasMainTabs'] = '1';
		$__compilerTemp37 .= '
							' . $__templater->callMacro('uix_mainTabSets', $__vars['uix_mainTabSets'], array(
			'activeTab' => $__vars['uix_mainTabActive'],
		), $__vars) . '
						';
	}
	$__compilerTemp37 .= '
					';
	if (strlen(trim($__compilerTemp37)) > 0) {
		$__compilerTemp36 .= '
		<div class="block uix_mainTabBar ';
		if ($__templater->func('property', array('uix_mainTabsSticky', ), false)) {
			$__compilerTemp36 .= 'uix_stickyBar';
		}
		$__compilerTemp36 .= '" data-top-offset-breakpoints="' . $__templater->filter($__vars['uix_mainTabsOffset'], array(array('for_attr', array()),), true) . '">
			<div class="block-tabHeader tabs hScroller" data-xf-init="h-scroller">
				<span class="hScroller-scroll">
					' . $__compilerTemp37 . '
				</span>
			</div>
		</div>
	';
	}
	$__vars['uix_mainTabComponent'] = $__templater->preEscaped('
	' . $__compilerTemp36 . '
');
	$__finalCompiled .= '

' . '

' . '

' . '

' . '

' . '

' . '

';
	if (!$__vars['uix_hideBreadcrumb']) {
		$__finalCompiled .= '
	';
		$__vars['position'] = 0;
		$__vars['rootBreadcrumb'] = $__vars['navTree'][$__vars['xf']['options']['rootBreadcrumb']];
		$__compilerTemp38 = '';
		if ($__vars['rootBreadcrumb'] AND ($__vars['rootBreadcrumb']['href'] != $__vars['xf']['uri'])) {
			$__compilerTemp38 .= '
			';
			$__vars['position'] = ($__vars['position'] + 1);
			$__compilerTemp38 .= '
			' . $__templater->callMacro(null, 'crumb', array(
				'position' => $__vars['position'],
				'href' => $__vars['rootBreadcrumb']['href'],
				'value' => $__vars['rootBreadcrumb']['title'],
			), $__vars) . '
		';
		}
		$__compilerTemp39 = '';
		if ($__vars['selectedNavEntry'] AND ($__vars['selectedNavEntry']['href'] AND (($__vars['selectedNavEntry']['href'] != $__vars['xf']['uri']) AND ($__vars['selectedNavEntry']['href'] != $__vars['rootBreadcrumb']['href'])))) {
			$__compilerTemp39 .= '
			';
			$__vars['position'] = ($__vars['position'] + 1);
			$__compilerTemp39 .= '
			' . $__templater->callMacro(null, 'crumb', array(
				'position' => $__vars['position'],
				'href' => $__vars['selectedNavEntry']['href'],
				'value' => $__vars['selectedNavEntry']['title'],
			), $__vars) . '
		';
		}
		$__compilerTemp40 = '';
		if ($__templater->isTraversable($__vars['breadcrumbs'])) {
			foreach ($__vars['breadcrumbs'] AS $__vars['breadcrumb']) {
				if ($__vars['breadcrumb']['href'] != $__vars['xf']['uri']) {
					$__compilerTemp40 .= '
			';
					$__vars['position'] = ($__vars['position'] + 1);
					$__compilerTemp40 .= '
			' . $__templater->callMacro(null, 'crumb', array(
						'position' => $__vars['position'],
						'href' => $__vars['breadcrumb']['href'],
						'value' => $__vars['breadcrumb']['value'],
					), $__vars) . '
		';
				}
			}
		}
		$__vars['uix_breadcrumbCrumbContent'] = $__templater->preEscaped('
		' . '' . '

		' . '' . '
		' . $__compilerTemp38 . '

		' . $__compilerTemp39 . '
		' . $__compilerTemp40 . '
	');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__vars['breadcrumbCount'] = $__templater->preEscaped($__templater->filter($__templater->func('count', array($__vars['breadcrumbs'], ), false), array(array('default', array(0, )),), true));
	$__finalCompiled .= '
';
	$__vars['navEntryCount'] = $__templater->preEscaped((($__vars['selectedNavEntry'] == null) ? 0 : 1));
	$__finalCompiled .= '

';
	if ($__vars['uix_breadcrumbCrumbContent'] AND (((($__templater->filter($__vars['navEntryCount'], array(array('escape', array()),), false) + $__templater->filter($__vars['breadcrumbCount'], array(array('escape', array()),), false)) != 1)) OR (!$__templater->func('property', array('uix_hideSingleCrumb', ), false)))) {
		$__finalCompiled .= '
	';
		$__vars['uix_breadcrumbHasCrumbs'] = '1';
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . '

' . '


' . '

';
	$__vars['uix_topBreadcrumb__component'] = $__templater->preEscaped('
	' . $__templater->callAdsMacro('container_breadcrumb_top_above', array(), $__vars) . '
	' . $__templater->callMacro(null, 'breadcrumbs', array(
		'crumbContent' => $__vars['uix_breadcrumbCrumbContent'],
		'breadcrumbHasCrumbs' => $__vars['uix_breadcrumbHasCrumbs'],
	), $__vars) . '
	' . $__templater->callAdsMacro('container_breadcrumb_top_below', array(), $__vars) . '
');
	$__finalCompiled .= '

' . '

' . '

';
	$__compilerTemp41 = '';
	if (!$__templater->func('property', array('uix_removeBottomBreadcrumb', ), false)) {
		$__compilerTemp41 .= '
		' . $__templater->callAdsMacro('container_breadcrumb_bottom_above', array(), $__vars) . '
		' . $__templater->callMacro(null, 'breadcrumbs', array(
			'crumbContent' => $__vars['uix_breadcrumbCrumbContent'],
			'breadcrumbHasCrumbs' => $__vars['uix_breadcrumbHasCrumbs'],
			'variant' => 'bottom',
		), $__vars) . '
		' . $__templater->callAdsMacro('container_breadcrumb_bottom_below', array(), $__vars) . '
	';
	}
	$__vars['uix_bottomBreadcrumb__component'] = $__templater->preEscaped('
	' . $__compilerTemp41 . '
');
	$__finalCompiled .= '

' . '


' . '

';
	$__compilerTemp42 = '';
	if (!$__vars['uix_hideNotices']) {
		$__compilerTemp42 .= '
		';
		if ($__vars['notices']['block']) {
			$__compilerTemp42 .= '
			' . $__templater->callMacro('notice_macros', 'notice_list', array(
				'type' => 'block',
				'notices' => $__vars['notices']['block'],
			), $__vars) . '
		';
		}
		$__compilerTemp42 .= '

		';
		if ($__vars['notices']['scrolling']) {
			$__compilerTemp42 .= '
			' . $__templater->callMacro('notice_macros', 'notice_list', array(
				'type' => 'scrolling',
				'notices' => $__vars['notices']['scrolling'],
			), $__vars) . '
		';
		}
		$__compilerTemp42 .= '
	';
	}
	$__vars['uix_notices'] = $__templater->preEscaped('
	' . $__compilerTemp42 . '
');
	$__finalCompiled .= '

' . '

' . '

';
	$__compilerTemp43 = '';
	if ((!$__vars['uix_hideExtendedFooter']) AND $__templater->func('property', array('uix_enableExtendedFooter', ), false)) {
		$__compilerTemp43 .= '
			' . $__templater->includeTemplate('uix_extendedFooter', $__vars) . '
		';
	}
	$__compilerTemp44 = '';
	$__compilerTemp45 = '';
	$__compilerTemp45 .= '
									';
	if (($__templater->func('property', array('uix_pageWidthToggle', ), false) != 'disabled') AND $__vars['uix_canTogglePageWidth']) {
		$__compilerTemp45 .= '
										<li><a id="uix_widthToggle--trigger" data-xf-init="tooltip" title="' . 'Toggle width' . '" rel="nofollow">' . $__templater->fontAwesome('fa-compress-alt', array(
			'class' => 'uix_icon uix_icon--collapse',
		)) . '</a></li>
									';
	}
	$__compilerTemp45 .= '
									';
	if ($__templater->method($__vars['xf']['visitor'], 'canChangeStyle', array()) AND (!$__templater->func('property', array('uix_hideStyleChoose', ), false))) {
		$__compilerTemp45 .= '
										<li><a href="' . $__templater->func('link', array('misc/style', ), true) . '" data-xf-click="overlay" data-xf-init="tooltip" title="' . 'Style chooser' . '" rel="nofollow">' . $__templater->escape($__vars['xf']['style']['title']) . '</a></li>
									';
	}
	$__compilerTemp45 .= '
									';
	if ($__templater->method($__vars['xf']['visitor'], 'canChangeLanguage', array())) {
		$__compilerTemp45 .= '
										<li><a href="' . $__templater->func('link', array('misc/language', ), true) . '" data-xf-click="overlay" data-xf-init="tooltip" title="' . 'Language chooser' . '" rel="nofollow"><i class="fa fa-globe" aria-hidden="true"></i> ' . $__templater->escape($__vars['xf']['language']['title']) . '</a></li>
									';
	}
	$__compilerTemp45 .= '
								';
	if (strlen(trim($__compilerTemp45)) > 0) {
		$__compilerTemp44 .= '
						<div class="p-footer-row-main">
							<ul class="p-footer-linkList p-footer-choosers">
								' . $__compilerTemp45 . '
							</ul>
						</div>
					';
	}
	$__compilerTemp46 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canUseContactForm', array())) {
		$__compilerTemp46 .= '
							';
		if ($__vars['xf']['contactUrl']) {
			$__compilerTemp46 .= '
								<li><a href="' . $__templater->escape($__vars['xf']['contactUrl']) . '" data-xf-click="' . (($__vars['xf']['options']['contactUrl']['overlay'] OR ($__vars['xf']['options']['contactUrl']['type'] == 'default')) ? 'overlay' : '') . '">' . 'Contact us' . '</a></li>
							';
		}
		$__compilerTemp46 .= '
						';
	}
	$__compilerTemp47 = '';
	if ($__vars['xf']['tosUrl']) {
		$__compilerTemp47 .= '
							<li><a href="' . $__templater->escape($__vars['xf']['tosUrl']) . '">' . 'Terms and rules' . '</a></li>
						';
	}
	$__compilerTemp48 = '';
	if ($__vars['xf']['privacyPolicyUrl']) {
		$__compilerTemp48 .= '
							<li><a href="' . $__templater->escape($__vars['xf']['privacyPolicyUrl']) . '">' . 'Privacy policy' . '</a></li>
						';
	}
	$__compilerTemp49 = '';
	if ($__vars['xf']['helpPageCount']) {
		$__compilerTemp49 .= '
							<li><a href="' . $__templater->func('link', array('help', ), true) . '">' . 'Help' . '</a></li>
						';
	}
	$__compilerTemp50 = '';
	if ($__vars['xf']['homePageUrl']) {
		$__compilerTemp50 .= '
							<li><a href="' . $__templater->escape($__vars['xf']['homePageUrl']) . '">' . 'Home' . '</a></li>
						';
	}
	$__compilerTemp51 = '';
	$__compilerTemp52 = '';
	$__compilerTemp52 .= '
								' . $__templater->func('copyright') . '
								' . $__templater->callback('ThemeHouse\\Core\\Branding', 'renderStyleBranding', '', array());
	$__vars['thBrandingDisplayed'] = '1';
	$__compilerTemp52 .= '
								' . '' . '
							';
	if (strlen(trim($__compilerTemp52)) > 0) {
		$__compilerTemp51 .= '
						<div class="p-footer-copyright">
							' . $__compilerTemp52 . '
						</div>
					';
	}
	$__compilerTemp53 = '';
	$__compilerTemp54 = '';
	$__compilerTemp54 .= '
								' . $__templater->callMacro('debug_macros', 'debug', array(
		'controller' => $__vars['controller'],
		'action' => $__vars['actionMethod'],
		'template' => $__vars['template'],
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp54)) > 0) {
		$__compilerTemp53 .= '
						<div class="p-footer-debug">
							' . $__compilerTemp54 . '
						</div>
					';
	}
	$__vars['uix_footer'] = $__templater->preEscaped('
	<footer class="p-footer" id="footer">

		' . $__compilerTemp43 . '

		<div class="p-footer-inner">
			<div class="pageContent">
				<div class="p-footer-row">
					' . $__compilerTemp44 . '
				</div>
				<div class="p-footer-row-opposite">
					<ul class="p-footer-linkList">
						' . $__compilerTemp46 . '

						' . $__compilerTemp47 . '

						' . $__compilerTemp48 . '

						' . $__compilerTemp49 . '

						' . $__compilerTemp50 . '

						<li><a href="#top" title="' . 'Top' . '" data-xf-click="scroll-to"><i class="fa fa-arrow-up" aria-hidden="true"></i></a></li>

						<li><a href="' . $__templater->func('link', array('forums/index.rss', '-', ), true) . '" target="_blank" class="p-footer-rssLink" title="' . $__templater->filter('RSS', array(array('for_attr', array()),), true) . '"><span aria-hidden="true"><i class="fa fa-rss"></i><span class="u-srOnly">' . 'RSS' . '</span></span></a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="p-footer-copyrightRow">
			<div class="pageContent">
				<div class="uix_copyrightBlock">
					' . $__compilerTemp51 . '

					' . $__compilerTemp53 . '
				</div>
				' . '
				' . $__templater->callMacro(null, 'uix_socialMedia__component', array(
		'content' => $__vars['uix_socialMediaContent'],
		'location' => 'copyright',
	), $__vars) . '
			</div>
		</div>
	</footer>
');
	$__finalCompiled .= '

' . '

' . '

';
	$__vars['thUixFontSize'] = (62.5 * ((14 + ($__vars['xf']['visitor']['Option']['thuix_font_size'] * 2)) / 14));
	$__finalCompiled .= '

<html id="XF" lang="' . $__templater->escape($__vars['xf']['language']['language_code']) . '" dir="' . $__templater->escape($__vars['xf']['language']['text_direction']) . '"
	  style="font-size: ' . $__templater->escape($__vars['thUixFontSize']) . '%;"
	  data-app="public"
	  data-template="' . $__templater->escape($__vars['template']) . '"
	  data-container-key="' . $__templater->escape($__vars['containerKey']) . '"
	  data-content-key="' . $__templater->escape($__vars['contentKey']) . '"
	  data-logged-in="' . ($__vars['xf']['visitor']['user_id'] ? 'true' : 'false') . '"
	  data-cookie-prefix="' . $__templater->escape($__vars['xf']['cookie']['prefix']) . '"
	  data-csrf="' . $__templater->filter($__templater->func('csrf_token', array(), false), array(array('escape', array('js', )),), true) . '"
	  class="has-no-js ' . ($__vars['template'] ? ('template-' . $__templater->escape($__vars['template'])) : '') . ' ' . $__templater->escape($__vars['uix_htmlClasses']) . ' ' . $__templater->escape($__vars['uix_additionalHtmlClasses']) . '"
	  ' . ($__vars['xf']['runJobs'] ? ' data-run-jobs=""' : '') . '>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

		' . '

		<title>' . $__templater->escape($__vars['title']) . '</title>

		';
	if ($__templater->isTraversable($__vars['head'])) {
		foreach ($__vars['head'] AS $__vars['headTag']) {
			$__finalCompiled .= '
			' . $__templater->escape($__vars['headTag']) . '
		';
		}
	}
	$__finalCompiled .= '

		';
	if ((!$__vars['head']['meta_site_name']) AND !$__templater->test($__vars['siteName'], 'empty', array())) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'site_name', array(
			'siteName' => $__vars['siteName'],
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		';
	if (!$__vars['head']['meta_type']) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'type', array(
			'type' => 'website',
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		';
	if (!$__vars['head']['meta_title']) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'title', array(
			'title' => ($__templater->func('page_title', array(), false) ?: $__vars['siteName']),
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		';
	if ((!$__vars['head']['meta_description']) AND (!$__templater->test($__vars['description'], 'empty', array()) AND $__vars['pageDescriptionMeta'])) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'description', array(
			'description' => $__vars['description'],
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		';
	if (!$__vars['head']['meta_share_url']) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'share_url', array(
			'shareUrl' => $__vars['xf']['fullUri'],
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		';
	if ((!$__vars['head']['meta_image_url']) AND $__templater->func('property', array('publicMetadataLogoUrl', ), false)) {
		$__finalCompiled .= '
			' . $__templater->callMacro('metadata_macros', 'image_url', array(
			'imageUrl' => $__templater->func('base_url', array($__templater->func('property', array('publicMetadataLogoUrl', ), false), true, ), false),
			'output' => true,
		), $__vars) . '
		';
	}
	$__finalCompiled .= '

		';
	if ($__templater->func('property', array('metaThemeColor', ), false)) {
		$__finalCompiled .= '
			<meta name="theme-color" content="' . $__templater->func('parse_less_color', array($__templater->func('property', array('metaThemeColor', ), false), ), true) . '" />
			<meta name="msapplication-TileColor" content="' . $__templater->func('property', array('metaThemeColor', ), true) . '">
		';
	}
	$__finalCompiled .= '

		' . $__templater->callMacro('helper_js_global', 'head', array(
		'app' => 'public',
	), $__vars) . '

		';
	if ($__templater->func('property', array('publicFaviconUrl', ), false)) {
		$__finalCompiled .= '
			<link rel="icon" type="image/png" href="' . $__templater->func('base_url', array($__templater->func('property', array('publicFaviconUrl', ), false), true, ), true) . '" sizes="32x32" />
		';
	}
	$__finalCompiled .= '
		';
	if ($__templater->func('property', array('publicMetadataLogoUrl', ), false)) {
		$__finalCompiled .= '
			<link rel="apple-touch-icon" href="' . $__templater->func('base_url', array($__templater->func('property', array('publicMetadataLogoUrl', ), false), true, ), true) . '" />
		';
	}
	$__finalCompiled .= '
		' . $__templater->includeTemplate('google_analytics', $__vars) . '

		';
	$__vars['uix_stickyStaffBarHeight'] = '0';
	$__finalCompiled .= '

		';
	if ($__templater->func('property', array('uix_stickyStaffBar', ), false) AND ($__vars['uix_responsiveStaffBar'] OR $__vars['uix_alwaysStaffBar'])) {
		$__finalCompiled .= '
			';
		$__vars['uix_stickyStaffBarHeight'] = $__templater->func('property', array('uix_stickyStaffBarHeight', ), false);
		$__finalCompiled .= '
		';
	}
	$__finalCompiled .= '

	</head>

	<body data-template="' . $__templater->escape($__vars['template']) . '">
		' . $__templater->includeTemplate('page_style', $__vars) . '
		<div id="jumpToTop"></div>

		' . '

		<div class="uix_pageWrapper--fixed">
			<div class="p-pageWrapper" id="top">

				<div class="uix_headerContainer">
					<div class="uix_headerContainer--stickyFix"></div>
					' . $__templater->callMacro(null, 'uix_staffbar__component', array(
		'content' => $__vars['uix_staffbar__component'],
	), $__vars) . '

					';
	$__compilerTemp55 = '';
	$__compilerTemp56 = '';
	$__compilerTemp56 .= '
									';
	if ($__templater->func('property', array('uix_viewportShowLogoBlock', ), false) != '0') {
		$__compilerTemp56 .= '
										' . $__templater->callMacro(null, 'uix_logo__component', array(
			'content' => $__vars['uix_logo__component'],
		), $__vars) . '
									';
	}
	$__compilerTemp56 .= '

									';
	$__compilerTemp57 = '';
	$__compilerTemp57 .= '
												' . $__templater->callMacro(null, 'uix_visitorTabs__component', array(
		'socialMediaContent' => $__vars['uix_socialMediaContent'],
		'whatsNewContent' => $__vars['uix_whatsNew__component'],
		'searchContent' => $__vars['uix_search__component'],
		'loginTabsContent' => $__vars['uix_loginTabs__component'],
		'visitorContent' => $__vars['uix_userTabs__component'],
		'location' => 'header',
	), $__vars) . '
											';
	if (strlen(trim($__compilerTemp57)) > 0) {
		$__compilerTemp56 .= '
										<div class="p-nav-opposite">
											' . $__compilerTemp57 . '
										</div>
									';
	}
	$__compilerTemp56 .= '
								';
	if (strlen(trim($__compilerTemp56)) > 0) {
		$__compilerTemp55 .= '
							<div class="p-header-content">
								' . $__compilerTemp56 . '
							</div>
						';
	}
	$__vars['uix_headerContent'] = $__templater->preEscaped('
						' . $__compilerTemp55 . '
					');
	$__finalCompiled .= '

					' . '

					';
	if ((!$__templater->func('property', array('uix_navAboveHeader', ), false))) {
		$__finalCompiled .= '
						' . $__templater->callMacro(null, 'uix_logoBlock', array(
			'content' => $__vars['uix_headerContent'],
		), $__vars) . '
					';
	}
	$__finalCompiled .= '

					';
	$__compilerTemp58 = '';
	if (!$__vars['uix_hideNavigation']) {
		$__compilerTemp58 .= '
									';
		$__vars['uix_badgeCounter'] = ($__vars['xf']['visitor']['conversations_unread'] + $__vars['xf']['visitor']['alerts_unread']);
		$__compilerTemp58 .= '
									';
		if (($__templater->func('property', array('uix_visitorTabsMobile', ), false) == 'canvas')) {
			$__compilerTemp58 .= '
										';
			$__vars['uix_visitorTabCanvas'] = '1';
			$__compilerTemp58 .= '
										';
		} else {
			$__compilerTemp58 .= '
										';
			$__vars['uix_visitorTabCanvas'] = '0';
			$__compilerTemp58 .= '
									';
		}
		$__compilerTemp58 .= '
									<a class="p-nav-menuTrigger  badgeContainer' . (($__vars['uix_visitorTabCanvas'] AND ($__vars['xf']['visitor']['alerts_unread'] OR $__vars['xf']['visitor']['conversations_unread'])) ? ' badgeContainer--highlighted' : '') . '" data-badge="' . $__templater->filter($__vars['uix_badgeCounter'], array(array('number', array()),), true) . '" data-xf-click="off-canvas" data-menu=".js-headerOffCanvasMenu" role="button" tabindex="0">
										<i aria-hidden="true"></i>
										<span class="p-nav-menuText">' . 'Menu' . '</span>
									</a>
									';
		if (($__templater->func('property', array('uix_navigationType', ), false) == 'sidebarNav') AND ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered')) {
			$__compilerTemp58 .= '
										<a class="p-nav-menuTrigger uix_sidebarNav--trigger" id="uix_sidebarNav--trigger" rel="nofollow">
											<i aria-hidden="true"></i>
											<span class="p-nav-menuText">' . 'Menu' . '</span>
										</a>
									';
		}
		$__compilerTemp58 .= '
								';
	}
	$__compilerTemp59 = '';
	if (!$__vars['uix_hideNavigation']) {
		$__compilerTemp59 .= '
									';
		if (($__templater->func('property', array('uix_navigationType', ), false) != 'sidebarNav') OR ($__templater->func('property', array('uix_pageStyle', ), false) != 'covered')) {
			$__compilerTemp59 .= '
										<div class="p-nav-scroller hScroller" data-xf-init="h-scroller" data-auto-scroll=".p-navEl.is-selected">
											<div class="hScroller-scroll">
												<ul class="p-nav-list js-offCanvasNavSource">
													';
			$__vars['i'] = 0;
			if ($__templater->isTraversable($__vars['navTree'])) {
				foreach ($__vars['navTree'] AS $__vars['navSection'] => $__vars['navEntry']) {
					if (($__vars['navSection'] != $__vars['xf']['app']['defaultNavigationId'])) {
						$__vars['i']++;
						$__compilerTemp59 .= '
														<li>
															' . $__templater->callMacro(null, 'nav_entry', array(
							'navId' => $__vars['navSection'],
							'nav' => $__vars['navEntry'],
							'selected' => ($__vars['navSection'] == $__vars['pageSection']),
							'shortcut' => $__vars['i'],
						), $__vars) . '
														</li>
													';
					}
				}
			}
			$__compilerTemp59 .= '
												</ul>
											</div>
										</div>
									';
		}
		$__compilerTemp59 .= '

									';
		if ($__templater->func('property', array('uix_activeNavTitle', ), false)) {
			$__compilerTemp59 .= '
										<div class="uix_activeNavTitle">
											<span>
												';
			if ($__vars['uix_mobileActiveNav']) {
				$__compilerTemp59 .= '
													' . $__templater->escape($__vars['uix_mobileActiveNav']) . '
													';
			} else if ($__vars['selectedNavEntry']['href']) {
				$__compilerTemp59 .= '
													' . $__templater->escape($__vars['selectedNavEntry']['title']) . '
													';
			} else {
				$__compilerTemp59 .= '
												';
			}
			$__compilerTemp59 .= '
											</span>
										</div>
									';
		}
		$__compilerTemp59 .= '
								';
	}
	$__compilerTemp60 = '';
	$__compilerTemp61 = '';
	$__compilerTemp61 .= '
											' . $__templater->callMacro(null, 'uix_visitorTabs__component', array(
		'socialMediaContent' => $__vars['uix_socialMediaContent'],
		'whatsNewContent' => $__vars['uix_whatsNew__component'],
		'searchContent' => $__vars['uix_search__component'],
		'loginTabsContent' => $__vars['uix_loginTabs__component'],
		'visitorContent' => $__vars['uix_userTabs__component'],
		'location' => 'navigation',
	), $__vars) . '
											' . $__templater->callMacro(null, 'uix_sidebarTrigger__component', array(
		'content' => $__vars['uix_sidebarTrigger__content'],
		'location' => 'navigation',
	), $__vars) . '
										';
	if (strlen(trim($__compilerTemp61)) > 0) {
		$__compilerTemp60 .= '
									<div class="p-nav-opposite">
										' . $__compilerTemp61 . '
									</div>
								';
	}
	$__vars['navHtml'] = $__templater->preEscaped('
						<nav class="p-nav">
							<div class="p-nav-inner">
								' . $__compilerTemp58 . '

								' . $__templater->callMacro(null, 'uix_logo__component', array(
		'content' => $__vars['uix_logo__component'],
	), $__vars) . '

								' . $__compilerTemp59 . '

								' . $__templater->callMacro(null, 'uix_search__component', array(
		'location' => 'navigationLeft',
		'content' => $__vars['uix_search__component'],
	), $__vars) . '

								' . $__compilerTemp60 . '
							</div>
							' . '
						</nav>
					');
	$__finalCompiled .= '
					
					';
	$__compilerTemp62 = '';
	if ($__vars['uix_responsiveStaffBar'] AND ((!$__vars['uix_alwaysStaffBar']) AND $__templater->func('property', array('uix_stickyStaffBar', ), false))) {
		$__compilerTemp62 .= '
							,{
								"breakpoint": "' . $__templater->filter($__templater->func('property', array('uix_staffBarBreakpoint', ), false), array(array('escape', array('json', )),), true) . '",
								"offset": "' . $__templater->filter($__templater->func('property', array('uix_stickyStaffBarHeight', ), false), array(array('escape', array('json', )),), true) . '"
							}	
							';
	}
	$__compilerTemp63 = '';
	if ($__vars['uix_alwaysStaffBar'] AND $__templater->func('property', array('uix_stickyStaffBar', ), false)) {
		$__compilerTemp63 .= '
							,{
								"breakpoint": "0",
								"offset": "' . $__templater->filter($__templater->func('property', array('uix_stickyStaffBarHeight', ), false), array(array('escape', array('json', )),), true) . '"
							}	
							';
	}
	$__vars['uix_navOffset'] = $__templater->preEscaped('
						[
							{
								"breakpoint": "0",
								"offset": "0"
							}
							' . $__compilerTemp62 . '
							' . $__compilerTemp63 . '
						]
					');
	$__finalCompiled .= '

					';
	if ($__templater->func('property', array('publicNavSticky', ), false) == 'primary') {
		$__finalCompiled .= '
						<div class="p-navSticky p-navSticky--primary ';
		if ($__templater->func('property', array('publicNavSticky', ), false) !== 'none') {
			$__finalCompiled .= 'uix_stickyBar';
		}
		$__finalCompiled .= '" data-top-offset-breakpoints="' . $__templater->filter($__vars['uix_navOffset'], array(array('for_attr', array()),), true) . '">
							' . $__templater->filter($__vars['navHtml'], array(array('raw', array()),), true) . '
						</div>
						' . $__templater->filter($__vars['subNavHtml'], array(array('raw', array()),), true) . '
						';
	} else if ($__templater->func('property', array('publicNavSticky', ), false) == 'all') {
		$__finalCompiled .= '
						<div class="p-navSticky p-navSticky--all ';
		if ($__templater->func('property', array('publicNavSticky', ), false) !== 'none') {
			$__finalCompiled .= 'uix_stickyBar';
		}
		$__finalCompiled .= '" data-top-offset-breakpoints="' . $__templater->filter($__vars['uix_navOffset'], array(array('for_attr', array()),), true) . '">
							' . $__templater->filter($__vars['navHtml'], array(array('raw', array()),), true) . '
							' . $__templater->filter($__vars['subNavHtml'], array(array('raw', array()),), true) . '
						</div>
						';
	} else {
		$__finalCompiled .= '
						' . $__templater->filter($__vars['navHtml'], array(array('raw', array()),), true) . '
						' . $__templater->filter($__vars['subNavHtml'], array(array('raw', array()),), true) . '
					';
	}
	$__finalCompiled .= '

					';
	if ($__templater->func('property', array('uix_navAboveHeader', ), false)) {
		$__finalCompiled .= '
						' . $__templater->callMacro(null, 'uix_logoBlock', array(
			'content' => $__vars['uix_headerContent'],
		), $__vars) . '
					';
	}
	$__finalCompiled .= '

					' . $__templater->callMacro('uix_welcomeSection', 'welcomeSection', array(
		'location' => 'header',
		'showWelcomeSection' => $__vars['uix_showWelcomeSection'],
	), $__vars) . '
					' . $__templater->callMacro(null, 'uix_topBreadcrumb__component', array(
		'location' => 'header',
		'content' => $__vars['uix_topBreadcrumb__component'],
	), $__vars) . '
					' . $__templater->callMacro(null, 'uix_mainTabComponent', array(
		'location' => 'header',
		'content' => $__vars['uix_mainTabComponent'],
	), $__vars) . '
					' . $__templater->callMacro(null, 'uix_titlebar__component', array(
		'location' => 'header',
		'content' => $__vars['uix_titlebar__component'],
	), $__vars) . '
					' . $__templater->filter($__vars['uix_headerExtra'], array(array('raw', array()),), true) . '
				</div>

				' . '
				<div class="offCanvasMenu offCanvasMenu--nav js-headerOffCanvasMenu" data-menu="menu" aria-hidden="true" data-ocm-builder="navigation">
					<div class="offCanvasMenu-backdrop" data-menu-close="true"></div>
					<div class="offCanvasMenu-content">
						' . $__templater->includeTemplate('uix_canvasTabs', $__vars) . '
						' . $__templater->includeTemplate('uix_canvasPanels', $__vars) . '

						' . '
					</div>
				</div>

				' . '

				' . $__templater->callMacro(null, 'uix_titlebar__component', array(
		'location' => 'belowHeader',
		'content' => $__vars['uix_titlebar__component'],
	), $__vars) . '

				<div class="p-body">

					';
	if (($__templater->func('property', array('uix_navigationType', ), false) == 'sidebarNav') AND ($__templater->func('property', array('uix_pageStyle', ), false) == 'covered')) {
		$__finalCompiled .= '
						' . $__templater->callMacro(null, 'uix_sidebarNav__component', array(
			'content' => $__vars['uix_sidebarNav__component'],
		), $__vars) . '
					';
	}
	$__finalCompiled .= '

					<div class="p-body-inner ' . ($__vars['removePageWrapper'] ? 'p-body-inner-none' : '') . '">
						';
	if ((!$__templater->func('is_addon_active', array('ThemeHouse/UIX', ), false)) AND $__vars['xf']['visitor']['is_admin']) {
		$__finalCompiled .= '
							<div class="blockMessage blockMessage--error blockMessage--errorUixAddon">
								<h2 style="margin: 0 0 .5em 0">UI.X Error</h2>
								<p>
									It appears that you do not have the UI.X Add-on installed. Please install this add-on to ensure your style works as expected. You can download the UI.X add-on <a href="https://www.themehouse.com/xenforo/2/addons/uix-addon">here</a>.
								</p>
								' . $__templater->button('
									' . $__templater->fontAwesome('fa-exclamation-triangle', array(
			'class' => 'uix_icon uix_icon--warning',
		)) . ' View Documentation
								', array(
			'href' => 'https://www.themehouse.com/help/documentation/uix2',
			'class' => 'button--primary',
		), '', array(
		)) . '
							</div>
						';
	}
	$__finalCompiled .= '
						<!--XF:EXTRA_OUTPUT-->

						' . $__templater->callMacro(null, 'uix_notices', array(
		'location' => 'aboveMainContainer',
		'content' => $__vars['uix_notices'],
	), $__vars) . '

						' . $__templater->callMacro(null, 'uix_titlebar__component', array(
		'location' => 'aboveMainContainer',
		'content' => $__vars['uix_titlebar__component'],
	), $__vars) . '

						' . $__templater->callMacro('uix_welcomeSection', 'welcomeSection', array(
		'location' => 'aboveMainContainer',
		'showWelcomeSection' => $__vars['uix_showWelcomeSection'],
	), $__vars) . '

						' . $__templater->callMacro(null, 'uix_topBreadcrumb__component', array(
		'location' => 'aboveMainContainer',
		'content' => $__vars['uix_topBreadcrumb__component'],
	), $__vars) . '

						' . $__templater->callMacro(null, 'uix_mainTabComponent', array(
		'location' => 'aboveMainContainer',
		'content' => $__vars['uix_mainTabComponent'],
	), $__vars) . '

						' . $__templater->callMacro('browser_warning_macros', 'javascript', array(), $__vars) . '
						' . $__templater->callMacro('browser_warning_macros', 'browser', array(), $__vars) . '

						<div uix_component="MainContainer" class="uix_contentWrapper">

							' . $__templater->callMacro(null, 'uix_notices', array(
		'location' => 'mainContainerTop',
		'content' => $__vars['uix_notices'],
	), $__vars) . '

							' . $__templater->callAdsMacro('container_content_above', array(), $__vars) . '
							' . $__templater->callMacro('uix_welcomeSection', 'welcomeSection', array(
		'location' => 'mainContainerTop',
		'showWelcomeSection' => $__vars['uix_showWelcomeSection'],
	), $__vars) . '
							' . $__templater->callMacro(null, 'uix_topBreadcrumb__component', array(
		'location' => 'mainContainerTop',
		'content' => $__vars['uix_topBreadcrumb__component'],
	), $__vars) . '
							' . $__templater->callMacro(null, 'uix_titlebar__component', array(
		'location' => 'mainContainerTop',
		'content' => $__vars['uix_titlebar__component'],
	), $__vars) . '
							' . $__templater->callMacro(null, 'uix_mainTabComponent', array(
		'location' => 'mainContainerTop',
		'content' => $__vars['uix_mainTabComponent'],
	), $__vars) . '

							<div class="p-body-main ' . ($__vars['sidebar'] ? 'p-body-main--withSidebar' : '') . ' ' . ($__vars['sideNav'] ? 'p-body-main--withSideNav' : '') . '">

								';
	if ($__vars['sideNav']) {
		$__finalCompiled .= '
									<div class="p-body-sideNav">
										<div class="p-body-sideNavTrigger">
											' . $__templater->button('
												' . ($__templater->escape($__vars['sideNavTitle']) ?: 'Navigation') . '
											', array(
			'class' => 'button--link',
			'data-xf-click' => 'off-canvas',
			'data-menu' => '#js-SideNavOcm',
		), '', array(
		)) . '
										</div>
										<div class="p-body-sideNavInner ' . (($__templater->func('property', array('uix_stickySidebar', ), false) == 'top') ? ' uix_stickyBodyElement' : '') . '" data-ocm-class="offCanvasMenu offCanvasMenu--blocks" id="js-SideNavOcm" data-ocm-builder="sideNav">
											<div class="uix_sidebar--scroller">
												<div data-ocm-class="offCanvasMenu-backdrop" data-menu-close="true"></div>
												<div data-ocm-class="offCanvasMenu-content">
													<div class="p-body-sideNavContent">
														' . $__templater->callAdsMacro('container_sidenav_above', array(), $__vars) . '
														';
		if ($__templater->isTraversable($__vars['sideNav'])) {
			foreach ($__vars['sideNav'] AS $__vars['sideNavHtml']) {
				$__finalCompiled .= '
															' . $__templater->escape($__vars['sideNavHtml']) . '
														';
			}
		}
		$__finalCompiled .= '
														' . $__templater->callAdsMacro('container_sidenav_below', array(), $__vars) . '
													</div>
												</div>
											</div>
										</div>
									</div>
								';
	}
	$__finalCompiled .= '

								' . $__templater->callMacro(null, 'uix_sidebar__component', array(
		'content' => $__vars['uix_sidebar__component'],
		'location' => 'left',
	), $__vars) . '
								<div uix_component="MainContent" class="p-body-content">
									<!-- ABOVE MAIN CONTENT -->
									' . $__templater->callMacro(null, 'uix_notices', array(
		'location' => 'aboveMainContent',
		'content' => $__vars['uix_notices'],
	), $__vars) . '
									' . $__templater->callMacro(null, 'uix_topBreadcrumb__component', array(
		'location' => 'aboveMainContent',
		'content' => $__vars['uix_topBreadcrumb__component'],
	), $__vars) . '
									' . $__templater->callMacro('uix_welcomeSection', 'welcomeSection', array(
		'location' => 'aboveMainContent',
		'showWelcomeSection' => $__vars['uix_showWelcomeSection'],
	), $__vars) . '
									' . $__templater->callMacro(null, 'uix_titlebar__component', array(
		'location' => 'aboveMainContent',
		'content' => $__vars['uix_titlebar__component'],
	), $__vars) . '
									' . $__templater->callMacro(null, 'uix_mainTabComponent', array(
		'location' => 'aboveMainContent',
		'content' => $__vars['uix_mainTabComponent'],
	), $__vars) . '
									<div class="p-body-pageContent">
										' . $__templater->callMacro(null, 'uix_notices', array(
		'location' => 'mainContentTop',
		'content' => $__vars['uix_notices'],
	), $__vars) . '
										' . $__templater->callMacro('uix_welcomeSection', 'welcomeSection', array(
		'location' => 'mainContentTop',
		'showWelcomeSection' => $__vars['uix_showWelcomeSection'],
	), $__vars) . '
										' . $__templater->callMacro(null, 'uix_topBreadcrumb__component', array(
		'location' => 'mainContentTop',
		'content' => $__vars['uix_topBreadcrumb__component'],
	), $__vars) . '
										' . $__templater->callMacro(null, 'uix_titlebar__component', array(
		'location' => 'mainContentTop',
		'content' => $__vars['uix_titlebar__component'],
	), $__vars) . '
										' . $__templater->callMacro(null, 'uix_mainTabComponent', array(
		'location' => 'mainContentTop',
		'content' => $__vars['uix_mainTabComponent'],
	), $__vars) . '
										' . $__templater->filter($__vars['content'], array(array('raw', array()),), true) . '
										' . $__templater->callMacro(null, 'uix_bottomBreadcrumb__component', array(
		'content' => $__vars['uix_bottomBreadcrumb__component'],
		'location' => 'mainContentBottom',
	), $__vars) . '
									</div>
									<!-- BELOW MAIN CONTENT -->
									' . $__templater->callAdsMacro('container_content_below', array(), $__vars) . '
									' . $__templater->callMacro(null, 'uix_bottomBreadcrumb__component', array(
		'content' => $__vars['uix_bottomBreadcrumb__component'],
		'location' => 'belowMainContent',
	), $__vars) . '
								</div>

								' . $__templater->callMacro(null, 'uix_sidebar__component', array(
		'content' => $__vars['uix_sidebar__component'],
		'location' => 'right',
	), $__vars) . '
							</div>
							' . $__templater->callMacro(null, 'uix_bottomBreadcrumb__component', array(
		'content' => $__vars['uix_bottomBreadcrumb__component'],
		'location' => 'mainContainerBottom',
	), $__vars) . '
						</div>
						' . $__templater->callMacro(null, 'uix_bottomBreadcrumb__component', array(
		'content' => $__vars['uix_bottomBreadcrumb__component'],
		'location' => 'belowMainContainer',
	), $__vars) . '
						' . $__templater->callMacro(null, 'uix_footer', array(
		'location' => 'insideContent',
		'content' => $__vars['uix_footer'],
		'insideContent' => $__templater->func('property', array('uix_footerInsideContent', ), false),
	), $__vars) . '
					</div>
				</div>

				' . $__templater->callMacro(null, 'uix_footer', array(
		'location' => 'bottom',
		'content' => $__vars['uix_footer'],
		'insideContent' => $__templater->func('property', array('uix_footerInsideContent', ), false),
	), $__vars) . '

				';
	$__compilerTemp64 = '';
	$__compilerTemp64 .= '
							';
	if ($__templater->func('property', array('scrollJumpButtons', ), false)) {
		$__compilerTemp64 .= '
								<div class="u-scrollButtons js-scrollButtons" data-trigger-type="' . $__templater->func('property', array('scrollJumpButtons', ), true) . '">
									' . $__templater->button($__templater->fontAwesome('fa-arrow-up', array(
		)) . '<span class="u-srOnly">' . 'Top' . '</span>', array(
			'href' => '#top',
			'class' => 'button--scroll ripple-JsOnly',
			'data-xf-click' => 'scroll-to',
		), '', array(
		)) . '
									';
		if ($__templater->func('property', array('scrollJumpButtons', ), false) != 'up') {
			$__compilerTemp64 .= '
										' . $__templater->button($__templater->fontAwesome('fa-arrow-down', array(
			)) . '<span class="u-srOnly">' . 'Bottom' . '</span>', array(
				'href' => '#footer',
				'class' => 'button--scroll ripple-JsOnly',
				'data-xf-click' => 'scroll-to',
			), '', array(
			)) . '
									';
		}
		$__compilerTemp64 .= '
								</div>
							';
	}
	$__compilerTemp64 .= '
							';
	if ($__templater->func('property', array('uix_fab', ), false) != 'never') {
		$__compilerTemp64 .= '
								';
		$__compilerTemp65 = '';
		$__compilerTemp65 .= (isset($__templater->pageParams['pageAction']) ? $__templater->pageParams['pageAction'] : '');
		if (strlen(trim($__compilerTemp65)) > 0) {
			$__compilerTemp64 .= '
									<div class="p-title-pageAction">' . $__compilerTemp65 . '</div>
								';
		}
		$__compilerTemp64 .= '
							';
	}
	$__compilerTemp64 .= '
						';
	if (strlen(trim($__compilerTemp64)) > 0) {
		$__finalCompiled .= '
					<div class="uix_fabBar uix_fabBar--active">
						' . $__compilerTemp64 . '
					</div>
				';
	}
	$__finalCompiled .= '
				';
	if (($__templater->func('property', array('uix_fab', ), false) != 'never') AND (!$__templater->func('property', array('uix_fabScroll', ), false))) {
		$__finalCompiled .= '
					';
		$__compilerTemp66 = '';
		$__compilerTemp66 .= (isset($__templater->pageParams['pageAction']) ? $__templater->pageParams['pageAction'] : '');
		if (strlen(trim($__compilerTemp66)) > 0) {
			$__finalCompiled .= '
						<div class="uix_fabBar uix_fabBar--mirror">
							<div class="p-title-pageAction">' . $__compilerTemp66 . '</div>
						</div>
					';
		}
		$__finalCompiled .= '
				';
	}
	$__finalCompiled .= '
				';
	if ($__templater->func('property', array('uix_visitorTabsMobile', ), false) == 'tabbar') {
		$__finalCompiled .= '
					' . $__templater->callMacro('uix_tabBar', 'uix_tabBar', array(), $__vars) . '
				';
	}
	$__finalCompiled .= '
			</div>
		</div>

		<div class="u-bottomFixer js-bottomFixTarget">
			';
	if ($__vars['notices']['floating']) {
		$__finalCompiled .= '
				' . $__templater->callMacro('notice_macros', 'notice_list', array(
			'type' => 'floating',
			'notices' => $__vars['notices']['floating'],
		), $__vars) . '
			';
	}
	$__finalCompiled .= '
			';
	if ($__vars['notices']['bottom_fixer']) {
		$__finalCompiled .= '
				' . $__templater->callMacro('notice_macros', 'notice_list', array(
			'type' => 'bottom_fixer',
			'notices' => $__vars['notices']['bottom_fixer'],
		), $__vars) . '
			';
	}
	$__finalCompiled .= '
		</div>

		' . $__templater->includeTemplate('uix_config', $__vars) . '

		' . $__templater->callMacro('helper_js_global', 'body', array(
		'app' => 'public',
		'jsState' => $__vars['jsState'],
	), $__vars) . '
		
		';
	if ($__templater->func('count', array($__vars['xf']['reactionsActive'], ), false) > 1) {
		$__finalCompiled .= '
			<script type="text/template" id="xfReactTooltipTemplate">
			<div class="tooltip-content-inner">
				<div class="reactTooltip">
					';
		if ($__templater->isTraversable($__vars['xf']['reactionsActive'])) {
			foreach ($__vars['xf']['reactionsActive'] AS $__vars['reactionId'] => $__vars['reaction']) {
				$__finalCompiled .= '
						' . $__templater->func('reaction', array(array(
					'id' => $__vars['reactionId'],
					'tooltip' => 'true',
				))) . '
				';
			}
		}
		$__finalCompiled .= '
				</div>
				</div>
			</script>
		';
	}
	$__finalCompiled .= '

		';
	if ($__templater->func('property', array('uix_loginStyle', ), false) == 'slidingPanel') {
		$__finalCompiled .= '
			<div class="uix__loginForm uix__loginForm--login">
				<div class="uix__loginForm--panel">
					';
		$__compilerTemp67 = $__vars;
		$__compilerTemp67['uix_loginPreventAutoFocus'] = '1';
		$__finalCompiled .= $__templater->includeTemplate('login', $__compilerTemp67) . '
				</div>
				<div class="uix__loginForm--mask"></div>
			</div>
		';
	}
	$__finalCompiled .= '

		' . $__templater->filter($__vars['ldJsonHtml'], array(array('raw', array()),), true) . '

	</body>
</html>

' . '

' . '

';
	return $__finalCompiled;
}
);