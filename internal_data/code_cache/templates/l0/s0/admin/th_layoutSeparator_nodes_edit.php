<?php
// FROM HASH: d2c05c32245bf16e2b55f072834a761f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['layoutSeparator'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add layout separator');
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit layout separator' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['node']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['layoutSeparator'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('layout-separators/delete', $__vars['node'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('node_edit_macros', 'title', array(
		'node' => $__vars['node'],
	), $__vars) . '

			<hr class="formRowSep" />
			' . $__templater->callMacro('node_edit_macros', 'position', array(
		'node' => $__vars['node'],
		'nodeTree' => $__vars['nodeTree'],
	), $__vars) . '
			<hr class="formRowSep" />

			' . '
			<hr class="formRowSep" />

			' . $__templater->formRow('
				<p class="u-muted">
					' . 'The following will set the default styling for this node, if you\'d like to edit the per-style options for it you will need to do so from the <a href="admin.php?node-layout">Node layout and styling</a> section.' . '
				</p>
			', array(
	)) . '
			' . $__templater->callMacro('th_node_layout_edit_nodes', 'grid_options', array(
		'nodeStyling' => $__vars['nodeStyling'],
		'autoEnable' => '1',
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('layout-separators/save', $__vars['node'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	)) . '
';
	return $__finalCompiled;
}
);