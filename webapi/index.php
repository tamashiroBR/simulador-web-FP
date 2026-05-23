<?php
require __DIR__ . '/Slim/Slim.php';

use Slim\Slim;
Slim::registerAutoloader();

$app = new Slim();

$app->config([
    'templates.path' => __DIR__ . '/templates'
]);

// Allow CORS so the frontend (served from any origin) can call this API
$app->add(new \Slim\Middleware\ContentTypes());

$app->hook('slim.before', function () use ($app) {
    $app->response()->header('Access-Control-Allow-Origin', '*');
    $app->response()->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
    $app->response()->header('Access-Control-Allow-Headers', 'Content-Type');
});

// Handle preflight OPTIONS requests
$app->options('/nws/v1/loadflow', function () use ($app) {
    $app->response()->header('Access-Control-Allow-Origin', '*');
    $app->response()->header('Access-Control-Allow-Methods', 'POST, OPTIONS');
    $app->response()->header('Access-Control-Allow-Headers', 'Content-Type');
    $app->response()->setStatus(200);
});

$app->options('/nws/v1/stability', function () use ($app) {
    $app->response()->header('Access-Control-Allow-Origin', '*');
    $app->response()->header('Access-Control-Allow-Methods', 'POST, OPTIONS');
    $app->response()->header('Access-Control-Allow-Headers', 'Content-Type');
    $app->response()->setStatus(200);
});

// Defines routes
$app->get('/', function () use ($app) {
    echo "<h1>NDSE Web Simulator web API</h1>";
});

$app->group('/nws/v1', function () use ($app) {

    $app->post('/loadflow', function () use ($app) {
        $json = json_decode($app->request->getBody());
        $data = ['data' => [
            'optLF'  => $json->optLF,
            'bus'    => $json->bus,
            'branch' => $json->branch
        ]];
        $app->response()->header('Content-Type', 'application/json');
        $app->render('loadflow.php', $data, 200);
    });

    $app->post('/stability', function () use ($app) {
        $json = json_decode($app->request->getBody());
        $data = ['data' => [
            'optLF'  => $json->optLF,
            'optTA'  => $json->optTA,
            'bus'    => $json->bus,
            'branch' => $json->branch,
            'gen'    => $json->gen,
            'exc'    => $json->exc,
            'gov'    => $json->gov,
            'event'  => $json->event
        ]];
        $app->response()->header('Content-Type', 'application/json');
        $app->render('stability.php', $data, 200);
    });

});

// Run Slim application
$app->run();
