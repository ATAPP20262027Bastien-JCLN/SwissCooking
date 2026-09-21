<?php

use BastienJcln\SwissCooking\Controllers\HomeController;
use BastienJcln\SwissCooking\Controllers\LoginController;

use BastienJcln\SwissCooking\Middleware\SessionMiddleware;

$app->get('/', [HomeController::class, 'index']);

$app->get('/login', [LoginController::class, 'showLogin']);
$app->post('/login', [LoginController::class, 'login']);
$app->get('/register', [LoginController::class, 'showRegistration']);
$app->post('/register', [LoginController::class, 'register']);
$app->get('/logout', [LoginController::class, 'logout']);

// $app->get('/', [Controller_Class::class, 'Func Name'])
// ->add(new Middleware_Class())
