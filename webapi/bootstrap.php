<?php
//header('Content-Type: text/html; charset=utf-8');

require __DIR__ . '/src/NDSE/Autoloader.php';

$loader = new Psr4AutoloaderClass;

// register the autoloader
$loader->register();

// register the base directories for the namespace prefix
$loader->addNamespace('NDSE',             __DIR__ . '/src/NDSE/Core');
$loader->addNamespace('NDSE\Math',        __DIR__ . '/src/NDSE/Core/Math');
$loader->addNamespace('NDSE\Tools',       __DIR__ . '/src/NDSE/Core/Tools');
$loader->addNamespace('NDSE\Models',      __DIR__ . '/src/NDSE/Core/Models');
$loader->addNamespace('NDSE\Models\Gen',  __DIR__ . '/src/NDSE/Core/Models/Gen');