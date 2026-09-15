<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use BastienJcln\SwissCooking\Models\User;

class ConnexionService
{
    public static function connectedUser(): ?User
    {
        if (isset($_SESSION['user_id'])) {
            return User::findById((int)$_SESSION['user_id']);
        }

        return null;
    }

    public static function redirectIfNotConnected(Request $request, Response $response): Response
    {
        if (!isset($_SESSION['user_id'])) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        return $response;
    }

    public static function redirectIfConnected(Request $request, Response $response): Response
    {
        if (isset($_SESSION['user_id'])) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        return $response;
    }

    public static function logout(Request $request, Response $response): Response
    {
        $_SESSION = [];
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}
