<?php
// FROM HASH: 4c7457e5deb274922c8e0ffdc972bffc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['xf']['options']['thtopics_enableTopics']) {
		$__finalCompiled .= '
	';
		$__templater->includeCss('thtopics.less');
		$__finalCompiled .= '
	';
		$__templater->includeCss('thtopics_topic_cache.less');
		$__finalCompiled .= '

	<div class="p-body-header--inner uix_headerInner">
		';
		if (($__vars['template'] === 'thread_view') OR ($__vars['template'] === 'topic_view')) {
			$__finalCompiled .= '
			<ul class="thTopicHeading__container__topics">
				<li class="thTopicHeading__container__topics__topic"><a href="' . $__templater->func('link', array('forums/all-threads', null, array('topics' => ($__vars['topic']['title'] . '.') . $__vars['topic']['topic_id'], ), ), true) . '" class="thTopic thTopic--inverted thTopic--' . $__templater->escape($__vars['topic']['topic_id']) . ' ' . $__templater->escape($__vars['topic']['extra_class']) . '">' . $__templater->escape($__vars['topic']['title']) . '</a></li>
				';
			if ($__templater->isTraversable($__vars['thread']['additional_topics'])) {
				foreach ($__vars['thread']['additional_topics'] AS $__vars['additionalTopic']) {
					$__finalCompiled .= '
					<li class="thTopicHeading__container__topics__topic"><a href="' . $__templater->func('link', array('forums/all-threads', null, array('topics' => ($__vars['additionalTopic']['title'] . '.') . $__vars['additionalTopic']['topic_id'], ), ), true) . '" class="thTopic--additional ' . $__templater->escape($__vars['topic']['extra_class']) . '">' . $__templater->escape($__vars['additionalTopic']['title']) . '</a></li>
				';
				}
			}
			$__finalCompiled .= '
			</ul>
			<div class="p-title">
				<h1 class="p-title-value">' . $__templater->func('prefix', array('thread', $__vars['thread'], ), true) . $__templater->escape($__vars['thread']['title']) . '</h1>
			</div>
			<div class="p-description">' . $__templater->escape($__vars['description']) . '</div>
		';
		} else {
			$__finalCompiled .= '
			<div class="p-title">
				<h1 class="p-title-value">' . $__templater->escape($__vars['topic']['title']) . '</h1>
			</div>
			<div class="p-description">' . $__templater->escape($__vars['topic']['description']) . '</div>
		';
		}
		$__finalCompiled .= '
	</div>
	';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= (isset($__templater->pageParams['pageAction']) ? $__templater->pageParams['pageAction'] : '');
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
		<div class="uix_headerInner--opposite">
			<div class="p-title-pageAction">' . $__compilerTemp1 . '</div>
		</div>
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);