<?php
// FROM HASH: 1f2b7d049c710b641f8f93ad8f875ff4
return array(
'macros' => array('edit_rows' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'uneditableTags' => '!',
		'editableTags' => '!',
		'minTags' => 0,
		'tagList' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeJs(array(
		'src' => 'xf/tag.js',
	));
	$__finalCompiled .= '

	';
	if ($__vars['uneditableTags']) {
		$__finalCompiled .= '
		' . $__templater->formRow('
			' . $__templater->filter($__vars['uneditableTags'], array(array('pluck', array('tag', )),array('join', array(', ', )),), true) . '
		', array(
			'label' => 'Uneditable tags',
		)) . '
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	if ($__vars['minTags']) {
		$__compilerTemp1 .= '
				' . 'This content must have at least ' . $__templater->escape($__vars['minTags']) . ' tag(s).' . '
			';
	}
	$__finalCompiled .= $__templater->formTokenInputRow(array(
		'name' => 'tags',
		'value' => $__templater->filter($__vars['editableTags'], array(array('pluck', array('tag', )),array('join', array(', ', )),), false),
		'href' => $__templater->func('link', array('misc/tag-auto-complete', ), false),
	), array(
		'label' => 'Tags',
		'explain' => '
			' . 'Multiple tags may be separated by commas.' . '
			' . $__compilerTemp1 . '
		',
	)) . '
';
	return $__finalCompiled;
}
),
'edit_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'action' => '!',
		'uneditableTags' => '!',
		'editableTags' => '!',
		'minTags' => 0,
		'tagList' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	' . $__templater->form('
		<div class="block-container">
			<div class="block-body">
				' . $__templater->callMacro(null, 'edit_rows', array(
		'uneditableTags' => $__vars['uneditableTags'],
		'editableTags' => $__vars['editableTags'],
		'minTags' => $__vars['minTags'],
		'listElement' => $__vars['listElement'],
	), $__vars) . '
			</div>
			' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
		</div>
	', array(
		'action' => $__vars['action'],
		'class' => 'block',
		'ajax' => 'true',
		'data-xf-init' => 'tagger',
		'data-tag-list' => $__vars['tagList'],
	)) . '
';
	return $__finalCompiled;
}
),
'list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'tags' => '!',
		'tagList' => '!',
		'editLink' => '!',
		'containerClass' => '',
		'highlightTerm' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__vars['tagIcon'] = $__templater->preEscaped('
		' . $__templater->fontAwesome('fa-tags', array(
		'title' => $__templater->filter('Tags', array(array('for_attr', array()),), false),
	)) . '
		<span class="u-srOnly">' . 'Tags' . '</span>
	');
	$__finalCompiled .= '

	<dl class="tagList ' . $__templater->escape($__vars['tagList']) . ' ' . ($__templater->escape($__vars['containerClass']) ?: '') . '">
		<dt>
			';
	if ($__vars['editLink']) {
		$__finalCompiled .= '
				<a href="' . $__templater->escape($__vars['editLink']) . '" class="u-concealed--icon"
					data-xf-click="overlay"
					data-xf-init="tooltip" title="' . $__templater->filter('Edit tags', array(array('for_attr', array()),), true) . '">' . $__templater->escape($__vars['tagIcon']) . '</a>
			';
	} else {
		$__finalCompiled .= '
				' . $__templater->escape($__vars['tagIcon']) . '
			';
	}
	$__finalCompiled .= '
		</dt>
		<dd>
			<span class="js-tagList">
				';
	if ($__vars['tags']) {
		$__finalCompiled .= '
					';
		if ($__templater->isTraversable($__vars['tags'])) {
			foreach ($__vars['tags'] AS $__vars['tag']) {
				$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('tags', $__vars['tag'], ), true) . '" class="tagItem tagItem--tag_' . $__templater->escape($__vars['tag']['tag_url']) . '" dir="auto">
							' . ($__vars['highlightTerm'] ? $__templater->func('highlight', array($__vars['tag']['tag'], $__vars['highlightTerm'], ), true) : $__templater->escape($__vars['tag']['tag'])) . '
						</a>
					';
			}
		}
		$__finalCompiled .= '
				';
	} else {
		$__finalCompiled .= '
					' . 'None' . '
				';
	}
	$__finalCompiled .= '
			</span>
		</dd>
	</dl>

';
	return $__finalCompiled;
}
),
'simple_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'tags' => '!',
		'containerClass' => '',
		'highlightTerm' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__vars['tags']) {
		$__finalCompiled .= '
		';
		if ($__templater->isTraversable($__vars['tags'])) {
			foreach ($__vars['tags'] AS $__vars['tag']) {
				$__finalCompiled .= '
			<span class="tagItem tagItem--tag_' . $__templater->escape($__vars['tag']['tag_url']) . '" dir="auto">
				' . ($__vars['highlightTerm'] ? $__templater->func('highlight', array($__vars['tag']['tag'], $__vars['highlightTerm'], ), true) : $__templater->escape($__vars['tag']['tag'])) . '
			</span>
		';
			}
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
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);