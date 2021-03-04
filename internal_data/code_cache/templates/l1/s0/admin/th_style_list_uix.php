<?php
// FROM HASH: dbb73e9b7563a3e3b5f979057e1af389
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('ThemeHouse Styles');
	$__finalCompiled .= '

';
	if ((!$__vars['canWriteDirs']) AND (!$__vars['xf']['options']['th_enableFtp_uix'])) {
		$__finalCompiled .= '
	<div class="blockMessage blockMessage--error">
		<h2 style="margin: 0 0 .5em 0">UI.X Error</h2>
		<p>
			' . 'Your XenForo directory is not writable by the PHP user. You can contact your host and recommend they use a method that allows PHP to run as the same user that owns the XenForo files or alternatively you can <a href="https://www.themehouse.com/help/documentation/uix2/installing-uix-2-manually">install your style manually</a>.<br> Alternatively, you can enable the option to install via FTP <a href="' . $__templater->func('link', array('options/groups', array('group_id' => 'th_uix', ), ), true) . '" target="_BLANK">here</a> which should allow you to proceed.' . '
		</p>
	</div>
';
	}
	$__finalCompiled .= '

';
	$__templater->includeCss('th_style_list_uix.less');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['styles'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['styles'])) {
			foreach ($__vars['styles'] AS $__vars['style']) {
				$__compilerTemp1 .= '
						';
				$__compilerTemp2 = array(array(
					'label' => $__templater->escape($__vars['style']['product_name']),
					'hint' => $__templater->escape($__vars['style']['product_tagline']),
					'_type' => 'main',
					'html' => '',
				));
				if ($__vars['style']['installed']) {
					$__compilerTemp2[] = array(
						'class' => 'dataList-cell--min uix_dataList-cel uix_dataList-cel--childStyles',
						'href' => $__templater->func('link', array('styles/themehouse/child-styles', null, array('product_id' => $__vars['style']['id'], 'style_id' => $__vars['style']['installed']['style_id'], ), ), false),
						'_type' => 'cell',
						'html' => 'Child styles',
					);
					if ($__vars['style']['isOutdated']) {
						$__compilerTemp2[] = array(
							'class' => 'dataList-cell--min uix_dataList-cel uix_dataList-cel--upgrade',
							'href' => $__templater->func('link', array('styles/themehouse/upgrade', null, array('product_id' => $__vars['style']['id'], 'style_id' => $__vars['style']['installed']['style_id'], ), ), false),
							'_type' => 'cell',
							'html' => 'Upgrade',
						);
					} else {
						$__compilerTemp2[] = array(
							'class' => 'dataList-cell--min uix_dataList-cel uix_dataList-cel--forceReinstall',
							'href' => $__templater->func('link', array('styles/themehouse/upgrade', null, array('product_id' => $__vars['style']['id'], 'style_id' => $__vars['style']['installed']['style_id'], ), ), false),
							'_type' => 'cell',
							'html' => 'Force reinstall',
						);
					}
				} else {
					$__compilerTemp2[] = array(
						'class' => 'dataList-cel--min uix_dataList-cell',
						'_type' => 'cell',
						'html' => '',
					);
					$__compilerTemp2[] = array(
						'class' => 'dataList-cell--min uix_dataList-cel uix_dataList-cel--install',
						'href' => $__templater->func('link', array('styles/themehouse/install', null, array('product_id' => $__vars['style']['id'], ), ), false),
						'_type' => 'cell',
						'html' => 'Install',
					);
				}
				$__compilerTemp2[] = array(
					'class' => 'dataList-cell--min uix_dataList-cel uix_dataList-cel--documentation',
					'href' => $__vars['style']['information_url'],
					'target' => '_BLANK',
					'_type' => 'cell',
					'html' => 'Documentation',
				);
				$__compilerTemp1 .= $__templater->dataRow(array(
				), $__compilerTemp2) . '
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
				<span class="u-pullRight">' . 'Showing ' . $__templater->func('count', array($__vars['styles'], ), true) . ' of ' . $__templater->func('count', array($__vars['styles'], ), true) . ' items' . '</span>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('styles/themehouse', ), false),
			'class' => 'block',
		)) . '
	';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No active licenses found, if you believe this is an error please <a href="https://www.themehouse.com/contact/create-ticket/choose-product">contact us</a>' . '</div>
';
	}
	return $__finalCompiled;
}
);