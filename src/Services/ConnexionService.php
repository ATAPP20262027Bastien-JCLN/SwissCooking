<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Services;

use BastienJcln\SwissCooking\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ConnexionService
{
    public static function connectedUser(): ?User
    {
        if (isset($_SESSION['user_id'])) {
            return User::findById((int) $_SESSION['user_id']);
        }

        return null;
    }

    public static function redirectIfNotConnected(Request $request, Response $response): Response
    {
        if (! isset($_SESSION['user_id'])) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        return $response;
    }

    public static function redirectIfConnected(Request $request, Response $response
    ): Response {
        if (isset($_SESSION['user_id'])) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        return $response;
    }

    public static function loginUser(User $user): void
    {
        $_SESSION['user_id']   = $user->id;
        $_SESSION['user_role'] = $user->id_role;
    }

    public static function logout(Request $request, Response $response): Response
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();

        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}
