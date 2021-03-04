<?php

namespace ThemeHouse\UIX\Admin\View\StyleProperty;

use XF\Mvc\View;

/**
 * Class ImageUploadResult
 * @package ThemeHouse\UIX\Admin\View\StyleProperty
 */
class ImageUploadResult extends View
{
    /**
     * @return array
     */
    public function renderJson()
    {
        return $this->getParams();
    }
}