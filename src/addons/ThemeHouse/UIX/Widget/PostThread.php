<?php

namespace ThemeHouse\UIX\Widget;

use XF\Widget\AbstractWidget;

/**
 * Class PostThread
 * @package ThemeHouse\UIX\Widget
 */
class PostThread extends AbstractWidget
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'description' => 'Ask a question, post a suggestion, start a discussion',
    ];

    /**
     * @return \XF\Widget\WidgetRenderer
     */
    public function render()
    {
        return $this->renderer('th_widget_post_thread_uix');
    }
}
