<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use BastienJcln\SwissCooking\Services\ConnexionService;
use BastienJcln\SwissCooking\Models\Recipe;
use BastienJcln\SwissCooking\Models\User;

class RecipeController extends BaseController
{
    public function show(
        Request $request,
        Response $response,
        array $args
    ): Response {
        if (!ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected(
                $request,
                $response
            );
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

    public function list(
        Request $request,
        Response $response
    ): Response {
        if (!ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected(
                $request,
                $response
            );
        }

        $queryParams = $request->getQueryParams();
        $bodyParams = $request->getParsedBody();

        $search = trim($queryParams['search'] ?? '');

        if (!empty($bodyParams['category'])) {
            $_SESSION['category'] = trim($bodyParams['category']);
            $_SESSION['fromCategory'] = true;

            $search = $_SESSION['category'];
        }

        if (
            empty($search) &&
            !empty($_SESSION['fromCategory']) &&
            !empty($_SESSION['category'])
        ) {
            $search = $_SESSION['category'];
        }

        $fromCategory = !empty($_SESSION['fromCategory']);

        if ($search !== '') {
            $recipes = Recipe::searchRecipes($search);
        } else {
            $recipes = Recipe::getAllRecipes();
        }

        $users = [];

        foreach ($recipes as $recipe) {
            $users[$recipe->id] = User::findById($recipe->user_id);
        }

        if (
            $request->getHeaderLine('X-Requested-With') ===
            'XMLHttpRequest'
        ) {
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
}