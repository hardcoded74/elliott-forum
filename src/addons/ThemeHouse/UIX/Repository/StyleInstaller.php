<?php

namespace ThemeHouse\UIX\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class StyleInstaller
 * @package ThemeHouse\UIX\Repository
 */
class StyleInstaller extends Repository
{
    /**
     * @param $baseDir
     * @param array $xmlFiles
     * @return array
     */
    public function getStyleNamesFromXmls($baseDir, array $xmlFiles)
    {
        $styleNames = [];

        foreach ($xmlFiles as $fileName) {
            $filePath = $baseDir . DIRECTORY_SEPARATOR . 'child_xmls' . DIRECTORY_SEPARATOR . $fileName;
            $xml = simplexml_load_file($filePath);

            $styleNames[$fileName] = strval($xml['title']);
        }

        return $styleNames;
    }
}
