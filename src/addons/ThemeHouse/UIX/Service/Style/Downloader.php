<?php

namespace ThemeHouse\UIX\Service\Style;

use XF\Service\AbstractService;
use XF\Util\File;

/**
 * Class Downloader
 * @package ThemeHouse\UIX\Service\Style
 */
class Downloader extends AbstractService
{
    /**
     * @var string
     */
    protected $apiUrl = 'products/{product_id}/download/{version_id}';

    /**
     * @param $productId
     * @param $versionId
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface
     */
    public function download($productId, $versionId)
    {
        $downloadResponse = $this->getVersion($productId, $versionId);

        if ($downloadResponse['status'] === 'error') {
            return $downloadResponse;
        }

        $version = $downloadResponse['payload']['version'];

        return $this->downloadStyle($version);
    }

    /**
     * @param $productId
     * @param $versionId
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface
     */
    protected function getVersion($productId, $versionId)
    {
        /** @var \ThemeHouse\Core\Service\ApiRequest $apiService */
        $apiService = $this->service('ThemeHouse\Core:ApiRequest');

        $url = str_replace('{product_id}', $productId, str_replace('{version_id}', $versionId, $this->apiUrl));
        return $apiService->get($url);
    }

    /**
     * @param $version
     * @return array
     */
    protected function downloadStyle($version)
    {
        /** @var \ThemeHouse\Core\Service\ApiRequest $apiService */
        $apiService = $this->service('ThemeHouse\Core:ApiRequest');

        $tempPath = File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time . '.zip';

        $downloadResponse = $apiService->download($version['download_url'], $tempPath);

        if ($downloadResponse['status'] === 'error') {
            return [
                'status' => 'error',
                'error' => 'Unable to download style zip',
            ];
        }

        return $downloadResponse;
    }
}
