<?php

use Dotenv\Dotenv;
require __DIR__.'/vendor/autoload.php';

require __DIR__.'/services/service.php';
$dotenv = Dotenv:: createImmutable(__DIR__);

$dotenv->load();
$app->run();

