<?php
// FROM HASH: b8d754b0cfa06d915b600aac95d1998e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.itemList-itemTypeIcon
{
	&.itemList-itemTypeIcon--album
	{
		&::after
		{
			.m-faContent(@fa-var-folder-open);
			' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'folder',
	), $__vars) . '
		}
	}
}

' . $__templater->includeTemplate('xfmg_item_list.less', $__vars);
	return $__finalCompiled;
}
);