<?php

namespace ThemeHouse\UIX\Service\Style;

use XF\Service\AbstractService;

/**
 * Class Fetcher
 * @package ThemeHouse\UIX\Service\Style
 */
class Fetcher extends AbstractService
{
    /**
     * @var string
     */
    protected $productListingUrl = 'product-categories/8';
    /**
     * @var string
     */
    protected $productUrl = 'products/{id}';

    /**
     * @param int $productId
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface
     */
    public function fetch($productId = 0)
    {
        /** @var \ThemeHouse\Core\Service\ApiRequest $apiService */
        $apiService = $this->service('ThemeHouse\Core:ApiRequest');

        $url = $this->productListingUrl;

        if ($productId) {
            $url = str_replace('{id}', $productId, $this->productUrl);
        }

        $product = $apiService->get($url);

        if ($productId) {
            $product['payload']['versions'] = array_reverse($product['payload']['versions']);
        }

        return $product;
    }
}
