<?php
// NOTE: 'use' declarations are not allowed inside method scope (Slim renders templates via
// require inside View::render()). Use the fully-qualified class name instead.
ini_set('display_errors', 0);
error_reporting(E_ALL);

require __DIR__ . '/../bootstrap.php';

if (!is_null($data)) {
    $ta = new \NDSE\Tools\TransientAnalysis($data);
    $result = $ta->run();
    echo $result;
}
