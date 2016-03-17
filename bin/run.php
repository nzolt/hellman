#!/usr/bin/env php
<?php

    chdir(dirname(__DIR__)); // set directory to root
require 'vendor/autoload.php'; // composer autoload

use Gtin\Gtin;

// convert all the command line arguments into a URL
$argv = $GLOBALS['argv'];
array_shift($GLOBALS['argv']);
$pathInfo = '/' . implode('/', $argv);



// Create our app instance
$app = new Slim\Slim([
'debug' => false,  // Turn off Slim's own PrettyExceptions
]);

// Set up the environment so that Slim can route
$app->environment = Slim\Environment::mock([
'PATH_INFO'   => $pathInfo
]);


// CLI-compatible not found error handler
$app->notFound(function () use ($app) {
$url = $app->environment['PATH_INFO'];
echo "Error: Cannot route to $url";
$app->stop();
});

// Format errors for CLI
$app->error(function (\Exception $e) use ($app) {
echo $e;
$app->stop();
});

$gtin = new Gtin();

// routes - as per normal - no HTML though!
$app->get('/gtin/:gtinnr', function ($gtinnr) use ($gtin) {
    $gtin->setGtinNumber($gtinnr);
    echo $gtin->getFullGtinNumber();
});

// run!
$app->run();