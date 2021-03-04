<?php
// FROM HASH: c7c9fd19975ab96a55629d615699c385
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to delete the following forum and all discussions within it' . $__vars['xf']['language']['label_separator'] . '
				<strong><a href="' . $__templater->func('link', array('layout-separators/edit', $__vars['node'], ), true) . '">' . $__templater->escape($__vars['node']['title']) . '</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->callMacro('helper_node_delete_children', 'delete_row', array(
		'node' => $__vars['node'],
		'nodeTree' => $__vars['nodeTree'],
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => ((!$__templater->method($__vars['node'], 'hasChildren', array())) ? 'simple' : ''),
	)) . '
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('layout-separators/delete', $__vars['node'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);