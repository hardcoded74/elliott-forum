<?php
// FROM HASH: 3e3a2c3a7bc2faae9d0568fe7593ed05
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('bb_code.less');
	$__finalCompiled .= '

';
	$__vars['isIgnored'] = ($__vars['attributes']['member'] AND $__templater->method($__vars['xf']['visitor'], 'isIgnoring', array($__vars['attributes']['member'], )));
	$__finalCompiled .= '

<blockquote class="bbCodeBlock bbCodeBlock--expandable bbCodeBlock--quote js-expandWatch">
	';
	if ($__vars['name']) {
		$__finalCompiled .= '
		<div class="bbCodeBlock-title">
			';
		if ($__vars['source']) {
			$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('goto/' . $__vars['source']['type'], null, array('id' => $__vars['source']['id'], ), ), true) . '"
					class="bbCodeBlock-sourceJump"
					data-xf-click="attribution"
					data-content-selector="#' . $__templater->escape($__vars['source']['type']) . '-' . $__templater->escape($__vars['source']['id']) . '">' . '' . $__templater->escape($__vars['name']) . ' said' . $__vars['xf']['language']['label_separator'] . '</a>
			';
		} else {
			$__finalCompiled .= '
				' . '' . $__templater->escape($__vars['name']) . ' said' . $__vars['xf']['language']['label_separator'] . '
			';
		}
		$__finalCompiled .= '
		</div>
	';
	}
	$__finalCompiled .= '
	<div class="bbCodeBlock-content">
		';
	if ($__vars['isIgnored']) {
		$__finalCompiled .= '
			<div class="messageNotice messageNotice--nested messageNotice--ignored">
				' . 'You are ignoring content by this member.' . '
				' . $__templater->func('show_ignored', array(array(
		))) . '
			</div>
		';
	}
	$__finalCompiled .= '
		<div class="bbCodeBlock-expandContent js-expandContent ' . ($__vars['isIgnored'] ? 'is-ignored' : '') . '">
			' . $__templater->escape($__vars['content']) . '
		</div>
		<div class="bbCodeBlock-expandLink js-expandLink"><a>' . 'Click to expand...' . '</a></div>
	</div>
</blockquote>';
	return $__finalCompiled;
}
);