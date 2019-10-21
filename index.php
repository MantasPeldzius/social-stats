<?php

use App\Controllers\App;

// Classes autoloder
require_once 'system/autoload.php';
// Configuration for app
$config = require(__DIR__ . '/system/config.php');

$app = new App($config);
// $app->debug();
$app->run();
