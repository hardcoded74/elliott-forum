<?php

namespace ThemeHouse\UIX\Manifest;

use XF\App;
use XF\Http\Response;

/**
 * Class Renderer
 * @package ThemeHouse\UIX\Manifest
 */
class Renderer
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var \XF\Style
     */
    protected $style;

    /**
     * Renderer constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param Response $response
     * @param $styleId
     * @return Response
     * @throws \Exception
     */
    public function outputManifest(Response $response, $styleId)
    {
        if ($styleId) {
            $this->style = $this->app->container()->create('style', $styleId);
        }
        if (!$this->style) {
            $this->style = $this->app->get('style.fallback');
        }

        /** @var \XF\CssRenderer $renderer */
        $rendererClass = $this->app->extendClass('XF\CssRenderer');
        $renderer = new $rendererClass($this->app, $this->app->get('templater'));
        $renderer->setStyle($this->style);

        $response->header('X-Robots-Tag', 'noindex');

        if (!\XF::options()->thuix_enableManifest) {
            $response->httpCode(404);
            $response->contentType('text/plain');
            $response->body('no sitemap');
            return $response;
        }

        $response->contentType('application/json');

        $response->setDownloadFileName(
            'manifest.json',
            true
        );

        $sizes = [192, 512];
        $icons = [];

        foreach ($sizes as $size) {
            if ($this->style->getProperty('thuix_webapp_icon' . $size . 'x' . $size)) {
                $icons[] = [
                    'src' => $this->style->getProperty('thuix_webapp_icon' . $size . 'x' . $size),
                    'sizes' => $size . 'x' . $size,
                    'type' => 'image/png'
                ];
            }
        }

        $themeColor = $renderer->parseLessColorValue(
            $this->style->getProperty('metaThemeColor')
        ) ?: '#ffffff';

        $backgroundColor = $renderer->parseLessColorValue(
            $this->style->getProperty('thuix_webapp_backgroundColor')
        ) ?: $themeColor;

        $manifest = [
            'name' => \XF::options()->thuix_webAppName ?: \XF::options()->boardTitle,
            'short_name' => \XF::options()->thuix_webAppShortName,
            'icons' => $icons,
            'theme_color' => $themeColor,
            'background_color' => $backgroundColor,
            'scope' => \XF::app()->request()->getBasePath(),
            'display' => \XF::options()->thuix_webAppDisplay ?: 'standalone',
        ];

        $response->body(json_encode($manifest, JSON_PRETTY_PRINT));

        return $response;
    }
}
