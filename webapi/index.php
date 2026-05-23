<?php
// Suppress any HTML error output — all responses must be JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Catch fatal errors and return JSON instead of HTML
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        // Clean any partial output already sent
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        echo json_encode([
            'error' => $error['message'],
            'file'  => $error['file'],
            'line'  => $error['line']
        ]);
    }
});

ob_start();

require __DIR__ . '/Slim/Slim.php';

use Slim\Slim;
Slim::registerAutoloader();

$app = new Slim();

$app->config([
    'templates.path' => __DIR__ . '/templates'
]);

// CORS headers for all responses
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

// Root route
$app->get('/', function () use ($app) {
    echo "<h1>NDSE Web Simulator web API</h1>";
});

$app->group('/nws/v1', function () use ($app) {

    $app->post('/loadflow', function () use ($app) {
        $body = $app->request->getBody();
        $json = json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE || is_null($json)) {
            $app->response()->header('Content-Type', 'application/json');
            $app->response()->setStatus(400);
            echo json_encode(['error' => 'Invalid JSON body: ' . json_last_error_msg()]);
            return;
        }

        if (!isset($json->optLF) || !isset($json->bus) || !isset($json->branch)) {
            $app->response()->header('Content-Type', 'application/json');
            $app->response()->setStatus(400);
            echo json_encode(['error' => 'Missing required fields: optLF, bus, branch']);
            return;
        }

        $data = ['data' => [
            'optLF'  => $json->optLF,
            'bus'    => $json->bus,
            'branch' => $json->branch
        ]];

        $app->response()->header('Content-Type', 'application/json');
        $app->render('loadflow.php', $data, 200);
    });

    $app->post('/stability', function () use ($app) {
        $body = $app->request->getBody();
        $json = json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE || is_null($json)) {
            $app->response()->header('Content-Type', 'application/json');
            $app->response()->setStatus(400);
            echo json_encode(['error' => 'Invalid JSON body: ' . json_last_error_msg()]);
            return;
        }

        $data = ['data' => [
            'optLF'  => $json->optLF  ?? null,
            'optTA'  => $json->optTA  ?? null,
            'bus'    => $json->bus    ?? null,
            'branch' => $json->branch ?? null,
            'gen'    => $json->gen    ?? null,
            'exc'    => $json->exc    ?? null,
            'gov'    => $json->gov    ?? null,
            'event'  => $json->event  ?? null
        ]];

        $app->response()->header('Content-Type', 'application/json');
        $app->render('stability.php', $data, 200);
    });

});

// Run Slim application
$app->run();
