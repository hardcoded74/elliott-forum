<?php

namespace ThemeHouse\UIX\Service\Style;

use XF\Service\AbstractService;
use XF\Util\File;

/**
 * Class Extractor
 * @package ThemeHouse\UIX\Service\Style
 */
class Extractor extends AbstractService
{
    /**
     * @param $filePath
     * @param $directory
     * @return array
     */
    public function extract($filePath, $directory)
    {
        $childStyles = [];

        if (!is_dir($directory)) {
            try {
                File::createDirectory($directory);
            } catch (\Exception $e) {
                return $this->responseError('Error extracting style zip, please check your file permissions');
            }
        }

        try {
            $zip = new \ZipArchive();
        } catch (\Exception $e) {
            return $this->responseError('ZipArchive extension missing. Please contact your host or sysadmin to resolve this issue.');
        }

        $zip->open($filePath);
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $fileName));

            $fileParts = explode(DIRECTORY_SEPARATOR, $fileName);
            if (strtolower($fileParts[0]) === 'upload' || strpos($fileParts[0], '.xml') !== false) {
                $zip->extractTo($directory, array($zip->getNameIndex($i)));
                $realFile = $directory . DIRECTORY_SEPARATOR . $zip->getNameIndex($i);
                File::makeWritableByFtpUser($realFile);
            }

            if (strtolower($fileParts[0]) === 'child_xmls' && !empty($fileParts[1]) && strpos($fileParts[1],
                    '.xml') !== false) {
                $zip->extractTo($directory, array($zip->getNameIndex($i)));
                $realFile = $directory . DIRECTORY_SEPARATOR . $zip->getNameIndex($i);
                File::makeWritableByFtpUser($realFile);
                $childStyles[] = $fileParts[1];
            }
        }
        $zip->close();
        unlink($filePath);

        return [
            'status' => 'success',
            'path' => $directory,
            'childStyles' => $childStyles,
        ];
    }

    /**
     * @param bool $message
     * @param string $errorCode
     * @return array
     */
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
}
