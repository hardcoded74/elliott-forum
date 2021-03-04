<?php
// FROM HASH: e594a68a72a184cbf9bce88aa029ab6f
return array(
'macros' => array('content' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'icon' => '',
		'content' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if (($__templater->func('property', array('uix_iconFontFamily', ), false) != 'fontawesome')) {
		$__finalCompiled .= '

	.xf-uix_iconFont();
	content: \'' . $__templater->escape($__vars['icon']) . '\';
	';
		if ($__vars['icon'] == 'article') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_article\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'toggle-off') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_toggleOff\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'toggle-on') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_toggleOn\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'vimeo') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_vimeo\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'twitch') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_twitch\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'spotify') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_spotify\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'apple') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_apple\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'youtube') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_youtube\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'camera') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_camera\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'checkbox') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_checkbox\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'checkbox-checked') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_checkboxChecked\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'radio') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_radio\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'radio-selected') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_radioSelected\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'disable') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_disable\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'alert') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_alert\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'alert-off') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_alertOff\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'collapse') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_collapse\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'expand') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_expand\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'new-thread') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_newThread\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'download') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_download\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'user') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_user\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'grid') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_email\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'rss') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_rss\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'folder') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_folder\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'home') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_home\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'email') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_email\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'inbox') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_email\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'menu-down') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_menuDown\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'menu-left') {
			$__finalCompiled .= '
		';
			if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_menuLeft\';
		';
			} else {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_menuRight\';
		';
			}
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'menu-right') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_menuRight\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'attention') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_attention\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'menu-up') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_menuUp\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'menu') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_menu\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'chevron-right') {
			$__finalCompiled .= '
		';
			if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_chevronRight\';
		';
			} else {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_chevronLeft\';
		';
			}
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'chevron-left') {
			$__finalCompiled .= '
		';
			if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_chevronLeft\';
		';
			} else {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_chevronRight\';
		';
			}
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'chevron-down') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_chevronDown\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'chevron-up') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_chevronUp\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'file') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_file\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'arrow-up-circle') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_arrowUpVariant\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'arrow-up') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_arrowUp\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'arrow-down') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_arrowDown\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'arrow-left') {
			$__finalCompiled .= '
		';
			if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_arrowLeft\';
		';
			} else {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_arrowRight\';
		';
			}
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'arrow-right') {
			$__finalCompiled .= '
		';
			if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_arrowRight\';
		';
			} else {
				$__finalCompiled .= '
			content: \'@xf-uix_icon_arrowLeft\';
		';
			}
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'close') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_close\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'tag-multiple') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_tagMultiple\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'search-plus') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_searchPlus\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'search') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_search\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'plus') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_plus\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'minus') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_minus\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'user-multiple') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_userMultiple\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'graph') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_graph\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'clock') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_clock\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'brush') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_brush\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'reply') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_reply\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'like') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_like\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'unlike') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_unlike\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'delete') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_delete\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'moderate') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_moderate\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'statistics') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_statistics\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'warning') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_warning\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'ignored') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_ignored\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'forum') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_forum\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'page') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_page\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'earth') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_earth\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'link') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_link\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'search-member') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_searchMember\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'check') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_check\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'lock') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_lock\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'share') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_share\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'redirect') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_redirect\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'messages') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_messages\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'post') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_post\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'star') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_star\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'embed') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_embed\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'star-half') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_starHalf\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'star-empty') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_starEmpty\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'sticky') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_sticky\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'watched') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_watched\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'poll') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_poll\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'facebook') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_facebook\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'twitter') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_twitter\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'google-plus') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_googlePlus\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'pinterest') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_pinterest\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'tumblr') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_tumblr\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'instagram') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_instagram\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'reddit') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_reddit\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'whatsapp') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_whatsapp\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'github') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_github\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'linkedin') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_linkedin\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'microsoft') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_microsoft\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'export') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_export\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'import') {
			$__finalCompiled .= 'content: \'@xf-uix_iconImport\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'edit') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_edit\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'save') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_save\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'quote') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_quote\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'payment') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_payment\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'shopping-cart') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_shoppingCart\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'birthday') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_birthday\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'sort') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_sort\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'upload') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_upload\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'attachment') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_attachment\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'login') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_login\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'register') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_register\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'rate') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_rate\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'convert') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_convert\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'trophy') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_trophy\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'report') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_report\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'ipaddress') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_ipAddress\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'history') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_history\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'warn') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_warn\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'spam') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_spam\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'settings') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_settings\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'file-document') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_fileDocument\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'comment-multiple') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_commentMultiple\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'bookmark') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_bookmark\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'help') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_help\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'refresh') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_refresh\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'unlock') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_unlock\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'location') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_location\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'web') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_web\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'list') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_list\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'comment-alert') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_commentAlert\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'whats-new') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_whatsNew\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'attention') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_attention\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'merge') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_merge\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'move') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_move\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'clone') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_clone\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'info') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_info\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'media') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_media\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'resource') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_resource\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'video') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_video\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'audio') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_audio\';';
		}
		$__finalCompiled .= '
	';
		if ($__vars['icon'] == 'steam') {
			$__finalCompiled .= 'content: \'@xf-uix_icon_steam\';';
		}
		$__finalCompiled .= '

	';
	}
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
),
'icon' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'icon' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
<i class="uix_icon uix_icon--' . $__templater->escape($__vars['icon']) . '"></i>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '/* ' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'account',
	), $__vars) . ' */

' . '

' . '

';
	if (($__templater->func('property', array('uix_iconFontFamily', ), false) != 'fontawesome')) {
		$__finalCompiled .= '
.uix_icon--toggle-on:before {content: \'@xf-uix_icon_toggleOn\';}
.uix_icon--toggle-off:before {content: \'@xf-uix_icon_toggleOff\';}
.uix_icon--alert:before {content: \'@xf-uix_icon_alert\';}
.uix_icon--alert-off:before {content: \'@xf-uix_icon_alertOff\';}
.uix_icon--user:before {content: \'@xf-uix_icon_user\';}
.uix_icon--grid:before {content: \'@xf-uix_icon_grid\';}
.uix_icon--rss:before {content: \'@xf-uix_icon_rss\';}
.uix_icon--folder:before {content: \'@xf-uix_icon_folder\';}
.uix_icon--home:before {content: \'@xf-uix_icon_home\';}
.uix_icon--email:before {content: \'@xf-uix_icon_email\';}
.uix_icon--inbox:before {content: \'@xf-uix_icon_inbox\';}
.uix_icon--menu-down:before {content: \'@xf-uix_icon_menuDown\';}
.uix_icon--menu-left:before {
	';
		if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_menuLeft\';
	';
		} else {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_menuRight\';
	';
		}
		$__finalCompiled .= '
}
.uix_icon--menu-right:before {
	';
		if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_menuRight\';
	';
		} else {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_menuLeft\';
	';
		}
		$__finalCompiled .= '
}
.uix_icon--menu-up:before {content: \'@xf-uix_icon_menuUp\';}
.uix_icon--menu:before {content: \'@xf-uix_icon_menu\';}
.uix_icon--chevron-right:before {
	';
		if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_chevronRight\';
	';
		} else {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_chevronLeft\';
	';
		}
		$__finalCompiled .= '
}
.uix_icon--chevron-left:before {
	';
		if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_chevronLeft\';
	';
		} else {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_chevronRight\';
	';
		}
		$__finalCompiled .= '
}
.uix_icon--chevron-down:before {content: \'@xf-uix_icon_chevronDown\';}
.uix_icon--chevron-up:before {content: \'@xf-uix_icon_chevronUp\';}
.uix_icon--file:before {content: \'@xf-uix_icon_file\';}
.uix_icon--arrow-up-circle:before {content: \'@xf-uix_icon_arrowUpCircle\';}
.uix_icon--arrow-up:before {content: \'@xf-uix_icon_arrowUp\';}
.uix_icon--arrow-down:before {content: \'@xf-uix_icon_arrowDown\';}
.uix_icon--arrow-left:before {
	';
		if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_arrowLeft\';
	';
		} else {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_arrowRight\';
	';
		}
		$__finalCompiled .= '
}
.uix_icon--arrow-right:before {
	';
		if ((($__vars['xf']['language']['text_direction'] == 'LTR') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'LTR')) OR (($__vars['xf']['language']['text_direction'] == 'RTL') AND ($__templater->func('property', array('uix_textDirection', ), false) == 'RTL'))) {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_arrowRight\';
	';
		} else {
			$__finalCompiled .= '
		content: \'@xf-uix_icon_arrowRight\';
	';
		}
		$__finalCompiled .= '
}

.uix_icon--spotify:before {content: \'@xf-uix_icon_spotify\';}
.uix_icon--soundcloud:before {content: \'@xf-uix_icon_soundcloud\';}
.uix_icon--flickr:before {content: \'@xf-uix_icon_flickr\';}
.uix_icon--close:before {content: \'@xf-uix_icon_close\';}
.uix_icon--apple:before {content: \'@xf-uix_icon_apple\';}
.uix_icon--youtube:before {content: \'@xf-uix_icon_youtube\';}
.uix_icon--camera:before {content: \'@xf-uix_icon_camera\';}
.uix_icon--tag-multiple:before {content: \'@xf-uix_icon_tagMultiple\';}
.uix_icon--search-plus:before {content: \'@xf-uix_icon_searchPlus\';}
.uix_icon--search:before {content: \'@xf-uix_icon_search\';}
.uix_icon--plus:before {content: \'@xf-uix_icon_plus\';}
.uix_icon--minus:before {content: \'@xf-uix_icon_minus\';}
.uix_icon--user-multiple:before {content: \'@xf-uix_icon_userMultiple\';}
.uix_icon--chart-bar:before {content: \'@xf-uix_icon_chartBar\';}
.uix_icon--clock:before {content: \'@xf-uix_icon_clock\';}
.uix_icon--brush:before {content: \'@xf-uix_icon_brush\';}
.uix_icon--reply:before {content: \'@xf-uix_icon_reply\';}
.uix_icon--like:before {content: \'@xf-uix_icon_like\';}
.uix_icon--unlike:before {content: \'@xf-uix_icon_unlike\';}
.uix_icon--delete:before {content: \'@xf-uix_icon_delete\';}
.uix_icon--moderate:before {content: \'@xf-uix_icon_moderate\';}
.uix_icon--statistics:before {content: \'@xf-uix_icon_statistics\';}
.uix_icon--warning:before {content: \'@xf-uix_icon_warning\';}
.uix_icon--ignored:before {content: \'@xf-uix_icon_ignored\';}
.uix_icon--forum:before {content: \'@xf-uix_icon_forum\';}
.uix_icon--page:before {content: \'@xf-uix_icon_page\';}
.uix_icon--earth:before {content: \'@xf-uix_icon_earth\';}
.uix_icon--link:before {content: \'@xf-uix_icon_link\';}
.uix_icon--search-member:before {content: \'@xf-uix_icon_searchMember\';}
.uix_icon--radio:before {content: \'@xf-uix_icon_radio\';}
.uix_icon--radio-selected:before {content: \'@xf-uix_icon_radioSelected\';}
.uix_icon--check:before {content: \'@xf-uix_icon_check\';}
.uix_icon--checkbox-checked:before {content: \'@xf-uix_icon_checkboxChecked\';}
.uix_icon--checkbox:before {content: \'@xf-uix_icon_checkbpx\';}
.uix_icon--lock:before {content: \'@xf-uix_icon_lock\';}
.uix_icon--share:before {content: \'@xf-uix_icon_share\';}
.uix_icon--redirect:before {content: \'@xf-uix_icon_redirect\';}
.uix_icon--messages:before {content: \'@xf-uix_icon_messages\';}
.uix_icon--post:before {content: \'@xf-uix_icon_post\';}
.uix_icon--star:before {content: \'@xf-uix_icon_star\';}
.uix_icon--star-empty:before {content: \'@xf-uix_icon_starEmpty\';}
.uix_icon--star-half:before {content: \'@xf-uix_icon_starHalf\';}
.uix_icon--sticky:before {content: \'@xf-uix_icon_sticky\';}
.uix_icon--watched:before {content: \'@xf-uix_icon_watched\';}
.uix_icon--poll:before {content: \'@xf-uix_icon_poll\';}
.uix_icon--facebook:before {content: \'@xf-uix_icon_facebook\';}
.uix_icon--twitter:before {content: \'@xf-uix_icon_twitter\';}
.uix_icon--instagram:before {content: \'@xf-uix_icon_instagram\';}
.uix_icon--google-plus:before {content: \'@xf-uix_icon_googlePlus\';}
.uix_icon--pinterest:before {content: \'@xf-uix_icon_pinterest\';}
.uix_icon--tumblr:before {content: \'@xf-uix_icon_tumblr\';}
.uix_icon--reddit:before {content: \'@xf-uix_icon_reddit\';}
.uix_icon--whatsapp:before {content: \'@xf-uix_icon_whatsapp\';}
.uix_icon--github:before {content: \'@xf-uix_icon_github\';}
.uix_icon--linkedin:before {content: \'@xf-uix_icon_linkedin\';}
.uix_icon--microsoft:before {content: \'@xf-uix_icon_microsoft\';}
.uix_icon--export:before {content: \'@xf-uix_icon_export\';}
.uix_icon--import:before {content: \'@xf-uix_icon_import\';}
.uix_icon--edit:before {content: \'@xf-uix_icon_edit\';}
.uix_icon--save:before {content: \'@xf-uix_icon_save\';}
.uix_icon--quote:before {content: \'@xf-uix_icon_quote\';}
.uix_icon--payment:before {content: \'@xf-uix_icon_payment\';}
.uix_icon--shopping-cart:before {content: \'@xf-uix_icon_shoppingCart\';}
.uix_icon--birthday:before {content: \'@xf-uix_icon_birthday\';}
.uix_icon--sort:before {content: \'@xf-uix_icon_sort\';}
.uix_icon--upload:before {content: \'@xf-uix_icon_upload\';}
.uix_icon--attachment:before {content: \'@xf-uix_icon_attachment\';}
.uix_icon--login:before {content: \'@xf-uix_icon_login\';}
.uix_icon--register:before {content: \'@xf-uix_icon_register\';}
.uix_icon--rate:before {content: \'@xf-uix_icon_rate\';}
.uix_icon--convert:before {content: \'@xf-uix_icon_convert\';}
.uix_icon--trophy:before {content: \'@xf-uix_icon_trophy\';}
.uix_icon--report:before {content: \'@xf-uix_icon_report\';}
.uix_icon--ipaddress:before {content: \'@xf-uix_icon_ipaddress\';}
.uix_icon--history:before {content: \'@xf-uix_icon_history\';}
.uix_icon--warn:before {content: \'@xf-uix_icon_warn\';}
.uix_icon--spam:before {content: \'@xf-uix_icon_spam\';}
.uix_icon--settings:before {content: \'@xf-uix_icon_settings\';}
.uix_icon--file-document:before {content: \'@xf-uix_icon_fileDocument\';}
.uix_icon--comment-multiple:before {content: \'@xf-uix_icon_commentMultiple\';}
.uix_icon--bookmark:before {content: \'@xf-uix_icon_bookmark\';}
.uix_icon--help:before {content: \'@xf-uix_icon_help\';}
.uix_icon--refresh:before {content: \'@xf-uix_icon_refresh\';}
.uix_icon--unlock:before {content: \'@xf-uix_icon_unlock\';}
.uix_icon--location:before {content: \'@xf-uix_icon_location\';}
.uix_icon--web:before {content: \'@xf-uix_icon_web\';}
.uix_icon--list:before {content: \'@xf-uix_icon_list\';}
.uix_icon--comment-alert:before {content: \'@xf-uix_icon_commentAlert\';}
.uix_icon--download:before {content: \'@xf-uix_icon_download\';}
.uix_icon--whats-new:before {content: \'@xf-uix_icon_whatsNew\';}
.uix_icon--new-thread:before {content: \'@xf-uix_icon_newThread\';}
.uix_icon--collapse:before {content: \'@xf-uix_icon_collapse\';}
.uix_icon--expand:before {content: \'@xf-uix_icon_expand\';}
.uix_icon--merge:before {content: \'@xf-uix_icon_merge\';}
.uix_icon--move:before {content: \'@xf-uix_icon_move\';}
.uix_icon--clone:before {content: \'@xf-uix_icon_clone\';}
.uix_icon--info:before {content: \'@xf-uix_icon_info\';}
.uix_icon--media:before {content: \'@xf-uix_icon_media\';}
.uix_icon--resource:before {content: \'@xf-uix_icon_resource\';}
.uix_icon--embed:before {content: \'@xf-uix_icon_embed\';}
.uix_icon--video:before {content: \'@xf-uix_icon_video\';}
.uix_icon--audio:before {content: \'@xf-uix_icon_audio\';}
.uix_icon--graph:before {content: \'@xf-uix_icon_graph\';}
.uix_icon--disable:before {content: \'@xf-uix_icon_disable\';}
.uix_icon--twitch:before {content: \'@xf-uix_icon_twitch\';}
.uix_icon--vimeo:before {content: \'@xf-uix_icon_vimeo\';}
.uix_icon--article:before {content: \'@xf-uix_icon_article\';}
.uix_icon--steam:before {content: \'@xf-uix_icon_steam\';}
';
	}
	$__finalCompiled .= '

// external anchors icons
/*
';
	if ($__templater->func('property', array('uix_externalLinkIcon', ), false)) {
		$__finalCompiled .= '
	a[href]:not([href*=\'\']):not([href*=\'' . $__templater->escape($__vars['xf']['options']['boardUrl']) . '\']):not([href*=\'' . $__templater->escape($__vars['xf']['options']['homePageUrl']) . '\']):not( [href^=\'#\'] ):not( [href^=\'/\'] ):after {
	    ' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'open-in-new',
		), $__vars) . '
	}
';
	}
	$__finalCompiled .= '
*/

/* -- MATERIAL ICONS -- */

' . '

';
	if (($__templater->func('property', array('uix_iconFontFamily', ), false) != 'fontawesome')) {
		$__finalCompiled .= '
.mdi:before,
.uix_icon {
	display: inline-block;
	font: normal normal normal 18px/1 "Material Design Icons";
	font-size: inherit;
	text-rendering: auto;
	line-height: inherit;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	transform: translate(0, 0);
	width: auto;
}
';
	}
	return $__finalCompiled;
}
);