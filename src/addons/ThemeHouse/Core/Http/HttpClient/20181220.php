<?php

namespace ThemeHouse\Core\Http;

class HttpClient
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function simpleGet($uri, array $options = [])
    {
        return $this->client->get($uri, $options);
    }

    public function simplePost($uri, array $params = [], array $options = [])
    {
        if (\XF::$versionId < 2010000) {
            $options['body'] = $params;
        } else {
            $options['form_params'] = $params;
        }

        return $this->client->post($uri, $options);
    }

    public function simpleDelete($uri, array $options = [])
    {
        return $this->client->delete($uri, $options);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    public function __construct(array $options = [])
    {
        $this->client = \XF::app()->http()->createClient($options);
    }
}