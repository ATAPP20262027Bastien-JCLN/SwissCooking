<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use BastienJcln\SwissCooking\Models\Recipe;
use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Models\Rating;
use BastienJcln\SwissCooking\Services\ConnexionService;

class RecipeController extends BaseController
{
    public function show(Request $request, Response $response, array $args): Response
    {
        if (!ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected($request, $response);
        }

        $id = (int) ($args['id'] ?? 0);

        $recipe = Recipe::findById($id);

        if (!$recipe) {
            return $response
                ->withHeader('Location', '/404')
                ->withStatus(302);
        }

        $user = null;

        if (isset($recipe->user_id)) {
            $user = User::findById($recipe->user_id);
        }

        return $this->view->render($response, 'recipe/show.php', [
            'recipe' => $recipe,
            'user' => $user,
        ]);
    }
}