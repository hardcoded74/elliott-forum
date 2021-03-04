<?php
// FROM HASH: a76c9dcfc8b5a3cb33b81b82606c8b49
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block" ' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
		<div class="block-body">
			<div class="block-row">
				';
	if ($__vars['options']['platform'] == 'facebook') {
		$__finalCompiled .= '
					<div class="fb-page" data-href="https://www.facebook.com/' . $__templater->escape($__vars['options']['name']) . '/" data-tabs="timeline" data-height="130px" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/' . $__templater->escape($__vars['options']['name']) . '/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/' . $__templater->escape($__vars['options']['name']) . '/">' . $__templater->escape($__vars['options']['name']) . '</a></blockquote></div>


					<div id="fb-root"></div>
					';
		$__templater->inlineJs('(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = \'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=152215748776501&autoLogAppEvents=1\';
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, \'script\', \'facebook-jssdk\'));');
		$__finalCompiled .= '
					';
	} else {
		$__finalCompiled .= '
					<a class="twitter-timeline" href="https://twitter.com/' . $__templater->escape($__vars['options']['name']) . '?ref_src=twsrc%5Etfw">Tweets by @' . $__templater->escape($__vars['options']['name']) . '</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
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