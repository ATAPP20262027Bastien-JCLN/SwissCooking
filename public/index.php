<?php

declare(strict_types=1);

use BastienJcln\SwissCooking\MyApp;

require __DIR__ . '/../vendor/autoload.php';

$app = MyApp::create();

$app->run();