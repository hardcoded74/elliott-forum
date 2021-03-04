<?php
// FROM HASH: 15f9ece1651b402dc17ec94175f4ff41
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="sidePanel sidePanel--nav sidePanel--visitor">
	<div class="sidePanel__tabPanels">
		
		<div data-content="navigation" class="is-active sidePanel__tabPanel js-navigationTabPanel">
			' . $__templater->callMacro('PAGE_CONTAINER', 'canvasNavPanel', array(
		'widgets' => $__vars['uix_sidebarNavWidgets'],
	), $__vars) . '
		</div>
		
		';
	if ($__vars['xf']['visitor']['user_id'] AND ($__templater->func('property', array('uix_visitorTabsMobile', ), false) == 'canvas')) {
		$__finalCompiled .= '
			
		<div data-content="account" class="sidePanel__tabPanel js-visitorTabPanel">
		<div class="uix_canvasPanelBody" data-menu="menu" aria-hidden="true"
			 data-href="' . $__templater->func('link', array('account/visitor-menu', ), true) . '"
			 data-load-target=".js-visitorMenuBody">
			<div class="menu-content">
				<div class="offCanvasMenu-header">
					' . 'Your account' . '
					<a class="offCanvasMenu-closer" data-menu-close="true" role="button" tabindex="0" aria-label="' . 'Close' . '"></a>
				</div>
				<div class="js-visitorMenuBody"></div>
			</div>
		</div>
		</div>
		
		<div data-content="inbox" class="sidePanel__tabPanel js-convoTabPanel">
			<div class="menu-content">
				<div class="uix_canvasPanelBody" data-menu="menu" aria-hidden="true"
					 data-href="' . $__templater->func('link', array('conversations/popup', ), true) . '"
					 data-nocache="true"
					 data-target=".js-convMenuBody">
					<div class="offCanvasMenu-header">
						' . 'Conversations' . '
						<a class="offCanvasMenu-closer" data-menu-close="true" role="button" tabindex="0" aria-label="' . 'Close' . '"></a>
					</div>
					<div class="js-convMenuBody">
					</div>
				</div>
				<div class="menu-footer">
					<a href="' . $__templater->func('link', array('conversations/add', ), true) . '" class="u-pullRight">' . 'Start a new conversation' . '</a>
					<a href="' . $__templater->func('link', array('conversations', ), true) . '">' . 'Show all' . $__vars['xf']['language']['ellipsis'] . '</a>
				</div>
			</div>
		</div>
		
		<div data-content="alerts" class="sidePanel__tabPanel js-alertTabPanel">
			<div class="menu-content">
				<div class="uix_canvasPanelBody" data-menu="menu" aria-hidden="true"
					 data-href="' . $__templater->func('link', array('account/alerts-popup', ), true) . '"
					 data-nocache="true"
					 data-target=".js-alertsMenuBody">
					<div class="offCanvasMenu-header">
						' . 'Forum Alerts' . '
						<a class="offCanvasMenu-closer" data-menu-close="true" role="button" tabindex="0" aria-label="' . 'Close' . '"></a>
					</div>
					<div class="js-alertsMenuBody">
					</div>
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
	$__finalCompiled .= '
		
	</div>
</div>';
	return $__finalCompiled;
}
);