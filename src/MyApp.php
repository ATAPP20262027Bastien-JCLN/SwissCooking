<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking;

use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

use BastienJcln\SwissCooking\Middleware\SessionMiddleware;

class MyApp
{
    public static function create(): App
    {
        $app = AppFactory::create();

        $app->add(new SessionMiddleware());

        $errorMiddleware = $app->addErrorMiddleware(
            true,
            true,
            true
        );

        $errorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function ($request, $exception, $displayErrorDetails) {
                $response = new \Slim\Psr7\Response();

                $view = new PhpRenderer(
                    __DIR__ . '/../views'
                );

                $view->setLayout('layout.php');

                return $view->render(
                    $response->withStatus(404),
                    'errors/404.php',
                    [
                        'withMenu' => false,
                        'title' => 'Page non trouvée',
                        'message' => $exception->getMessage(),
                    ]
                );
            }
        );

        $errorMiddleware->setErrorHandler(
            HttpInternalServerErrorException::class,
            function ($request, $exception, $displayErrorDetails) {
                $response = new \Slim\Psr7\Response();

                $view = new PhpRenderer(
                    __DIR__ . '/../views'
                );

                $view->setLayout('layout.php');

                return $view->render(
                    $response->withStatus(500),
                    'errors/500.php',
                    [
                        'withMenu' => false,
                        'title' => 'Erreur interne du serveur',
                        'message' => $exception->getMessage(),
                    ]
                );
            }
        );

        require __DIR__ . '/../routes/web.php';

        return $app;
    }
}
