<?php

$dir = __DIR__;
require($dir . '/src/XF.php');

XF::start($dir);
$app = XF::setupApp('XF\Pub\App');

/** @var \ThemeHouse\UIX\Manifest\Renderer $renderer */
$renderer = $app['uixManifest.renderer'];
$request = $app->request();
$styleId = $request->filter('s', 'uint');

$response = $app->response();

$response = $renderer->outputManifest($response, $styleId);
$response->send($request);
