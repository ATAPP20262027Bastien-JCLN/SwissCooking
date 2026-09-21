<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Models\Recipe;

use BastienJcln\SwissCooking\Services\ConnexionService;

class HomeController extends BaseController
{
    public function index(Request $request, Response $response): Response
    {
        if (!ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected($request, $response);
        }

        $recipes = Recipe::getAllRecipes();

        $sorted = usort($recipes, function ($a, $b) {
            return $b->averageRating <=> $a->averageRating;
        });

        if ($sorted) {
            $recipes = array_slice($recipes, 0, 5);
        } else {
            $recipes = [];
        }

        $users = [];

        foreach ($recipes as $recipe) {
            $user = User::findById($recipe->user_id);
            if ($user) {
                $users[$recipe->id] = $user;
            }
        }

        return $this->view->render($response, 'home/index.php', [
            'recipes' => $recipes,
            'users' => $users,
        ]);
    }

    public function list(Request $request, Response $response): Response
    {
        if (!ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected($request, $response);
        }

        $recipes = Recipe::getAllRecipes();

        return $this->view->render($response, 'home/list.php', [
            'recipes' => $recipes,
        ]);
    }
}
