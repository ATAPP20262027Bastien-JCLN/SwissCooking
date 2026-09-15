<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use BastienJcln\SwissCooking\Services\ConnexionService;

use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Models\Recipe;

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
        $_SESSION['error'] = null;
        
        $data = (array)$request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->password_hash)) {
            $_SESSION['user_id'] = $user->id;
            return $response->withHeader('Location', '/')->withStatus(302);
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            return $this->view->render($response, 'connexion/login.php', [
                'error' => 'Invalid email or password',
            ]);
        }
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
        $_SESSION['error'] = null;
        
        $data = (array)$request->getParsedBody();
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (User::findByEmail($email)) {
            return $this->view->render($response, 'connexion/registration.php', [
                'error' => 'Email already exists',
            ]);
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user->id_role = 1;

        if ($user->save()) {
            $_SESSION['user_id'] = $user->id;
            return $response->withHeader('Location', '/')->withStatus(302);
        } else {
            $_SESSION['error'] = 'Registration failed';
            return $this->view->render($response, 'connexion/registration.php', [
                'error' => 'Registration failed',
            ]);
        }
    }

    public function logout(Request $request, Response $response): Response
    {
        return ConnexionService::logout($request, $response);
    }
}
