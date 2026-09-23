<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use BastienJcln\SwissCooking\Models\Recipe;
use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Models\Category;
use BastienJcln\SwissCooking\Services\ConnexionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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

        $users[$_SESSION['user_id']] = User::findById($_SESSION['user_id'] ?? null);

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

        $queryParams = $request->getQueryParams();

        $search = trim($queryParams['search'] ?? '');

        $fromCategory = isset($queryParams['fromCategory'])
            && $queryParams['fromCategory'] === '1';

        if ($search !== '') {
            $recipes = Recipe::searchRecipes($search);
        } else {
            $recipes = Recipe::getAllRecipes();
        }

        $users = [];

        foreach ($recipes as $recipe) {
            $users[$recipe->id] = User::findById($recipe->user_id);
        }

        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return $this->view->render($response, 'home/search.php', [
                'withMenu' => false,
                'recipes' => $recipes,
                'users' => $users,
            ]);
        }

        return $this->view->render($response, 'home/list.php', [
            'fromCategory' => $fromCategory,
            'recipes' => $recipes,
            'users' => $users,
        ]);
    }

    public function categories(Request $request, Response $response): Response
    {

        if (!ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected(
                $request,
                $response
            );
        }

        $categories = Category::getAllCategories();

        return $this->view->render($response, 'home/categories.php', [
            'categories' => $categories,
        ]);
    }
}
