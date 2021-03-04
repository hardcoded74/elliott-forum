<?php

namespace ThemeHouse\Core;

class AutoLoad
{
    public static function autoloadComposerPackages($addOnId)
    {
        $composerDir = \XF::getAddOnDirectory() . '/' . $addOnId .'/vendor/composer';
        $app = \XF::app();

        self::autoloadNamespaces($composerDir, $app);
        self::autoloadPsr4($composerDir, $app);
        self::autoloadClassmap($composerDir, $app);
        self::autoloadFiles($composerDir, $app);
    }

    protected static function autoloadNamespaces($composerDir, \XF\App $app, $prepend = false)
    {
        $namespaces = $composerDir . DIRECTORY_SEPARATOR . 'autoload_namespaces.php';

        if (file_exists($namespaces))  {
            $map = require $namespaces;

            foreach ($map as $namespace => $path) {
                \XF::$autoLoader->add($namespace, $path, $prepend);
            }
        }
    }

    protected static function autoloadPsr4($composerDir, \XF\App $app, $prepend = false)
    {
        $psr4 = $composerDir . DIRECTORY_SEPARATOR . 'autoload_psr4.php';

        if (file_exists($psr4)) {
            $map = require $psr4;

            foreach ($map as $namespace => $path) {
                \XF::$autoLoader->addPsr4($namespace, $path, $prepend);
            }
        }
    }

    protected static function autoloadClassmap($composerDir, \XF\App $app)
    {
        $classmap = $composerDir . DIRECTORY_SEPARATOR . 'autoload_classmap.php';

        if (file_exists($classmap)) {
            $map = require $classmap;

            if ($map) {
                \XF::$autoLoader->addClassMap($map);
            }
        }
    }

    protected static function autoloadFiles($composerDir, \XF\App $app)
    {
        $files = $composerDir . DIRECTORY_SEPARATOR . 'autoload_files.php';

        if (file_exists($files)) {
            $includeFiles = require $files;

            foreach ($includeFiles as $fileIdentifier => $file) {
                if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
                    require $file;

                    $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
                }
            }
        }
    }
}