<?php

use BastienJcln\SwissCooking\Controllers\CategoryController;
use BastienJcln\SwissCooking\Controllers\ErrorController;
use BastienJcln\SwissCooking\Controllers\HomeController;
use BastienJcln\SwissCooking\Controllers\LoginController;
use BastienJcln\SwissCooking\Controllers\RecipeController;
use BastienJcln\SwissCooking\Controllers\UserController;

$app->get('/400', [ErrorController::class, 'badRequest']);
$app->get('/401', [ErrorController::class, 'unauthorized']);
$app->get('/403', [ErrorController::class, 'forbidden']);
$app->get('/404', [ErrorController::class, 'notFound']);
$app->get('/405', [ErrorController::class, 'methodNotAllowed']);
$app->get('/500', [ErrorController::class, 'serverError']);

$app->get('/', [HomeController::class, 'index']);

$app->get('/recipes', [RecipeController::class, 'list']);
$app->post('/recipes', [RecipeController::class, 'list']);
$app->get('/recipe/create', [RecipeController::class, 'create']);
$app->post('/recipe/create', [RecipeController::class, 'store']);
$app->get('/recipe/{id}', [RecipeController::class, 'show']);
$app->post('/recipe/{id}/delete', [RecipeController::class, 'delete']);
$app->get('/recipe/{id}/edit', [RecipeController::class, 'editForm']);
$app->post('/recipe/{id}/edit', [RecipeController::class, 'update']);
$app->post('/recipe/{id}/favorite', [UserController::class, 'toggleFavorite']);
$app->post('/recipe/{id}/rate', [RecipeController::class, 'rate']);

$app->get('/favorites', [UserController::class, 'favorites']);

$app->get('/categories', [CategoryController::class, 'index']);

$app->get('/login', [LoginController::class, 'showLogin']);
$app->post('/login', [LoginController::class, 'login']);
$app->get('/register', [LoginController::class, 'showRegistration']);
$app->post('/register', [LoginController::class, 'register']);

$app->get('/profile', [UserController::class, 'profile']);
$app->get('/user/{id}', [UserController::class, 'publicProfile']);
$app->post('/profile/update', [UserController::class, 'updateProfile']);
$app->post('/profile/picture', [UserController::class, 'updateProfilePicture']);
$app->get('/profile/picture/delete', [UserController::class, 'deleteProfilePicture']);
$app->get('/logout', [UserController::class, 'logout']);

// $app->get('/', [Controller_Class::class, 'Func Name'])
//     ->add(new Middleware_Class());
