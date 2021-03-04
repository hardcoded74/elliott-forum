<?php

namespace ThemeHouse\UIX\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Entity\StyleProperty;
use XF\Mvc\ParameterBag;

/**
 * Class UixSpImageUpload
 * @package ThemeHouse\UIX\Admin\Controller
 */
class UixSpImageUpload extends AbstractController
{
    /**
     * @param $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     */
    function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('uploadSPImages');
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        $styleProperty = $this->assertStylePropertyExists($params['property_id']);
        return $this->view('ThemeHouse\UIX:StyleProperty\ImageUpload', 'th_uix_sp_image_upload', [
            'styleProperty' => $styleProperty,
            'defaultPath' => $this->getDefaultPathForSPImage($styleProperty),
            'defaultName' => $styleProperty->property_name
        ]);
    }

    /**
     * @param $id
     * @param null $with
     * @param null $phraseKey
     * @return StyleProperty
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertStylePropertyExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('XF:StyleProperty', $id, $with, $phraseKey);
    }

    /**
     * @param StyleProperty $styleProperty
     * @return string
     */
    protected function getDefaultPathForSPImage(StyleProperty $styleProperty)
    {
        $styleName = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $styleProperty->Style->title));
        $spGroup = $styleProperty->group_name;

        $path = 'styles' . '/' . $styleName . '/' . $spGroup;
        return $path;
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionUpload(ParameterBag $params)
    {
        $styleProperty = $this->assertStylePropertyExists($params['property_id']);

        $input = $this->filter([
            'custom_disk_path' => 'bool',
            'rename_file' => 'bool',
            'path' => 'str',
            'name' => 'str'
        ]);

        if ($input['custom_disk_path']) {
            $path = $input['path'];
        } else {
            $path = $this->getDefaultPathForSPImage($styleProperty);
        }

        if ($input['rename_file']) {
            $name = $input['name'];
        } else {
            $name = $styleProperty->property_name;
        }

        $file = $this->request()->getFile('file');
        $file->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'jpe']);

        $extension = $file->getExtension();

        if (!$file->isImage()) {
            return $this->error(\XF::phrase('thuix_file_must_be_an_image'));
        }

        if (!$file->isValid($errors)) {
            return $this->error($errors);
        }

        $diskPath = \XF::getRootDirectory() . DIRECTORY_SEPARATOR . str_replace('/',
                DIRECTORY_SEPARATOR, $path);

        @mkdir($path, 0755, true);
        move_uploaded_file($file->getTempFile(), $diskPath . DIRECTORY_SEPARATOR . $name . '.' . $extension);

        $viewParams = [
            'file' => $path . '/' . $name . '.' . $extension,
            'stylePropertyId' => $styleProperty->property_id,
            'message' => \XF::phrase('th_uix_image_successfully_uploaded')
        ];

        return $this->view('ThemeHouse\UIX:StyleProperty\ImageUploadResult', '', $viewParams);
    }

    /**
     * @param $src
     * @param $dst
     */
    protected function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
