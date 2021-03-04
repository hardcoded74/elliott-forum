<?php

namespace ThemeHouse\UIX\Pub\View\UIX;

use XF\Mvc\View;

/**
 * Class ToggleWidth
 * @package ThemeHouse\UIX\Pub\View\UIX
 */
class ToggleWidth extends View
{
    /**
     * @return array
     */
    public function renderJson()
    {
        $this->response->header('X-Robots-Tag', 'noindex');

        return [
            'width' => $this->params['width'],
        ];
    }
}
