<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BastienJcln\SwissCooking\Models\Recipe;
use BastienJcln\SwissCooking\Models\User;

class HomeController extends BaseController
{
    public function index(Request $request, Response $response): Response
    {
        $recipes = Recipe::getAllRecipes();

        $sorted = usort($recipes, function ($a, $b) {return $b->averageRating <=> $a->averageRating;});

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

        if (isset($_SESSION['user_id'])) {
            $users[$_SESSION['user_id']] = User::findById(
                $_SESSION['user_id'] ?? null
            );
        }

        return $this->view->render($response, 'home/index.php', [
            'recipes' => $recipes,
            'users'   => $users,
        ]);
    }
}
