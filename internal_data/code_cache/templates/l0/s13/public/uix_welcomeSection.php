<?php
// FROM HASH: 2fc5b6a0e59c1902690a36c7d5e5d952
return array(
'macros' => array('welcomeSection' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'location' => '!',
		'showWelcomeSection' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['showWelcomeSection']) {
		$__finalCompiled .= '
		';
		if ($__vars['location'] == $__templater->func('property', array('uix_welcomeSectionLocation', ), false)) {
			$__finalCompiled .= '
			';
			$__templater->includeCss('uix_welcomeSection.less');
			$__finalCompiled .= '

			<div class="uix_welcomeSection">
				<div class="uix_welcomeSection__inner">

					<div class="media__container">

						';
			if ($__templater->func('property', array('uix_welcomeSection__icon', ), false)) {
				$__finalCompiled .= '
						<div class="media__object media--left">
							<span class="uix_welcomeSection__icon"><i class="uix_icon ' . $__templater->func('property', array('uix_welcomeSection__icon', ), true) . '"></i></span>
						</div>
						';
			}
			$__finalCompiled .= '

						<div class="media__body">
							';
			if ($__templater->func('property', array('uix_welcomeSection__title', ), false)) {
				$__finalCompiled .= '<div class="uix_welcomeSection__title">' . $__templater->func('property', array('uix_welcomeSection__title', ), true) . '</div>';
			}
			$__finalCompiled .= '

							';
			if ($__templater->func('property', array('uix_welcomeSection__text', ), false)) {
				$__finalCompiled .= '<div class="uix_welcomeSection__text">' . $__templater->func('property', array('uix_welcomeSection__text', ), true) . '</div>';
			}
			$__finalCompiled .= '

							';
			if ($__templater->func('property', array('uix_welcomeSection__url', ), false)) {
				$__finalCompiled .= $__templater->button($__templater->func('property', array('uix_welcomeSection__buttonText', ), true), array(
					'href' => $__templater->func('link', array($__templater->func('property', array('uix_welcomeSection__url', ), false), ), false),
					'class' => 'button--cta',
				), '', array(
				));
			}
			$__finalCompiled .= '
						</div>
					</div>
				</div>
			</div>
		';
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

	return $__finalCompiled;
}
);