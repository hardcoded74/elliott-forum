<?php

namespace ThemeHouse\Uix\Cli\Command\Uix;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThemeHouse\UIX\Repository\StyleInstaller;
use XF\Entity\Style;
use XF\Util\File;

/**
 * Class InstallStyle
 * @package ThemeHouse\Uix\Cli\Command\Uix
 */
class InstallStyle extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('uix:install-style')
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'Product ID'
            )
            ->addArgument(
                'child-style-xml',
                InputArgument::OPTIONAL,
                'Child Style XML Name'
            )
            ->setDescription(
                'Imports templates, style properties, options, phrases, etc from all add-ons and styles'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     * @throws \XF\PrintableException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = \XF::service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $input->getArgument('id');
        $childStyleXml = $input->getArgument('child-style-xml');
        $output = $styleFetcher->fetch($productId);
        $product = $output['payload']['product'];
        $versions = $output['payload']['versions'];
        $latestVersion = $versions[0];

        $versionId = $latestVersion['id'];
        $tempStyleDir = File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time;
        $version = false;

        foreach ($versions as $thisVersion) {
            if ($thisVersion['id'] === $versionId) {
                $version = $thisVersion;
                break;
            }
        }

        if (!$version) {
            File::cleanUpTempFiles();
            throw new \Exception('The version you have requested was not found');
        }

        /** @var \ThemeHouse\UIX\Service\Style\Downloader $styleDownloader */
        $styleDownloader = \XF::service('ThemeHouse\UIX:Style\Downloader');
        /** @var \ThemeHouse\UIX\Service\Style\Extractor $styleExtractor */
        $styleExtractor = \XF::service('ThemeHouse\UIX:Style\Extractor');

        $downloadResponse = $styleDownloader->download($product['id'], $versionId);
        if ($downloadResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            throw new \Exception($downloadResponse['error']);
        }

        $extractorResponse = $styleExtractor->extract($downloadResponse['path'], $tempStyleDir);
        if ($extractorResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            throw new \Exception($extractorResponse['error']);
        }

        $childStylePaths = [];
        /** @var StyleInstaller $styleInstallRepo */
        $styleInstallRepo = \XF::repository('ThemeHouse\UIX:StyleInstaller');
        $childStyleNames = $styleInstallRepo->getStyleNamesFromXmls($extractorResponse['path'],
            $extractorResponse['childStyles']);
        if ($childStyleXml) {
            $childStylePath = $tempStyleDir . DIRECTORY_SEPARATOR . 'child_xmls' . DIRECTORY_SEPARATOR . $childStyleXml;
            if (isset($childStyleNames[$childStyleXml]) && @file_exists($childStylePath)) {
                $childStylePaths[] = $childStylePath;
            }
        }

        /** @var \ThemeHouse\UIX\Service\Style\Mover $styleMover */
        $styleMover = \XF::service('ThemeHouse\UIX:Style\Mover', $tempStyleDir, \XF::getRootDirectory());
        $moverResponse = $styleMover->move();

        if ($moverResponse['status'] === 'error') {
            return 0;
        }

        /** @var \ThemeHouse\UIX\Service\Style\Installer $styleInstaller */
        $styleInstaller = \XF::service('ThemeHouse\UIX:Style\Installer', $product, $version);
        $installResponse = $styleInstaller->install($tempStyleDir, $childStylePaths, $childStyleNames);
        if ($installResponse['status'] === 'error') {
            return 0;
        }


        File::cleanUpTempFiles();

        $styleFinder = \XF::finder('XF:Style');
        /** @var Style $style */
        $style = $styleFinder->order('style_id', 'desc')->fetchOne();

        /** @var \XF\Repository\Option $optionRepo */
        $optionRepo = \XF::repository('XF:Option');
        $optionRepo->updateOption('defaultStyleId', $style->style_id);
        return 0;
    }
}