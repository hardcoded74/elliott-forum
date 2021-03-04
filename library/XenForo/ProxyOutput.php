<?php

/**
 * This class is entirely self sufficient. It handles parsing the input,
 * getting the data, rendering it, and manipulating HTTP headers.
 *
 * @package XenForo_ProxyOutput
 */
class XenForo_ProxyOutput
{
	/**
	 * @var Zend_Controller_Response_Http
	 */
	protected $_response = null;

	/**
	 * @var XenForo_Dependencies_Abstract
	 */
	protected $_dependencies;

	protected $_mode = null;

	protected $_url = null;

	protected $_hash = null;

	protected $_json = false;

	protected $_referrer = null;

	/**
	 * Constructor.
	 *
	 * @param array $input Array of input (intended to normally be $_REQUEST)
	 * @param XenForo_Dependencies_Abstract $dependencies
	 */
	public function __construct(array $input, XenForo_Dependencies_Abstract $dependencies)
	{
		$this->_dependencies = $dependencies;
		$this->_response = new Zend_Controller_Response_Http();

		if (!empty($input['image']) && is_scalar($input['image']))
		{
			$this->_mode = 'image';
			$this->_url = trim(strval($input['image']));
		}

		if (!empty($input['link']) && is_scalar($input['link']))
		{
			$this->_mode = 'link';
			$this->_url = trim(strval($input['link']));
		}

		if (!empty($input['hash']) && is_scalar($input['hash']))
		{
			$this->_hash = trim(strval($input['hash']));
		}

		$this->_json = (!empty($input['_xfResponseType']) && $input['_xfResponseType'] === 'json');

		if ($this->_json
			&& $this->_mode == 'link'
			&& !empty($input['_xfRequestUri'])
			&& is_scalar($input['_xfRequestUri'])
		)
		{
			// only use this if we're using JSON output (in a handler that supports it)
			$this->_referrer = XenForo_Link::convertUriToAbsoluteUri($input['_xfRequestUri'], true);
		}

		if (!$this->_referrer && !empty($_SERVER['HTTP_REFERER']))
		{
			$this->_referrer = $_SERVER['HTTP_REFERER'];
		}

		if ($this->_referrer && !preg_match('#^https?://#i', $this->_referrer))
		{
			$this->_referrer = null;
		}
	}

	public function isValidRequest(&$error = null)
	{
		$error = false;

		if (!$this->_url || !preg_match('#^https?://#i', $this->_url))
		{
			$error = 'invalid_url';
			return false;
		}

		$urlParts = @parse_url($this->_url);
		if (!$urlParts || empty($urlParts['host']))
		{
			$error = 'invalid_url';
			return false;
		}

		$hash = hash_hmac('md5', $this->_url,
			XenForo_Application::getConfig()->globalSalt . XenForo_Application::getOptions()->imageLinkProxyKey
		);
		if ($hash !== $this->_hash)
		{
			$error = 'invalid_hash';
			return false;
		}

		if (!$this->isValidReferrer())
		{
			$error = 'invalid_referrer';
			return false;
		}

		return true;
	}

	/**
	 * Attempts to determine whether the request is referred from the expected host.
	 * By default, only supports the current host.
	 *
	 * @return boolean
	 */
	public function isValidReferrer()
	{
		if ($this->_referrer)
		{
			$refererParts = @parse_url($this->_referrer);
			if ($refererParts && !empty($refererParts['host']))
			{
				$paths = XenForo_Application::get('requestPaths');
				$requestParts = @parse_url($paths['fullUri']);

				if ($requestParts && !empty($requestParts['host']))
				{
					if ($refererParts['host'] != $requestParts['host'])
					{
						// referer is not the same as request host
						return false;
					}
				}
			}
		}

		// either we have the same host and referer, or we just don't know...
		return true;
	}

	public function output()
	{
		$isValidRequest = $this->isValidRequest($error);

		switch ($this->_mode)
		{
			case 'image':
				$this->_outputImage($error);
				break;

			case 'link':
				$this->_outputLinkRedirect($error);
				break;

			default:
				header('Content-Type: text/html; charset=utf-8', true, 500);
				die('Unknown proxy mode');
		}
	}

	/**
	 * Tells the browser that the data should be downloaded (rather than displayed)
	 * using the specified file name.
	 *
	 * @param string $fileName
	 * @param boolean $inline True if the attachment should be shown inline - use with caution!
	 */
	protected function _setDownloadFileName($fileName, $inline = false)
	{
		$type = ($inline ? 'inline' : 'attachment');

		$this->_response->setHeader('Content-Disposition',
			$type . '; filename="' . str_replace('"', '', $fileName) . '"',
			true
		);
	}

	protected function _isLocalHost($host)
	{
		return (
			preg_match('#^(127\.|192\.168\.|10\.|172\.(1[6789]|2|3[01])|169\.254\.|0\.0\.0\.0)#', $host)
			|| preg_match('#^localhost(\.localdomain)?$#i', $host)
		);
	}

	/**
	 * Intercept a request for a cached image from the proxy and output it
	 *
	 * @param string|bool $error If non-false, an error that occurred when validating the request
	 */
	protected function _outputImage($error)
	{
		if (empty(XenForo_Application::getOptions()->imageLinkProxy['images']))
		{
			$error = 'disabled';
		}

		/* @var $proxyModel XenForo_Model_ImageProxy */
		$proxyModel = XenForo_Model::create('XenForo_Model_ImageProxy');

		$image = false;

		if (!$error)
		{
			if (!XenForo_Helper_Http::isRequestableUntrustedUrl($this->_url))
			{
				$error = 'unrequestable_url';
			}
		}

		if (!$error)
		{
			$image = $proxyModel->getImage($this->_url);
			if ($image)
			{
				$image = $proxyModel->prepareImage($image);
				if ($image['use_file'])
				{
					$proxyModel->logImageView($image);
					if (!empty(XenForo_Application::getOptions()->imageLinkProxyReferrer['enabled'])
						&& $this->_referrer
					)
					{
						$proxyModel->logImageReferrer($image['image_id'], $this->_referrer);
					}

					$eTag = !empty($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : null;
					$eTagExpected =  '"' . $image['fetch_date'] . '"';
					if ($eTag && ($eTag == $eTagExpected || $eTag == "W/$eTagExpected"))
					{
						$this->_response->setHttpResponseCode(304);
						$this->_response->clearHeader('Last-Modified');
						$this->_response->sendHeaders();
						return;
					}
				}
				else
				{
					$image = false;
					$error = 'retrieve_failed';
				}
			}
		}

		if (!$image)
		{
			$image = $proxyModel->getPlaceHolderImage();
		}

		$imageTypes = array(
			'image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png'
		);

		if (in_array($image['mime_type'], $imageTypes))
		{
			$this->_response->setHeader('Content-type', $image['mime_type'], true);
			$this->_setDownloadFileName($image['file_name'], true);
		}
		else
		{
			$this->_response->setHeader('Content-type', 'application/octet-stream', true);
			$this->_setDownloadFileName($image['file_name']);
		}

		if (!$error)
		{
			$this->_response->setHeader('ETag', '"' . $image['fetch_date'] . '"', true);
		}

		if ($image['file_size'])
		{
			$this->_response->setHeader('Content-Length', $image['file_size'], true);
		}
		$this->_response->setHeader('X-Content-Type-Options', 'nosniff');

		if ($error)
		{
			$this->_response->setHeader('X-Proxy-Error', $error);
		}

		$this->_response->sendHeaders();

		$imageData = new XenForo_FileOutput($image['file_path']);
		$imageData->output();
	}

	/**
	 * Intercept a request for a link redirect
	 *
	 * @param string|bool $error If non-false, an error that occurred when validating the request
	 */
	protected function _outputLinkRedirect($error)
	{
		if ($error === 'invalid_url')
		{
			header('Content-Type: text/html; utf-8', true, 500);
			die('Invalid URL');
		}

		if (empty(XenForo_Application::getOptions()->imageLinkProxy['links']))
		{
			$error = 'disabled';
		}

		if (!$error)
		{
			/* @var $proxyModel XenForo_Model_LinkProxy */
			$proxyModel = XenForo_Model::create('XenForo_Model_LinkProxy');
			$linkId = $proxyModel->logVisit($this->_url);
			if ($linkId
				&& !empty(XenForo_Application::getOptions()->imageLinkProxyReferrer['enabled'])
				&& $this->_referrer
			)
			{
				$proxyModel->logLinkReferrer($linkId, $this->_referrer);
			}

			if ($this->_json)
			{
				header('Content-type: application/json; charset=UTF-8');
				echo json_encode(array('logged' => true));
				exit;
			}

			// this used to redirect, but we don't take this approach any longer, so we display an intersitial
		}

		$request = new Zend_Controller_Request_Http();

		XenForo_Session::startPublicSession($request);

		$this->_dependencies->preRenderView();

		if (!preg_match('#^https?://#i', $this->_url))
		{
			throw new Exception('Unsafe proxy URL: ' . $this->_url);
		}

		$printable = urldecode($this->_url);
		if (!preg_match('/./u', $printable))
		{
			$printable = $this->_url;
		}

		$renderer = new XenForo_ViewRenderer_HtmlPublic($this->_dependencies, $this->_response, $request);
		$contents = $renderer->createTemplateObject('link_redirect', array(
			'url' => $this->_url,
			'printable' => $printable,
			'parts' => parse_url($this->_url)
		));
		$containerParams = $this->_dependencies->getEffectiveContainerParams(array(), $request);

		$output = $renderer->renderContainer($contents, $containerParams);

		$extraHeaders = XenForo_Application::gzipContentIfSupported($output);
		foreach ($extraHeaders AS $extraHeader)
		{
			$this->_response->setHeader($extraHeader[0], $extraHeader[1], $extraHeader[2]);
		}

		$this->_response->setHeader('X-Proxy-Error', $error);

		$this->_response->sendHeaders();
		echo $output;
	}

	/**
	 * Static helper to execute a full request for proxy output
	 */
	public static function run()
	{
		$dependencies = new XenForo_Dependencies_Public();
		$dependencies->preLoadData();

		$class = XenForo_Application::resolveDynamicClass(__CLASS__);

		$proxyOutput = new $class($_REQUEST, $dependencies);
		$proxyOutput->output();
	}
}