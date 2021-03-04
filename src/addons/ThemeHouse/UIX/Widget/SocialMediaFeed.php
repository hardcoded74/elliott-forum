<?php

namespace ThemeHouse\UIX\Widget;

use XF\Http\Request;
use XF\Widget\AbstractWidget;

/**
 * Class SocialMediaFeed
 * @package ThemeHouse\UIX\Widget
 */
class SocialMediaFeed extends AbstractWidget
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'platform' => '',
        'name' => '',
    ];

    /**
     * @param Request $request
     * @param array $options
     * @param null $error
     * @return bool
     */
    public function verifyOptions(Request $request, array &$options, &$error = null)
    {
        if (empty($options['platform'])) {
            $error = 'Please select a platform';
            return false;
        }

        if (empty($options['name'])) {
            $error = 'Please enter your name/unique ID for the selected platform';
            return false;
        }
        return true;
    }

    /**
     * @return \XF\Widget\WidgetRenderer
     */
    public function render()
    {
        return $this->renderer('thuix_widget_social_media_feed');
    }
}
