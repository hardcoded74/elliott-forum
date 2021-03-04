<?php

namespace ThemeHouse\UIX\Listener;

use ThemeHouse\UIX\Manifest\Renderer;
use XF\App;
use XF\Container;

/**
 * Class AppSetup
 * @package ThemeHouse\UIX\Listener
 */
class AppSetup
{
    /**
     * @param App $app
     */
    public static function appSetup(App $app)
    {
        $container = $app->container();

        $container->set('uixManifest.renderer', function (Container $c) use ($app) {
            return new Renderer($app);
        }, false);
    }
}
