<?php

use BastienJcln\SwissCooking\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);

// $app->get('/', [Controller_Class::class, 'Func Name'])
// ->add(new Middleware_Class())
