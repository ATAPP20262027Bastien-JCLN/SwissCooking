<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking;

use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

use BastienJcln\SwissCooking\Middleware\SessionMiddleware;
use BastienJcln\SwissCooking\Middleware\ErrorMiddleware;

class MyApp
{
    public static function create(): App
    {
        $app = AppFactory::create();

        ErrorMiddleware::register($app);

        $app->add(new SessionMiddleware());

        $errorMiddleware = $app->addErrorMiddleware(true,true,true);

        require __DIR__ . '/../routes/web.php';

        return $app;
    }
}
