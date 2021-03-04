<?php

namespace ThemeHouse\UIX\Pub\View\UIX;

use XF\Mvc\View;

/**
 * Class Toggle
 * @package ThemeHouse\UIX\Pub\View\UIX
 */
class Toggle extends View
{
    /**
     * @return array
     */
    public function renderJson()
    {
        $this->response->header('X-Robots-Tag', 'noindex');

        return [
            'collapsed' => $this->params['collapsed'],
        ];
    }
}
