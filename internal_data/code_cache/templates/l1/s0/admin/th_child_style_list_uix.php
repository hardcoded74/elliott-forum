<?php
// FROM HASH: b03e227854b9b95cff8f17815fe5bd8c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Child styles for ' . $__templater->escape($__vars['product']['product_name']) . '');
	$__finalCompiled .= '

';
	if ((!$__vars['canWriteDirs']) AND (!$__vars['xf']['options']['th_enableFtp_uix'])) {
		$__finalCompiled .= '
	<div class="blockMessage blockMessage--error">
		<h2 style="margin: 0 0 .5em 0">UI.X Error</h2>
		<p>
			Your XenForo directory is not writable by the PHP user. You can contact your host and recommend they use a method that allows PHP to run as the same user that owns the XenForo files or alternatively you can <a href="https://www.themehouse.com/help/documentation/uix2/installing-uix-2-manually">install your style manually</a>.<br>
			Alternatively, you can enable the option to install via FTP <a href="http://xf2.dev/admin.php?options/groups/th_uix/" target="_BLANK">here</a> which should allow you to proceed.
		</p>
	</div>
';
	}
	$__finalCompiled .= '

';
	$__templater->includeCss('th_style_list_uix.less');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['style']->{'th_child_style_cache_uix'}, 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		$__compilerTemp2 = $__templater->method($__vars['style'], 'getChildStyles', array());
		if ($__templater->isTraversable($__compilerTemp2)) {
			foreach ($__compilerTemp2 AS $__vars['xmlName'] => $__vars['childStyle']) {
				$__compilerTemp1 .= '
						';
				$__compilerTemp3 = array(array(
					'label' => $__templater->escape($__vars['childStyle']['style_name']),
					'_type' => 'main',
					'html' => '',
				));
				if (!$__vars['childStyle']['installed']) {
					$__compilerTemp3[] = array(
						'class' => 'dataList-cell--min uix_dataList-cel uix_dataList-cel--install',
						'href' => $__templater->func('link', array('styles/themehouse/child-styles/install', null, array('product_id' => $__vars['product']['id'], 'style_id' => $__vars['style']['style_id'], 'child_style' => $__vars['xmlName'], ), ), false),
						'_type' => 'cell',
						'html' => 'Install',
					);
				}
				$__compilerTemp1 .= $__templater->dataRow(array(
				), $__compilerTemp3) . '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<div class="block-body">
				' . $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
			</div>
			<div class="block-footer">
				<span class="u-pullRight">' . 'Showing ' . $__templater->func('count', array($__vars['style']->{'th_child_style_cache_uix'}, ), true) . ' of ' . $__templater->func('count', array($__vars['style']->{'th_child_style_cache_uix'}, ), true) . ' items' . '</span>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('styles/themehouse', ), false),
			'class' => 'block',
		)) . '
	';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No child styles found for ' . $__templater->escape($__vars['product']['product_name']) . '.' . '</div>
';
	}
	return $__finalCompiled;
}
);