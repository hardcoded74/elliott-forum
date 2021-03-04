<?php
// FROM HASH: 4044fff62bde21a2554accffe0c0a431
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xfmg_gallery_bb_code.less');
	$__finalCompiled .= '

<div class="embeddedMedia">
	<div class="embeddedMedia-container">
		' . $__templater->callMacro('xfmg_media_view_macros', 'media_content', array(
		'mediaItem' => $__vars['mediaItem'],
		'linkMedia' => true,
	), $__vars) . '
	</div>
	<div class="embeddedMedia-info fauxBlockLink">
		<div class="contentRow">
			<div class="contentRow-main">
				<h4 class="contentRow-title">
					<a href="' . $__templater->func('link', array('media', $__vars['mediaItem'], ), true) . '" class="fauxBlockLink-blockLink u-cloaked">' . $__templater->escape($__vars['mediaItem']['title']) . '</a>
				</h4>
				<div class="contentRow-lesser p-description">
					<ul class="listInline listInline--bullet is-structureList">
						<li>' . $__templater->fontAwesome('fa-user', array(
		'title' => $__templater->filter('xfmg_media_owner', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('username_link', array($__vars['mediaItem']['User'], false, array(
		'defaultname' => $__vars['mediaItem']['username'],
		'class' => 'u-concealed',
	))) . '</li>
						<li>' . $__templater->fontAwesome('fa-clock', array(
		'title' => $__templater->filter('xfmg_date_added', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('date_dynamic', array($__vars['mediaItem']['media_date'], array(
	))) . '</li>
						';
	if ($__vars['mediaItem']['comment_count']) {
		$__finalCompiled .= '
							<li>' . $__templater->fontAwesome('fa-comments', array(
			'title' => $__templater->filter('xfmg_comments', array(array('for_attr', array()),), false),
		)) . ' ' . $__templater->filter($__vars['mediaItem']['comment_count'], array(array('number_short', array()),), true) . '</li>
						';
	}
	$__finalCompiled .= '
						';
	if ($__vars['xf']['options']['enableTagging'] AND $__vars['mediaItem']['tags']) {
		$__finalCompiled .= '
							<li>' . $__templater->fontAwesome('fa-tags', array(
			'title' => $__templater->filter('Tags', array(array('for_attr', array()),), false),
		)) . '
								';
		if ($__templater->isTraversable($__vars['mediaItem']['tags'])) {
			foreach ($__vars['mediaItem']['tags'] AS $__vars['tag']) {
				$__finalCompiled .= '
									<a href="' . $__templater->func('link', array('tags', $__vars['tag'], ), true) . '" class="tagItem">' . $__templater->escape($__vars['tag']['tag']) . '</a>
								';
			}
		}
		$__finalCompiled .= '
							</li>
						';
	}
	$__finalCompiled .= '
					</ul>
				</div>
				';
	if ($__vars['mediaItem']['description']) {
		$__finalCompiled .= '
					<div class="contentRow-snippet">
						' . $__templater->func('structured_text', array($__templater->func('snippet', array($__vars['mediaItem']['description'], 100, ), false), ), true) . '
					</div>
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);