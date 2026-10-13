<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Controllers;

use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Services\ConnexionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController extends BaseController
{
    public function showLogin(Request $request, Response $response): Response
    {
        if (ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfConnected($request, $response);
        }

        return $this->view->render($response, 'connexion/login.php', []);
    }

    public function login(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();

        $email    = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = User::findByEmail($email);

        if ($user && $user->blocked) {
            $_SESSION['error'] = 'Your account has been blocked. Please contact support.';
            return $this->view->render($response, 'connexion/login.php', [
                'error' => 'Your account has been blocked. Please contact support.',
            ])->withStatus(200);
        }

        if ($user && password_verify($password, $user->password_hash)) {
            ConnexionService::loginUser($user);
            $_SESSION['error'] = null;

            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $_SESSION['error'] = 'Invalid email or password';

        return $this->view->render($response, 'connexion/login.php', [
            'error' => 'Invalid email or password',
        ])->withStatus(200);
    }

    public function showRegistration(Request $request, Response $response): Response
    {
        if (ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfConnected($request, $response);
        }

        return $this->view->render($response, 'connexion/registration.php', []);
    }

    public function register(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();

        $name     = $data['name'] ?? '';
        $email    = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (User::findByEmail($email)) {
            return $this->view->render($response, 'connexion/registration.php', [
                'error' => 'Email already exists',
            ])->withStatus(200);
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->view->render($response, 'connexion/registration.php', [
                'error' => 'Invalid email format',
            ])->withStatus(200);
        }

        if (strlen($name) > 100) {
            return $this->view->render($response, 'connexion/registration.php', [
                'error' => 'Name cannot exceed 100 characters',
            ])->withStatus(200);
        }

        if (strlen($email) > 255) {
            return $this->view->render($response, 'connexion/registration.php', [
                'error' => 'Email cannot exceed 255 characters',
            ])->withStatus(200);
        }

        $user                = new User();
        $user->name          = $name;
        $user->email         = $email;
        $user->password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user->blocked       = false;
        $user->id_role       = 2;

        if ($user->save()) {
            ConnexionService::loginUser($user);
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $_SESSION['error'] = 'Registration failed';

        return $this->view->render($response, 'connexion/registration.php', [
            'error' => 'Registration failed',
        ])->withStatus(200);
    }
}
