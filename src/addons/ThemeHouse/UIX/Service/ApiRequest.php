<?php

namespace ThemeHouse\UIX\Service;

use GuzzleHttp\Message\Request;
use XF\Util\File;

class ApiRequest extends \XF\Service\AbstractService
{

    protected $baseUrl = 'https://pinto.audent.io/api/';

    public function __construct(\XF\App $app)
    {
        parent::__construct($app);

        $baseUrl = \XF::config('uix_baseUrl');
        if (!empty($baseUrl)) {
            $this->baseUrl = $baseUrl;
        }

        $this->app = $app;
    }

    public function get($endpoint, Array $data = [])
    {
        $url = $this->baseUrl . $endpoint;
        $options = $this->app->options();

        $headers = [];
        $headers['ApiKey'] = $options->th_apiKey_uix;
        $headers['SiteUrl'] = $options->boardUrl;
        $headers['HttpHost'] = $_SERVER['HTTP_HOST'];
        $headers['Software'] = 'XenForo';
        $headers['SoftwareVersion'] = \XF::$versionId;

        $client = $this->app->http()->client();

        try {
            $response = $client->get($url, [
                'headers' => $headers,
            ]);
            $body = (string) $response->getBody();
            if (!$body || !$this->_isJson($body)) {
                return $this->responseError('Timeout connecting to the ThemeHouse API. Please try again later');
            }
            $response = json_decode($body, true);
        } catch(\GuzzleHttp\Exception\RequestException $e) {
            return $this->responseError($e->getMessage());
        }

        if ($response['status'] === 'error') {
            return $this->responseError($response['error'], $response['error_code']);
        }

        return $response;
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

        \XF\Util\File::makeWritableByFtpUser($filePath);

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