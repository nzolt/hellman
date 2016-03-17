<?php

require 'vendor/autoload.php';

use Slim\Slim;
use Gtin\Gtin;

$app = new Slim([
    'debug' => true,
    'log.enabled' => true,
    'Name' => 'GTIN',
]);

$gtin = new Gtin();

$app->get('/gtin/:gtinnr', function ($gtinnr)  use ($gtin) {
    $gtin->setGtinNumber($gtinnr);
    echo $gtin->getFullGtinNumber();
});

$app->run();