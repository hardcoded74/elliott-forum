<?php

namespace ThemeHouse\UIX\Service\Style;

/**
 * Class UpdateCheck
 * @package ThemeHouse\UIX\Service\Style
 */
class UpdateCheck extends \ThemeHouse\Core\Service\UpdateCheck
{
    /**
     * @return array|bool
     */
    public function check()
    {
        $apiResponse = $this->get('products/' . $this->productId);
        if ($apiResponse['status'] === 'error') {
            return false;
        } else {
            $versions = $apiResponse['payload']['versions'];

            preg_match('/^\d.\d.\d/', \XF::$version, $match);
            $xfVersionMinorRelease = $match[0];

            $minorReleaseVersions = [];
            foreach ($versions as $version) {
                preg_match('/^\d.\d.\d/', $version['version'], $match);
                if ($match[0] === $xfVersionMinorRelease) {
                    $minorReleaseVersions[] = $version['version'];
                }
            }
            $latestVersion = end($minorReleaseVersions);

            if (!$latestVersion) {
                if ($this->currentVersion) {
                    preg_match('/^\d.\d.\d/', $this->currentVersion, $match);
                    if ($match[0] === $xfVersionMinorRelease) {
                        return [
                            'requires_update' => false,
                            'prerelease' => true,
                            'no_version_available' => false,
                        ];
                    }
                }
                return [
                    'requires_update' => false,
                    'prerelease' => false,
                    'no_version_available' => true,
                ];
            }

            if ($this->compareVersions($this->currentVersion, $latestVersion)) {
                return [
                    'requires_update' => true,
                    'prerelease' => false,
                    'no_version_available' => false,
                ];
            }

            if ($this->compareVersions($this->currentVersion, $latestVersion, '>')) {
                return [
                    'requires_update' => false,
                    'prerelease' => true,
                    'no_version_available' => false,
                ];
            }

            return [
                'requires_update' => false,
                'prerelease' => false,
                'no_version_available' => false,
            ];
        }
    }
}
