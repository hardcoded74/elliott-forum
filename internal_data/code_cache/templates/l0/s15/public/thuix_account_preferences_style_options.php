<?php
// FROM HASH: a0aa97b22c7d1e8a9fa05979ece5d9eb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['xf']['options']['thuix_enableStyleOptions']) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = array();
		if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('th_uix', 'collapseSidebar', ))) {
			if ($__templater->func('in_array', array($__vars['xf']['options']['th_sidebarCollapseDefault_uix'], array(0 => 'registered', 1 => 'all', ), ), false)) {
				$__compilerTemp1[] = array(
					'name' => 'option[thuix_collapse_sidebar]',
					'checked' => ($__vars['xf']['visitor']['Option']['thuix_collapse_sidebar'] ? false : true),
					'label' => 'Show sidebar by default',
					'hint' => 'If the selected style includes a collapsible sidebar, this will show the sidebar on all pages by default.',
					'_type' => 'option',
				);
			} else {
				$__compilerTemp1[] = array(
					'name' => 'option[thuix_collapse_sidebar]',
					'checked' => ($__vars['xf']['visitor']['Option']['thuix_collapse_sidebar'] ? true : false),
					'label' => 'Collapse sidebar by default',
					'hint' => 'If the selected style includes a collapsible sidebar, this will collapse the sidebar on all pages by default.',
					'_type' => 'option',
				);
			}
		}
		if ($__templater->func('in_array', array($__vars['xf']['options']['th_sidebarNavCollapseDefault_uix'], array(0 => 'registered', 1 => 'all', ), ), false)) {
			$__compilerTemp1[] = array(
				'name' => 'option[thuix_collapse_sidebar_nav]',
				'checked' => ($__vars['xf']['visitor']['Option']['thuix_collapse_sidebar_nav'] ? false : true),
				'label' => 'Show side navigation by default',
				'hint' => 'If the selected style includes a collapsible side navigation, this will show the side navigation on all pages by default.',
				'_type' => 'option',
			);
		} else {
			$__compilerTemp1[] = array(
				'name' => 'option[thuix_collapse_sidebar_nav]',
				'checked' => ($__vars['xf']['visitor']['Option']['thuix_collapse_sidebar_nav'] ? true : false),
				'label' => 'Collapse side navigation by default',
				'hint' => 'If the selected style includes a collapsible side navigation, this will collapse the side navigation on all pages by default.',
				'_type' => 'option',
			);
		}
		if ($__templater->func('in_array', array($__vars['xf']['options']['th_postbitCollapseDefault_uix'], array(0 => 'registered', 1 => 'all', ), ), false)) {
			$__compilerTemp1[] = array(
				'name' => 'option[thuix_collapse_postbit]',
				'checked' => ($__vars['xf']['visitor']['Option']['thuix_collapse_postbit'] ? false : true),
				'label' => 'Show postbit by default',
				'hint' => 'If the selected style includes a collapsible postbit, this will show the postbit on all posts by default.',
				'_type' => 'option',
			);
		} else {
			$__compilerTemp1[] = array(
				'name' => 'option[thuix_collapse_postbit]',
				'checked' => ($__vars['xf']['visitor']['Option']['thuix_collapse_postbit'] ? true : false),
				'label' => 'Collapse postbit by default',
				'hint' => 'If the selected style includes a collapsible postbit, this will collapse the postbit on all posts by default.',
				'_type' => 'option',
			);
		}
		$__finalCompiled .= $__templater->formCheckBoxRow(array(
		), $__compilerTemp1, array(
			'label' => 'Style options',
		)) . '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['xf']['versionId'] >= 2010010) {
		$__finalCompiled .= '
	' . $__templater->formSelectRow(array(
			'name' => 'option[thuix_font_size]',
			'value' => $__vars['xf']['visitor']['Option']['thuix_font_size'],
		), array(array(
			'value' => '-1',
			'label' => 'Small',
			'_type' => 'option',
		),
		array(
			'value' => '0',
			'label' => 'Medium (recommended)',
			'_type' => 'option',
		),
		array(
			'value' => '1',
			'label' => 'Large',
			'_type' => 'option',
		),
		array(
			'value' => '2',
			'label' => 'Extra large',
			'_type' => 'option',
		)), array(
			'label' => 'Font size',
		)) . '
';
	}
	return $__finalCompiled;
}
);