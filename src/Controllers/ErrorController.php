<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class ErrorController extends BaseController
{
    public function redirectToErrorPage(
        ServerRequestInterface $request,
        int $statusCode
    ): ResponseInterface {
        $allowedCodes = [400, 401, 403, 404, 405, 500];

        if (!in_array($statusCode, $allowedCodes, true)) {
            $statusCode = 500;
        }

        return (new Response())
            ->withStatus(302)
            ->withHeader('Location', '/' . $statusCode);
    }

    public function badRequest(): ResponseInterface
    {
        return $this->renderError(400);
    }

    public function unauthorized(): ResponseInterface
    {
        return $this->renderError(401);
    }

    public function forbidden(): ResponseInterface
    {
        return $this->renderError(403);
    }

    public function notFound(): ResponseInterface
    {
        return $this->renderError(404);
    }

    public function methodNotAllowed(): ResponseInterface
    {
        return $this->renderError(405);
    }

    public function serverError(): ResponseInterface
    {
        return $this->renderError(500);
    }

    private function renderError(
        int $statusCode,
        string $customMessage = ''
    ): ResponseInterface {
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Page Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ];

        $messages = [
            400 => 'The request could not be understood.',
            401 => 'You must be authenticated to access this page.',
            403 => 'You do not have permission to access this page.',
            404 => 'The page you are looking for could not be found.',
            405 => 'This HTTP method is not allowed.',
            500 => 'Something went wrong on our server.',
        ];

        $description = [
            400 => 'The server could not understand the request due to invalid syntax.',
            401 => 'The client must authenticate itself to get the requested response.',
            403 => 'You do not have permission to access this page.',
            404 => 'Sorry, the page you are looking for does not exist or has been moved.',
            405 => 'The request method is known by the server but is not supported by the target resource.',
            500 => 'The server has encountered a situation it does not know how to handle.',
        ];

        if (!isset($pages[$statusCode])) {
            $statusCode = 500;
        }

        $message = $customMessage !== ''
            ? $customMessage
            : $messages[$statusCode];

        $response = new Response($statusCode);

        return $this->view->render(
            $response,
            'errors/display.php',
            [
                'withMenu'   => false,
                'title'      => $titles[$statusCode],
                'statusCode' => $statusCode,
                'message'    => $message,
                'description' => $description[$statusCode],
            ]
        );
    }
}