<?php

namespace ThemeHouse\UIX\XF\Admin\Controller;

use ThemeHouse\Core\Service\UpdateCheck;
use ThemeHouse\UIX\Repository\StyleInstaller;
use ThemeHouse\UIX\Util\UIX;
use XF\Mvc\ParameterBag;
use XF\Repository\AddOn;
use XF\Util\File;

/**
 * Class Style
 * @package ThemeHouse\UIX\XF\Admin\Controller
 */
class Style extends XFCP_Style
{
    /**
     * @var
     */
    protected $ftpConnection;
    /**
     * @var string
     */
    protected $sessionKey = 'th_styleInstallUpgrade_uix';

    /**
     * @param $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     */
    public function preDispatch($action, ParameterBag $params)
    {
        parent::preDispatch($action, $params);

        $blockedActions = [
//            'Themehouse',
            'ThemehouseInstall',
            'ThemehouseUpgrade',
        ];

        if (in_array($action, $blockedActions)) {
            /** @var AddOn $addonRepo */
            $addonRepo = \XF::repository('XF:AddOn');
            /** @var UpdateCheck $update */
            $update = \XF::service(
                'ThemeHouse\Core:UpdateCheck',
                217,
                $addonRepo->inferVersionStringFromId(
                    \XF::app()->registry()['addOns']['ThemeHouse/UIX']
                )
            );
            $bypassCheck = \XF::config('uix_bypassCheck');
            if (empty($bypassCheck)) {
                $check = $update->check();

                if (!$check || $check['requires_update']) {
                    throw $this->exception($this->error(\XF::phrase('thuix_addon_requires_upgrade')));
                }
            }

            $uix = new UIX();
            $canWriteDirs = $uix->canWriteToJsAndStyleDirectories();
            if (!$canWriteDirs && !\XF::options()->th_enableFtp_uix) {
                throw $this->exception($this->error(\XF::phrase('thuix_writeable_error',
                    ['url' => $this->buildLink('options/groups', ['group_id' => 'th_uix'])])));
            }

            if (!class_exists('ZipArchive')) {
                throw $this->exception($this->error(\XF::phrase('thuix_zip_extension_error')));
            }
        }
    }

    /**
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
     */
    public function actionThemehouse()
    {
        $uix = new UIX();
        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');
        /** @var \ThemeHouse\UIX\XF\Repository\Style $styleRepo */
        $styleRepo = $this->repository('XF:Style');

        $styleResponse = $styleFetcher->fetch();
        if ($styleResponse['status'] === 'error') {
            return $this->thStyleErrorResponse($styleResponse);
        }
        $styles = $styleResponse['payload']['products'];
        $styles = $styleRepo->prepareTHStyles($styles);

        $viewParams = [
            'canWriteDirs' => $uix->canWriteToJsAndStyleDirectories(),
            'styles' => $styles,
        ];
        return $this->view('ThemeHouse\UIX:Style\ThemeHouse', 'th_style_list_uix', $viewParams);
    }

    /**
     * @param $response
     * @return \XF\Mvc\Reply\Error
     */
    protected function thStyleErrorResponse($response)
    {
        switch ($response['error_code']) {
            case 'ERR_API_KEY':
                $error = \XF::phrase('th_invalid_api_key_uix',
                    ['url' => $this->buildLink('options/groups', ['group_id' => 'th_uix'])]);
                break;

            default:
                $error = $response['error'];
                break;
        }
        return $this->error($error);
    }

    /**
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
     */
    public function actionThemeHouseChildStyles()
    {
        $uix = new UIX();

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->filter('product_id', 'uint');
        $styleId = $this->filter('style_id', 'uint');

        $style = $this->assertStyleExists($styleId);

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $product = $styleResponse['payload']['product'];

        $viewParams = [
            'canWriteDirs' => $uix->canWriteToJsAndStyleDirectories(),
            'product' => $product,
            'style' => $style,
            'refreshUrl' => $this->buildLink('styles/themehouse/child-styles/refresh', null, [
                'product_id' => $productId,
                'style_id' => $styleId,
            ]),
        ];

        return $this->view('ThemeHouse\UIX:Style\ThemeHouse\ChildStyles', 'th_child_style_list_uix', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
     * @throws \XF\PrintableException
     */
    public function actionThemeHouseChildStylesInstall()
    {
        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->filter('product_id', 'uint');
        $styleId = $this->filter('style_id', 'uint');
        $childStyle = $this->filter('child_style', 'str');

        /** @var \ThemeHouse\UIX\XF\Entity\Style $style */
        $style = $this->assertStyleExists($styleId);

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $product = $styleResponse['payload']['product'];
        $latestVersion = $styleResponse['payload']['versions'][0];

        /** @var \ThemeHouse\UIX\Service\Style\Downloader $styleDownloader */
        $styleDownloader = $this->service('ThemeHouse\UIX:Style\Downloader');
        /** @var \ThemeHouse\UIX\Service\Style\Extractor $styleExtractor */
        $styleExtractor = $this->service('ThemeHouse\UIX:Style\Extractor');


        $primaryChildStyle = $this->finder('XF:Style')->where('parent_id', '=',
            $style->style_id)->where('th_primary_child_uix', '=', 1)->fetchOne();
        if (!$primaryChildStyle) {
            return $this->error('Unable to locate primary Tactical child style');
        }

        $tempStyleDir = File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time;

        $downloadResponse = $styleDownloader->download($product['id'], $latestVersion['id']);
        if ($downloadResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            return $this->error($downloadResponse['error']);
        }

        $extractorResponse = $styleExtractor->extract($downloadResponse['path'], $tempStyleDir);
        if ($extractorResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            return $this->error($extractorResponse['error']);
        }

        /** @var StyleInstaller $styleInstallerRepo */
        $styleInstallerRepo = $this->repository('ThemeHouse\UIX:StyleInstaller');
        $childStyles = $styleInstallerRepo->getStyleNamesFromXmls($extractorResponse['path'],
            $extractorResponse['childStyles']);

        $style->th_child_style_cache_uix = $childStyles;
        $style->save();

        if (empty($childStyles[$childStyle])) {
            File::cleanUpTempFiles();
            return $this->error('Could not find child style');
        }

        $xmlFile = $tempStyleDir . DIRECTORY_SEPARATOR . 'child_xmls' . DIRECTORY_SEPARATOR . $childStyle;

        /** @var \ThemeHouse\UIX\Service\Style\Installer $installerService */
        $installerService = $this->service('ThemeHouse\UIX:Style\Installer', $product, $latestVersion);
        $installerResponse = $installerService->importStyle($xmlFile, $primaryChildStyle);
        $installerService->createChildStyle($installerResponse['style']);

        File::deleteDirectory($tempStyleDir);

        return $this->redirect($this->buildLink('styles'));
    }

    /**
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
     * @throws \XF\PrintableException
     */
    public function actionThemeHouseChildStylesRefresh(){

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->filter('product_id', 'uint');
        $styleId = $this->filter('style_id', 'uint');

        /** @var \ThemeHouse\UIX\XF\Entity\Style $style */
        $style = $this->assertStyleExists($styleId);

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $product = $styleResponse['payload']['product'];
        $latestVersion = $styleResponse['payload']['versions'][0];

        /** @var \ThemeHouse\UIX\Service\Style\Downloader $styleDownloader */
        $styleDownloader = $this->service('ThemeHouse\UIX:Style\Downloader');
        /** @var \ThemeHouse\UIX\Service\Style\Extractor $styleExtractor */
        $styleExtractor = $this->service('ThemeHouse\UIX:Style\Extractor');

        $tempStyleDir = File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time;

        $downloadResponse = $styleDownloader->download($product['id'], $latestVersion['id']);
        if ($downloadResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            return $this->error($downloadResponse['error']);
        }

        $extractorResponse = $styleExtractor->extract($downloadResponse['path'], $tempStyleDir);
        if ($extractorResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            return $this->error($extractorResponse['error']);
        }

        /** @var StyleInstaller $styleInstallerRepo */
        $styleInstallerRepo = $this->repository('ThemeHouse\UIX:StyleInstaller');
        $childStyles = $styleInstallerRepo->getStyleNamesFromXmls($extractorResponse['path'],
            $extractorResponse['childStyles']);

        File::deleteDirectory($tempStyleDir);

        $style->th_child_style_cache_uix = $childStyles;
        $style->save();

        return $this->redirect($this->buildLink('styles/themehouse/child-styles', null, [
            'product_id' => $productId,
            'style_id' => $styleId,
        ]));
    }

    /**
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    public function actionThemehouseInstall()
    {
        $options = \XF::options();

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');
        /** @var \ThemeHouse\UIX\XF\Repository\Style $styleRepo */
        $styleRepo = $this->repository('XF:Style');

        $productId = $this->filter('product_id', 'int');
        $versionId = $this->filter('version_id', 'int');

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $product = $styleResponse['payload']['product'];
        $versions = $styleResponse['payload']['versions'];

        $versions = $styleRepo->prepareTHVersions($versions);

        if (!$versions) {
            return $this->error(\XF::phrase('thuix_no_compatible_style_versions'));
        }

        if ($this->isPost()) {
            $step = $this->filter('step', 'string');

            if ($step === 'ftp_details' && $options->th_enableFtp_uix) {
                $viewParams = [
                    'product' => $product,
                    'submitUrl' => $this->buildLink('styles/themehouse/install', null,
                        ['product_id' => $product['id']]),
                    'versionId' => $versionId,
                    'fileDir' => \XF::getRootDirectory(),
                ];
                return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall\FtpDetails', 'th_style_install_ftp_uix',
                    $viewParams);
            }

            return $this->installUpgradeStyle($product, $versions);
        }

        $viewParams = [
            'product' => $product,
            'versions' => $versions,
            'freshInstall' => true,
            'submitUrl' => $this->buildLink('styles/themehouse/install', null, ['product_id' => $product['id']]),
        ];

        return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall\Version', 'th_style_install_version_uix',
            $viewParams);
    }

    /**
     * @param array $product
     * @param array $versions
     * @param \XF\Entity\Style|null $style
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\PrintableException
     */
    protected function installUpgradeStyle(array $product, array $versions, \XF\Entity\Style $style = null)
    {
        $submitUrl = $this->buildLink('styles/themehouse/install', null, ['product_id' => $product['id']]);
        if ($style) {
            $submitUrl = $this->buildLink('styles/themehouse/upgrade', null,
                ['product_id' => $product['id'], 'style_id' => $style->style_id]);
        }

        $options = \XF::options();
        $uix = new UIX();
        $path = $this->filter('path', 'str');

        $ftpDetails = $this->filter([
            'ftp_host' => 'string',
            'ftp_port' => 'uint',
            'ftp_user' => 'string',
            'ftp_pass' => 'string',
            'ftp_dir' => 'string',
        ]);

        if ($options->th_enableFtp_uix) {
            try {
                $this->ftpConnection = $uix->createFtpConnection($ftpDetails['ftp_host'], $ftpDetails['ftp_port'],
                    $ftpDetails['ftp_user'], $ftpDetails['ftp_pass'], $ftpDetails['ftp_dir']);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }

        $freshInstall = true;
        if ($style) {
            $freshInstall = false;
        }

        $versionId = $this->filter('version_id', 'int');
        $tempStyleDir = File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time;
        if ($path) {
            $tempStyleDir = $path;
        }
        $version = false;

        foreach ($versions as $thisVersion) {
            if ($thisVersion['id'] === $versionId) {
                $version = $thisVersion;
                break;
            }
        }

        if (!$version) {
            File::cleanUpTempFiles();
            return $this->error('The version you have requested was not found');
        }

        /** @var \ThemeHouse\UIX\Service\Style\Downloader $styleDownloader */
        $styleDownloader = $this->service('ThemeHouse\UIX:Style\Downloader');
        /** @var \ThemeHouse\UIX\Service\Style\Extractor $styleExtractor */
        $styleExtractor = $this->service('ThemeHouse\UIX:Style\Extractor');

        $childStylePaths = [];
        if (!is_dir($path)) {
            $downloadResponse = $styleDownloader->download($product['id'], $versionId);
            if ($downloadResponse['status'] === 'error') {
                File::cleanUpTempFiles();
                return $this->error($downloadResponse['error']);
            }

            $extractorResponse = $styleExtractor->extract($downloadResponse['path'], $tempStyleDir);
            if ($extractorResponse['status'] === 'error') {
                File::cleanUpTempFiles();
                return $this->error($extractorResponse['error']);
            }

            /** @var StyleInstaller $styleInstallerRepo */
            $styleInstallerRepo = $this->repository('ThemeHouse\UIX:StyleInstaller');
            $childStyleNames = $styleInstallerRepo->getStyleNamesFromXmls($extractorResponse['path'],
                $extractorResponse['childStyles']);
            $this->session()->set($this->sessionKey, $childStyleNames);

            if (!empty($extractorResponse['childStyles']) && $freshInstall) {
                $viewParams = [
                    'path' => $extractorResponse['path'],
                    'childStyles' => $childStyleNames,
                    'ftpDetails' => $ftpDetails,
                    'submitUrl' => $submitUrl,
                    'versionId' => $versionId,
                ];
                return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall', 'th_style_install_child_uix', $viewParams);
            }
        } else {
            $childStyles = $this->filter('child_styles', 'array');
            foreach ($childStyles as $childStyle) {
                $childStylePath = $path . DIRECTORY_SEPARATOR . 'child_xmls' . DIRECTORY_SEPARATOR . $childStyle;

                if (!file_exists($childStylePath)) {
                    return $this->error('Unable to find the requested child style');
                }

                $childStylePaths[$childStyle] = $childStylePath;
            }
        }

        $childStyleNames = $this->session()->get($this->sessionKey);

        /** @var \ThemeHouse\UIX\Service\Style\Mover $styleMover */
        $styleMover = $this->service('ThemeHouse\UIX:Style\Mover', $tempStyleDir, \XF::getRootDirectory(),
            $this->ftpConnection);
        $moverResponse = $styleMover->move();

        if ($moverResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            $this->session()->remove($this->sessionKey);
            return $this->error($moverResponse['error']);
        }

        if ($freshInstall) {
            /** @var \ThemeHouse\UIX\Service\Style\Installer $styleInstaller */
            $styleInstaller = $this->service('ThemeHouse\UIX:Style\Installer', $product, $version);

            $installResponse = $styleInstaller->install($tempStyleDir, $childStylePaths, $childStyleNames);

            if ($installResponse['status'] === 'error') {
                File::cleanUpTempFiles();
                $this->session()->remove($this->sessionKey);
                return $this->error($installResponse['error']);
            }
        } else {
            /** @var \ThemeHouse\UIX\Service\Style\Upgrader $styleUpgrader */
            $styleUpgrader = $this->service('ThemeHouse\UIX:Style\Upgrader', $product, $version, $style);

            $upgradeResponse = $styleUpgrader->upgrade($tempStyleDir, $childStyleNames);

            if ($upgradeResponse['status'] === 'error') {
                File::cleanUpTempFiles();
                $this->session()->remove($this->sessionKey);
                return $this->error($upgradeResponse['error']);
            }
        }

        File::cleanUpTempFiles();
        $this->session()->remove($this->sessionKey);
        \ThemeHouse\UIX\Cron\UpdateCheck::run();
        return $this->redirect($this->buildLink('styles/themehouse'));
    }

    /**
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\PrintableException
     */
    public function actionThemehouseUpgrade()
    {
        $options = \XF::options();

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->request()->filter('product_id', 'int');
        $styleId = $this->request()->filter('style_id', 'int');
        $versionId = $this->filter('version_id', 'int');

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $style = $this->assertStyleExists($styleId);

        $product = $styleResponse['payload']['product'];
        $versions = $styleResponse['payload']['versions'];

        if ($this->isPost()) {
            $step = $this->filter('step', 'string');

            if ($step === 'ftp_details' && $options->th_enableFtp_uix) {
                $viewParams = [
                    'product' => $product,
                    'style' => $style,
                    'submitUrl' => $this->buildLink('styles/themehouse/upgrade', null,
                        ['product_id' => $product['id'], 'style_id' => $style->style_id]),
                    'versionId' => $versionId,
                    'fileDir' => \XF::getRootDirectory(),
                ];
                return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall\FtpDetails', 'th_style_install_ftp_uix',
                    $viewParams);
            }

            return $this->installUpgradeStyle($product, $versions, $style);
        }

        $viewParams = [
            'product' => $product,
            'versions' => $versions,
            'style' => $style,
            'freshInstall' => false,
            'submitUrl' => $this->buildLink('styles/themehouse/upgrade', null,
                ['product_id' => $product['id'], 'style_id' => $style->style_id]),
        ];

        return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall', 'th_style_install_version_uix', $viewParams);
    }
}
