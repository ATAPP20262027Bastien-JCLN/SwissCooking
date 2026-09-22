<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Middleware;

use BastienJcln\SwissCooking\Controllers\ErrorController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Middleware\ErrorMiddleware as SlimErrorMiddleware;

class ErrorMiddleware
{
    public static function register(App $app): SlimErrorMiddleware
    {
        $errorMiddleware = $app->addErrorMiddleware(
            true,
            true,
            true
        );

        $errorMiddleware->setDefaultErrorHandler(
            function (
                ServerRequestInterface $request,
                \Throwable $exception,
                bool $displayErrorDetails
            ): ResponseInterface {

                $statusCode = match (true) {
                    $exception instanceof HttpBadRequestException => 400,
                    $exception instanceof HttpUnauthorizedException => 401,
                    $exception instanceof HttpForbiddenException => 403,
                    $exception instanceof HttpNotFoundException => 404,
                    $exception instanceof HttpMethodNotAllowedException => 405,
                    $exception instanceof HttpInternalServerErrorException => 500,
                    $exception instanceof HttpException => 500,
                    default => 500,
                };

                $controller = new ErrorController();

                return $controller->redirectToErrorPage(
                    $request,
                    $statusCode
                );
            }
        );

        return $errorMiddleware;
    }
}