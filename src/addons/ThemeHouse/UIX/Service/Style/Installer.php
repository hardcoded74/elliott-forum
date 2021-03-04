<?php

namespace ThemeHouse\UIX\Service\Style;

use XF\App;
use XF\Entity\Style;
use XF\Service\AbstractService;
use XF\Util\File;
use XF\Util\Xml;

/**
 * Class Installer
 * @package ThemeHouse\UIX\Service\Style
 */
class Installer extends AbstractService
{
    /**
     * @var array
     */
    protected $product;
    /**
     * @var array
     */
    protected $version;

    /**
     * Installer constructor.
     * @param App $app
     * @param array $product
     * @param array $version
     */
    public function __construct(App $app, array $product, array $version)
    {
        parent::__construct($app);

        $this->product = $product;
        $this->version = $version;
    }

    /**
     * @param $tempDir
     * @param array $childStyles
     * @param array|null $childStyleNames
     * @return array
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    public function install($tempDir, array $childStyles = [], array $childStyleNames = null)
    {
        if ($childStyleNames === null) {
            $childStyleNames = [];
        }
        $dirHandle = opendir($tempDir);

        $xmlFile = false;
        while ($file = readdir($dirHandle)) {
            if (strpos($file, '.xml') !== false && strpos($file, 'style-') !== false) {
                $xmlFile = $tempDir . DIRECTORY_SEPARATOR . $file;
            }
        }

        if (!$xmlFile) {
            File::deleteDirectory($tempDir);
            return [
                'status' => 'error',
                'error' => 'Unable to find style XML please <a href="https://www.themehouse.com/contact/create-ticket">create a ticket</a> for support.',
            ];
        }

        $importResponse = $this->importStyle($xmlFile, null, $childStyleNames);
        if ($importResponse['status'] === 'error') {
            File::deleteDirectory($tempDir);
            return $importResponse;
        }

        $visible = empty($childStyles);
        $mainChildStyle = $this->createChildStyle($importResponse['style'], $visible, true);

        if (!empty($childStyles)) {
            foreach ($childStyles as $childStyle => $childXmlFile) {
                $childImportResponse = $this->importStyle($childXmlFile, $mainChildStyle);
                if ($childImportResponse['status'] === 'error') {
                    File::deleteDirectory($tempDir);
                    return $childImportResponse;
                }

                $this->createChildStyle($childImportResponse['style']);
            }
        }

        File::deleteDirectory($tempDir);
        return [
            'status' => 'success',
        ];
    }

    /**
     * @param $xmlFile
     * @param Style|null $parent
     * @param array $childStyleNames
     * @return array
     * @throws \XF\PrintableException
     */
    public function importStyle($xmlFile, Style $parent = null, array $childStyleNames = [])
    {
        /** @var \XF\Service\Style\Import $styleImporter */
        $styleImporter = $this->service('XF:Style\Import');

        try {
            $document = Xml::openFile($xmlFile);
        } catch (\Exception $e) {
            $document = null;
        }

        if (!$styleImporter->isValidXml($document, $error)) {
            return [
                'status' => 'error',
                'error' => $error,
            ];
        }

        $parentStyleId = 0;
        if ($parent) {
            $parentStyleId = $parent->style_id;
        }

        $styleImporter->setParentStyle(null);
        $style = $styleImporter->importFromXml($document);
        $style->user_selectable = 0;
        $style->parent_id = $parentStyleId;
        if (!$parent) {
            $style->th_product_id_uix = $this->product['id'];
            $style->th_product_version_uix = $this->version['version'];
            $style->th_child_style_cache_uix = $childStyleNames;
        } else {
            $xmlFileParts = explode(DIRECTORY_SEPARATOR, $xmlFile);
            $xmlFileName = end($xmlFileParts);
            $style->th_child_style_xml_uix = $xmlFileName;
        }
        $style->save();

        return [
            'status' => 'success',
            'style' => $style,
        ];
    }

    /**
     * @param $parent
     * @param bool $selectable
     * @param bool $thChild
     * @return \XF\Mvc\Entity\Entity
     * @throws \XF\PrintableException
     */
    public function createChildStyle($parent, $selectable = true, $thChild = false)
    {
        $style = $this->em()->create('XF:Style');
        $style->bulkSet([
            'parent_id' => $parent['style_id'],
            'title' => $parent['title'] . ' (child)',
            'user_selectable' => $selectable,
            'th_primary_child_uix' => $thChild,
        ]);
        $style->save();

        return $style;
    }
}
