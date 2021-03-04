<?php

/**
 * Implementation for new version of ReCAPTCHA (No CAPTCHA).
 *
 * @package XenForo_Captcha
 */
class XenForo_Captcha_NoCaptcha extends XenForo_Captcha_Abstract
{
	/**
	 * reCAPTCHA site key
	 *
	 * @var null|string
	 */
	protected $_siteKey = null;

	/**
	 * reCAPTCHA secret key
	 *
	 * @var null|string
	 */
	protected $_secretKey = null;


	/**
	 * Constructor.
	 *
	 * @param null $siteKey
	 * @param null $secretKey
	 */
	public function __construct($siteKey = null, $secretKey = null)
	{
		if (!$siteKey || !$secretKey)
		{
			$extraKeys = XenForo_Application::getOptions()->extraCaptchaKeys;
			if (!empty($extraKeys['reCaptchaSiteKey']) && !empty($extraKeys['reCaptchaSecretKey']))
			{
				$this->_siteKey = $extraKeys['reCaptchaSiteKey'];
				$this->_secretKey = $extraKeys['reCaptchaSecretKey'];
			}
		}
		else
		{
			$this->_siteKey = $siteKey;
			$this->_secretKey = $secretKey;
		}
	}

	/**
	 * Determines if CAPTCHA is valid (passed).
	 *
	 * @see XenForo_Captcha_Abstract::isValid()
	 */
	public function isValid(array $input)
	{
		if (!$this->_siteKey || !$this->_secretKey)
		{
			return true; // if not configured, always pass
		}

		if (empty($input['g-recaptcha-response']))
		{
			return false;
		}

		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

		try
		{
			$client = XenForo_Helper_Http::getClient('https://www.google.com/recaptcha/api/siteverify');
			$client->setParameterPost(array(
				'secret' => $this->_secretKey,
				'response' => $input['g-recaptcha-response'],
				'remoteip' => $ip
			));
			$response = json_decode($client->request('POST')->getBody(), true);

			$requestPaths = XenForo_Application::getRequestPaths(new Zend_Controller_Request_Http());
			if (isset($response['success']) && isset($response['hostname']) && $response['hostname'] == $requestPaths['host'])
			{
				return $response['success'];
			}

			return false;
		}
		catch (Zend_Http_Client_Adapter_Exception $e)
		{
			// this is an exception with the underlying request, so let it go through
			XenForo_Error::logException($e, false, "ReCAPTCHA (No CAPTCHA) connection error: ");
			return true;
		}
	}

	/**
	 * Renders the CAPTCHA template.
	 *
	 * @see XenForo_Captcha_Abstract::renderInternal()
	 */
	public function renderInternal(XenForo_View $view)
	{
		if (!$this->_siteKey)
		{
			return '';
		}

		$template = $view->createTemplateObject('captcha_nocaptcha', array(
			'siteKey' => $this->_siteKey
		));
		return $template;
	}
}