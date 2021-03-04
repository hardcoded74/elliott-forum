<?php

namespace ThemeHouse\UIX\Service\Style;

use ThemeHouse\UIX\Util\Ftp;
use XF\App;
use XF\Service\AbstractService;
use XF\Util\File;

/**
 * Class Mover
 * @package ThemeHouse\UIX\Service\Style
 */
class Mover extends AbstractService
{
    /**
     * @var bool|string
     */
    protected $source;
    /**
     * @var
     */
    protected $destination;
    /**
     * @var Ftp
     */
    protected $ftpConnection;

    /**
     * Mover constructor.
     * @param App $app
     * @param $source
     * @param $destination
     * @param Ftp|null $ftpConnection
     * @throws \Exception
     */
    public function __construct(App $app, $source, $destination, Ftp $ftpConnection = null)
    {
        parent::__construct($app);

        $newSource = $this->validateSource($source);

        if (!$newSource) {
            throw new \Exception('Error locating product files in ' . $source);
        }

        $this->source = $newSource;
        $this->destination = $destination;
        $this->ftpConnection = $ftpConnection;
    }

    /**
     * @param $source
     * @return bool|string
     */
    protected function validateSource($source)
    {
        $validDirectories = [
            'upload',
            'uploads',
            'Upload',
            'Uploads',
        ];

        $directories = array_filter(glob($source . DIRECTORY_SEPARATOR . '*'), 'is_dir');

        foreach ($directories as $directory) {
            $parts = explode(DIRECTORY_SEPARATOR, $directory);
            $subDir = end($parts);

            if (in_array($subDir, $validDirectories)) {
                return $directory . DIRECTORY_SEPARATOR;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function move()
    {
        return $this->rmove($this->source, $this->destination);
    }

    /**
     * @param $dirSource
     * @param $dirDest
     * @return array
     */
    protected function rmove($dirSource, $dirDest)
    {
        if (is_dir($dirSource)) {
            $dirHandle = opendir($dirSource);
        }

        $dirName = substr($dirSource, strrpos($dirSource, DIRECTORY_SEPARATOR) + 1);
        if (!is_dir($dirDest . DIRECTORY_SEPARATOR . $dirName) && !file_exists($dirDest . DIRECTORY_SEPARATOR . $dirName)) {
            $this->mkdir($dirDest . DIRECTORY_SEPARATOR . $dirName);
        }

        $break = false;
        /** @noinspection PhpUndefinedVariableInspection */
        while ($file = readdir($dirHandle)) {
            if ($file == 'Thumbs.db' || $file == '.DS_Store') {
                $this->rmfile($file);
                continue;
            }

            if ($file != '.' && $file != '..') {
                if (!is_dir($dirSource . DIRECTORY_SEPARATOR . $file)) {
                    if (file_exists($dirDest . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $file)) {
                        $this->rmfile($dirDest . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $file);
                    }
                    $hasCopied = $this->copy($dirSource . DIRECTORY_SEPARATOR . $file,
                        $dirDest . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $file);
                    if (!$hasCopied) {
                        $break = true;
                        break;
                    }
                    $this->rmfile($dirSource . DIRECTORY_SEPARATOR . $file);
                } else {
                    $this->rmove($dirSource . DIRECTORY_SEPARATOR . $file, $dirDest . DIRECTORY_SEPARATOR . $dirName);
                }
            }
        }

        @closedir($dirHandle);
        @rmdir($dirSource);

        if ($break) {
            return [
                'status' => 'error',
                'error' => 'Error copying style files, please check your file permissions',
            ];
        }

        return [
            'status' => 'success',
        ];
    }

    /**
     * @param $dirName
     */
    protected function mkdir($dirName)
    {
        if ($this->ftpConnection) {
            $this->ftpConnection->mkdir($dirName);
        } else {
            File::createDirectory($dirName);
        }
    }

    /**
     * @param $fileName
     */
    protected function rmfile($fileName)
    {
//        @unlink($fileName);
    }

    /**
     * @param $source
     * @param $destination
     * @return bool
     */
    protected function copy($source, $destination)
    {
        $source = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $source);
        $destination = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $destination);
        $source = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $source);
        $destination = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $destination);

        try {
            if ($this->ftpConnection) {
                $this->ftpConnection->move($source, $destination);
            } else {
                copy($source, $destination);
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
