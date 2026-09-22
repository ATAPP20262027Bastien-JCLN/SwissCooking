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

        $ratings = Rating::getAverageRatingForRecipe($recipe->id);

        if (isset($recipe->id_user)) {
            $user = User::findById($recipe->id_user);
        }

        return $this->view->render($response, 'recipe/show.php', [
            'recipe' => $recipe,
            'user' => $user,
        ]);
    }
}