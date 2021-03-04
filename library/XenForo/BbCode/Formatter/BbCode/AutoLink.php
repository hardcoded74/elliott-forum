<?php

/**
 * BB code to BB code formatter that automatically links URLs and emails using
 * [url] and [email] tags.
 *
 * @package XenForo_BbCode
 */
class XenForo_BbCode_Formatter_BbCode_AutoLink extends XenForo_BbCode_Formatter_BbCode_Abstract
{
	/**
	 * Callback for all tags.
	 *
	 * @var callback
	 */
	protected $_generalTagCallback = array('$this', 'autoLinkTag');

	/**
	 * The tags that disable autolinking.
	 *
	 * @var array
	 */
	protected $_disableAutoLink = array('url', 'email', 'img', 'code', 'php', 'html', 'plain', 'media');

	/**
	 * Auto embed media settings.
	 *
	 * @var array
	 */
	protected $_autoEmbed = array();

	/**
	 * Amount of media embeds that can be automatically applied.
	 *
	 * @var integer 0 will disable auto embed
	 */
	protected $_autoEmbedRemaining = 0;

	protected $_enableAutoEmbed = true;

	protected $_fixProxy = true;

	protected $_filterer;

	protected $_parser;

	protected $_startTime;
	protected $_urlTitleTimeLimit = 10;

	public function __construct()
	{
		parent::__construct();

		$options = XenForo_Application::get('options');

		// TODO: end-user ability to disable auto-embedding on a per-post basis
		$this->_autoEmbed = $options->autoEmbedMedia;
		$this->_autoEmbedRemaining = ($options->messageMaxMedia ? $options->messageMaxMedia : PHP_INT_MAX);

		$this->_startTime = microtime(true);
	}

	public function addCustomTags(array $tags)
	{
		parent::addCustomTags($tags);

		foreach ($tags AS $tagName => $tag)
		{
			if ($tag['disable_autolink'] || $tag['plain_children'])
			{
				$this->_disableAutoLink[] = $tagName;
			}
		}
	}

	public function renderTree(array $tree, array $extraStates = array())
	{
		$this->_subtractMediaTagsRemaining($tree);
		return parent::renderTree($tree, $extraStates);
	}

	public function setAutoEmbed($enable)
	{
		$this->_enableAutoEmbed = $enable;
	}

	public function setFixProxy($fix)
	{
		$this->_fixProxy = $fix;
	}

	protected function _subtractMediaTagsRemaining(array $tree)
	{
		foreach ($tree AS $element)
		{
			if (is_array($element))
			{
				// tag
				if (strtolower($element['tag']) == 'media')
				{
					$this->_autoEmbedRemaining--;
				}
				$this->_subtractMediaTagsRemaining($element['children']);
			}
		}
	}

	/**
	 * Callback that all tags with go through. Changes the rendering state to disable
	 * URL parsing if necessary.
	 *
	 * @param array $tag
	 * @param array $rendererStates

	 * @return string
	 */
	public function autoLinkTag(array $tag, array $rendererStates)
	{
		if (in_array($tag['tag'], $this->_disableAutoLink))
		{
			$rendererStates['stopAutoLink'] = true;
		}

		if ($tag['tag'] == 'url'
			&& $this->_autoEmbed['embedType'] != XenForo_Helper_Media::AUTO_EMBED_MEDIA_DISABLED
			&& $this->_enableAutoEmbed
		)
		{
			$childText = $this->stringifyTree($tag['children'], $rendererStates);
			if (empty($tag['option']) || $tag['option'] == $childText)
			{
				return $this->_autoLinkUrl($childText);
			}
		}

		$text = $this->renderSubTree($tag['children'], $rendererStates);

		if (!empty($tag['original']) && is_array($tag['original']))
		{
			list($prepend, $append) = $tag['original'];
		}
		else
		{
			$prepend = '';
			$append = '';
		}

		// note: necessary to return prepend/append unfiltered to keep them unchanged
		return $prepend . $text . $append;
	}

	/**
	 * String filter that does link parsing if not disabled.
	 *
	 * @param string $string
	 * @param array $rendererStates List of states the renderer may be in
	 *
	 * @return string Filtered/escaped string
	 */
	public function filterString($string, array $rendererStates)
	{
		if (empty($rendererStates['stopAutoLink']))
		{
			$string = preg_replace_callback(
				'#(?<=[^a-z0-9@-]|^)(https?://|www\.)[^\s"]+#iu',
				array($this, '_autoLinkUrlCallback'),
				$string
			);

			if (strpos($string, '@') !== false)
			{
				// assertion to prevent matching email in url matched above (user:pass@example.com)
				$string = preg_replace(
					'#(?<=[\s"\']|^)[a-z0-9.+_-]+@[a-z0-9-]+(\.[a-z]+)+(?![^\s"]*\[/url\])#iu',
					'[email]$0[/email]',
					$string
				);
			}
		}

		return $string;
	}

	/**
	 * Callback for the auto-linker regex.
	 *
	 * @param array $match
	 *
	 * @return string
	 */
	protected function _autoLinkUrlCallback(array $match)
	{
		return $this->_autoLinkUrl($match[0]);
	}

	/**
	 * Stores the stream file path so it can be tidied up.
	 */
	protected $_streamFile;

	/**
	 * Handles autolinking the given URL.
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	protected function _autoLinkUrl($url)
	{
		$link = XenForo_Helper_String::prepareAutoLinkedUrl($url);

		if ($this->_fixProxy && preg_match('/proxy\.php\?[a-z0-9_]+=(http[^&]+)&/i', $link['url'], $match))
		{
			// proxy link of some sort, adjust to the original one
			$url = urldecode($match[1]);
			if (preg_match('/./u', $url))
			{
				if ($link['url'] == $link['linkText'])
				{
					$link['linkText'] = $url;
				}
				$link['url'] = $url;
			}
		}

		if ($link['url'] === $link['linkText'])
		{
			if ($this->_autoEmbed['embedType'] != XenForo_Helper_Media::AUTO_EMBED_MEDIA_DISABLED
				&& $this->_autoEmbedRemaining > 0
				&& $this->_enableAutoEmbed
				&& ($mediaTag = XenForo_Helper_Media::convertMediaLinkToEmbedHtml($link['url'], $this->_autoEmbed))
			)
			{
				$tag = $mediaTag;
				$this->_autoEmbedRemaining--;
			}
			else
			{
				$urlToPageTitle = XenForo_Application::getOptions()->urlToPageTitle;

				if (!empty($urlToPageTitle['enabled'])
					&& microtime(true) - $this->_startTime < $this->_urlTitleTimeLimit
					&& $link['url'] === XenForo_Helper_String::censorString($link['url'])
				)
				{
					$title = $this->_getUrlTitle($link['url']);
					if ($title)
					{
						$format = $urlToPageTitle['format'];
						if (!$format)
						{
							$format = '{title}';
						}

						$tokens = array(
							'{title}' => $title,
							'{url}' => $link['url']
						);
						$linkTitle = strtr($format, $tokens);

						$tag = '[URL="' . $link['url'] . '"]' . $linkTitle . '[/URL]';
					}
					else
					{
						$tag = '[URL]' . $link['url'] . '[/URL]';
					}

					if ($this->_streamFile)
					{
						@unlink($this->_streamFile);
						$this->_streamFile = null;
					}
				}
				else
				{
					$tag = '[URL]' . $link['url'] . '[/URL]';
				}
			}
		}
		else
		{
			$tag = '[URL="' . $link['url'] . '"]' . $link['linkText'] . '[/URL]';
		}

		return $tag . $link['suffixText'];
	}

	protected function _getUrlTitle($url)
	{
		$requestUrl = $url;
		$requestUrl = preg_replace('/#.*$/', '', $requestUrl);
		if (preg_match_all('/[^A-Za-z0-9._~:\/?#\[\]@!$&\'()*+,;=%-]/', $requestUrl, $matches))
		{
			foreach ($matches[0] AS $match)
			{
				$requestUrl = str_replace($match[0], '%' . strtoupper(dechex(ord($match[0]))), $requestUrl);
			}
		}
		$requestUrl = preg_replace('/%(?![a-fA-F0-9]{2})/', '%25', $requestUrl);

		// ensure the URL is valid and isn't trying to load something locally
		$parts = @parse_url($requestUrl);
		if (!$parts
			|| empty($parts['scheme'])
			|| !preg_match('/^https?$/i', $parts['scheme'])
			|| empty($parts['host'])
			|| preg_match('#^(127\.|192\.168\.|10\.|172\.(1[6789]|2|3[01])|169\.254\.|0\.0\.0\.0)#', $parts['host'])
			|| preg_match('#^localhost(\.localdomain)?$#i', $parts['host'])
		)
		{
			return false;
		}

		$urlHash = md5($url);

		XenForo_HttpStream::register();
		XenForo_HttpStream::setMaxSize(50 * 1024); // 50KB

		$streamUri = 'xf-http://' . $urlHash . '-' . uniqid();
		$this->_streamFile = XenForo_HttpStream::getTempFile($streamUri);

		try
		{
			$response = XenForo_Helper_Http::getUntrustedClient($requestUrl, array(
				'output_stream' => $streamUri,
				'timeout' => 3,
				'httpversion' => Zend_Http_Client::HTTP_0
			))->setHeaders('Accept-encoding', 'identity')
				->setHeaders('Accept', 'text/html,*/*;q=0.8')
				->request('GET');
			if (!$response->isSuccessful())
			{
				return false;
			}

			$headers = $response->getHeaders();
			if (!$headers)
			{
				return false;
			}

			$charset = false;

			if (isset($headers['Content-type']))
			{
				$parts = explode(
					';',
					is_array($headers['Content-type']) ? end($headers['Content-type']) : $headers['Content-type'],
					2
				);

				$type = trim($parts[0]);
				if ($type != 'text/html')
				{
					return false;
				}

				if (isset($parts[1]) && preg_match('/charset=([-a-z0-9_]+)/i', trim($parts[1]), $match))
				{
					$charset = $match[1];
				}
			}

			try
			{
				$body = $response->getBody();
			}
			catch (Exception $e)
			{
				// some servers may ignore our request for no gzip and this can fail
				$body = '';
			}

			$title = '';
			if (preg_match('#<meta[^>]+property="(og:|twitter:)title"[^>]*content="([^">]+)"#siU', $body, $match))
			{
				$title = isset($match[2]) ? $match[2] : '';
			}

			if (!$title && preg_match('#<title[^>]*>(.*)</title>#siU', $body, $match))
			{
				$title = $match[1];
			}

			if (!$title)
			{
				return false;
			}

			if (!$charset)
			{
				preg_match('/charset=([^;"\\s]+|"[^;"]+")/i', $body, $contentTypeMatch);

				if (isset($contentTypeMatch[1]))
				{
					$charset = trim($contentTypeMatch[1], " \t\n\r\0\x0B\"");
				}

				if (!$charset)
				{
					$charset = 'windows-1252';
				}
			}

			$title = XenForo_Input::cleanString($this->_toUtf8($title, $charset, true));
			if (defined('ENT_HTML5'))
			{
				$title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
			}
			else
			{
				$title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
			}
			$title = utf8_unhtml($title);
			$title = str_replace("\n", ' ', trim($title));

			if (!strlen($title))
			{
				return false;
			}

			$formatter = $this->_getBbCodeFilterer();
			$parser = $this->_getBbCodeParser();
			$parser->render($title);

			if ($formatter->getSmilieTally() || $formatter->getTotalTagTally())
			{
				$title = "[PLAIN]{$title}[/PLAIN]";
			}

			return $title;
		}
		catch (Zend_Http_Client_Exception $e) { }
		catch (Zend_Uri_Exception $e) { }
		catch (Exception $e)
		{
			XenForo_Error::logException($e, false, "Error linking URL '$url': ");
		}

		return false;
	}

	/**
	 * Convert the given text to valid UTF-8
	 *
	 * @param string $string
	 * @param string $charset
	 * @param boolean $entities Convert &lt; (and other) entities back to < characters
	 *
	 * @return string
	 */
	protected function _toUtf8($string, $charset, $entities = null)
	{
		// note: assumes charset is ascii compatible
		if (preg_match('/[\x80-\xff]/', $string))
		{
			$newString = false;
			if (function_exists('iconv'))
			{
				$newString = @iconv($charset, 'utf-8//IGNORE', $string);
			}
			if (!$newString && function_exists('mb_convert_encoding'))
			{
				$newString = @mb_convert_encoding($string, 'utf-8', $charset);
			}
			$string = ($newString ? $newString : preg_replace('/[\x80-\xff]/', '', $string));
		}

		$string = utf8_unhtml($string, $entities);
		$string = preg_replace('/[\xF0-\xF7].../', '', $string);
		$string = preg_replace('/[\xF8-\xFB]..../', '', $string);
		return $string;
	}

	/**
	 * @return XenForo_BbCode_Formatter_BbCode_Filter
	 */
	protected function _getBbCodeFilterer()
	{
		if (!$this->_filterer)
		{
			$this->_filterer = XenForo_BbCode_Formatter_Base::create('XenForo_BbCode_Formatter_BbCode_Filter');
		}

		return $this->_filterer;
	}

	/**
	 * @return XenForo_BbCode_Parser
	 */
	protected function _getBbCodeParser()
	{
		if (!$this->_parser)
		{
			$this->_parser = XenForo_BbCode_Parser::create($this->_getBbCodeFilterer());
		}

		return $this->_parser;
	}
}