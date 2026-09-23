<?php

use BastienJcln\SwissCooking\Controllers\HomeController;
use BastienJcln\SwissCooking\Controllers\LoginController;
use BastienJcln\SwissCooking\Controllers\RecipeController;
use BastienJcln\SwissCooking\Controllers\ErrorController;

$app->get('/400', [ErrorController::class, 'badRequest']);
$app->get('/401', [ErrorController::class, 'unauthorized']);
$app->get('/403', [ErrorController::class, 'forbidden']);
$app->get('/404', [ErrorController::class, 'notFound']);
$app->get('/405', [ErrorController::class, 'methodNotAllowed']);
$app->get('/500', [ErrorController::class, 'serverError']);

$app->get('/', [HomeController::class, 'index']);

$app->get('/recipes', [HomeController::class, 'list']);
$app->post('/recipes', [HomeController::class, 'list']);
$app->get('/recipe/{id}', [RecipeController::class, 'show']);

$app->get('/categories', [HomeController::class, 'categories']);

$app->get('/login', [LoginController::class, 'showLogin']);
$app->post('/login', [LoginController::class, 'login']);
$app->get('/register', [LoginController::class, 'showRegistration']);
$app->post('/register', [LoginController::class, 'register']);
$app->get('/logout', [LoginController::class, 'logout']);

// $app->get('/', [Controller_Class::class, 'Func Name'])
// ->add(new Middleware_Class())
