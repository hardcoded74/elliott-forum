<?php
// FROM HASH: de5b1cb89b74f53efc62193abf815007
return array(
'macros' => array('node_list_item_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'node' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->func('property', array('th_enableNodeFooter_nodes', ), false)) {
		$__finalCompiled .= '
		<div class="block-footer has-flexbox">
			<div class="node-footer--more">
				<a href="' . $__templater->func('link', array($__templater->method($__vars['node'], 'getRoute', array()), $__vars['node'], ), true) . '">More</a>
			</div>
			<div class="node-footer--actions">
				';
		if (($__vars['node']['node_type_id'] === 'Forum') AND $__templater->method($__templater->method($__vars['node'], 'getData', array()), 'canCreateThread', array())) {
			$__finalCompiled .= '
					<a href="' . $__templater->func('link', array('forums/post-thread', $__vars['node'], ), true) . '" class="node-footer--createThread" data-xf-init="tooltip" title="' . 'Post thread' . $__vars['xf']['language']['ellipsis'] . '"></a>
				';
		}
		$__finalCompiled .= '
			</div>
		</div>
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

	return $__finalCompiled;
}
);