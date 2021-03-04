<?php

namespace ThemeHouse\UIX\Service;

class UpdateCheck extends ApiRequest
{
    protected $productId, $currentVersion;

    public function __construct(\XF\App $app, $productId, $currentVersion)
    {
        parent::__construct($app);

        $this->productId = $productId;
        $this->currentVersion = $currentVersion;
    }

    public function check()
    {
        $apiResponse = $this->get('products/'. $this->productId);
        if ($apiResponse['status'] === 'error') {
            return false;
        } else {
            $versions = $apiResponse['payload']['versions'];
            $latestVersion = end($versions);
            $latestVersion = $latestVersion['version'];

            if ($this->compareVersions($this->currentVersion, $latestVersion)) {
                return [
                    'requires_update' => true,
                    'prerelease' => false,
                ];
            }

            if ($this->compareVersions($this->currentVersion, $latestVersion, '>')) {
                return [
                    'requires_update' => false,
                    'prerelease' => true,
                ];
            }

            return [
                'requires_update' => false,
                'prerelease' => false,
            ];
        }
    }

    protected function compareVersions($currentVersion, $latestVersion, $operator = '<')
    {
        return version_compare($this->standardizeVersionNumber($currentVersion), $this->standardizeVersionNumber($latestVersion), $operator);
    }

    protected function standardizeVersionNumber($versionNumber)
    {
        $versionNumber = str_replace(' ', '', $versionNumber);
        $versionNumber = str_ireplace('Alpha', 'a', $versionNumber);
        $versionNumber = str_ireplace('Beta', 'b', $versionNumber);
        $versionNumber = str_ireplace('ReleaseCandidate', 'rc', $versionNumber);
        $versionNumber = str_ireplace('PatchLevel', 'pl', $versionNumber);
        return $versionNumber;
    }
}