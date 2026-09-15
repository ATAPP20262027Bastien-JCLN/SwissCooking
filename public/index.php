<?php

declare(strict_types=1);

use BastienJcln\SwissCooking\MyApp;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$app = MyApp::create();

$app->run();