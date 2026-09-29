<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use BastienJcln\SwissCooking\Services\ConnexionService;

class UserController extends BaseController
{
    public function logout(Request $request, Response $response): Response
    {
        return ConnexionService::logout($request, $response);
    }
}