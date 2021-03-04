<?php
// FROM HASH: 6332d815392ddfb0e42ec143c93f56df
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if (!$__templater->test($__vars['providers'], 'empty', array())) {
			$__compilerTemp1 .= '
					<div class="blocks-textJoiner"><span></span><em>' . 'or' . ' ' . 'Log in using' . '</em><span></span></div>

					<div class=" uix_loginProvider__row">
						';
			$__compilerTemp2 = '';
			if ($__templater->isTraversable($__vars['providers'])) {
				foreach ($__vars['providers'] AS $__vars['provider']) {
					$__compilerTemp2 .= '
									<li>
										' . $__templater->callMacro('connected_account_macros', 'button', array(
						'provider' => $__vars['provider'],
					), $__vars) . '
									</li>
								';
				}
			}
			$__compilerTemp1 .= $__templater->formRow('
							<ul class="listHeap">
								' . $__compilerTemp2 . '
							</ul>
						', array(
				'rowtype' => 'button',
			)) . '
					</div>
				';
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<h3 class="block-minorHeader">' . 'Log in' . '</h3>
			<div class="block-body">
				<div class="block-row">
					<label for="ctrl_loginWidget_login">' . 'Your name or email address' . '</label>
					<div class="u-inputSpacer">
						<input name="login" id="ctrl_loginWidget_login" class="input" />
					</div>
				</div>

				<div class="block-row">
					<label for="ctrl_loginWidget_password">' . 'Password' . '</label>
					<div class="u-inputSpacer">
						<input name="password" id="ctrl_loginWidget_password" type="password" class="input" />
						<a href="' . $__templater->func('link', array('lost-password', ), true) . '" data-xf-click="overlay">' . 'Forgot your password?' . '</a>
					</div>
				</div>

				<div class="block-row">
					<label>
						<input type="checkbox" name="remember" value="1" checked="checked"> ' . 'Stay logged in' . '
					</label>
				</div>

				' . $__compilerTemp1 . '

				' . $__templater->formSubmitRow(array(
			'icon' => 'login',
		), array(
			'rowtype' => 'simple',
		)) . '
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('login/login', ), false),
			'class' => 'block',
			'attributes' => $__templater->func('widget_data', array($__vars['widget'], true, ), false),
		)) . '
';
	}
	return $__finalCompiled;
}
);