<?php

namespace ThemeHouse\Core\Service;

use GuzzleHttp\Exception\RequestException;
use XF\App;
use XF\Service\AbstractService;
use XF\Util\File;

class ApiRequest extends AbstractService
{
    protected $baseUrl = 'https://pinto.audent.io/api/';

    protected $apiKey;

    public function __construct(App $app, $apiKey = null)
    {
        parent::__construct($app);

        $baseUrl = \XF::config('uix_baseUrl');
        if (!empty($baseUrl)) {
            $this->baseUrl = $baseUrl;
        }

        $this->app = $app;
        $this->apiKey = $apiKey;
    }

    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function get($endpoint, Array $data = [])
    {
        $url = $this->baseUrl . $endpoint;
        $options = $this->app->options();

        $headers = [];
        $headers['ApiKey'] = isset($this->apiKey) && strlen($this->apiKey) ? $this->apiKey : self::getDefaultApiKey();
        $headers['SiteUrl'] = $options->boardUrl;
        if (!empty($_SERVER['HTTP_HOST'])) {
            $headers['HttpHost'] = $_SERVER['HTTP_HOST'];
        } else {
            $headers['HttpHost'] = null;
        }
        $headers['Software'] = 'XenForo';
        $headers['SoftwareVersion'] = \XF::$versionId;

        $client = $this->app->http()->client();

        try {
            $response = $client->get($url, [
                'headers' => $headers,
            ]);
            $body = (string)$response->getBody();
            if (!$body || !$this->_isJson($body)) {
                return $this->responseError('Timeout connecting to the ThemeHouse API. Please try again later');
            }
            $response = json_decode($body, true);
        } catch (RequestException $e) {
            return $this->responseError($e->getMessage());
        }

        if ($response['status'] === 'error') {
            return $this->responseError($response['error'], $response['error_code']);
        }

        return $response;
    }

    protected static function getDefaultApiKey()
    {
        $apiKey = \XF::options()->th_apiKey_uix;

        $config = \XF::config('uix');
        if (isset($config['apiKey'])) {
            $apiKey = $config['apiKey'];
        }

        return $apiKey;
    }

    public function download($url, $filePath)
    {
        $ch = @curl_init($url);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $return = @curl_exec($ch);
        if (!$return) {
            return $this->responseError('Timeout connecting to the ThemeHouse API. Please try again later');
        }

        try {
            $fh = fopen($filePath, 'w+');
            fwrite($fh, $return);
            fclose($fh);
        } catch (\Exception $e) {
            return $this->responseError('An error has occurred while downloading the product\'s zip file. Please check your filesystem permissions.');
        }

        File::makeWritableByFtpUser($filePath);

        return [
            'status' => 'success',
            'path' => $filePath,
        ];
    }

    protected function responseError($message = false, $errorCode = 'SERVER_ERR')
    {
        if (!$message) {
            $message = 'An unknown error has occurred.';
        }

        return [
            'status' => 'error',
            'error_code' => $errorCode,
            'error' => $message,
        ];
    }

    protected function _isJson($string)
    {
        $array = json_decode($string);
        if (!$array) {
            return false;
        }

        return true;
    }
}