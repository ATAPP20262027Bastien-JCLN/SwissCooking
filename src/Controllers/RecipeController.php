<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Controllers;

use BastienJcln\SwissCooking\Core\Database;
use BastienJcln\SwissCooking\Models\Category;
use BastienJcln\SwissCooking\Models\Ingredient;
use BastienJcln\SwissCooking\Models\Recipe;
use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Services\ConnexionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RecipeController extends BaseController
{
    public function show(
        Request $request,
        Response $response,
        array $args
    ): Response {
        if (! ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected(
                $request,
                $response
            );
        }

        $id     = (int) ($args['id'] ?? 0);
        $recipe = Recipe::findById($id);

        if (! $recipe) {
            return $response
                ->withHeader('Location', '/404')
                ->withStatus(302);
        }

        $user = null;

        if (isset($recipe->user_id)) {
            $user = User::findById($recipe->user_id);
        }

        return $this->view->render(
            $response,
            'recipe/show.php',
            [
                'recipe' => $recipe,
                'user'   => $user,
            ]
        );
    }

    public function list(
        Request $request,
        Response $response
    ): Response {
        if (! ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected(
                $request,
                $response
            );
        }

        $queryParams = $request->getQueryParams();
        $bodyParams  = $request->getParsedBody();

        $search = trim($queryParams['search'] ?? '');

        if (! empty($bodyParams['category'])) {
            $_SESSION['category']     = trim($bodyParams['category']);
            $_SESSION['fromCategory'] = true;

            $search = $_SESSION['category'];
        }

        if (
            empty($search) &&
            ! empty($_SESSION['fromCategory']) &&
            ! empty($_SESSION['category'])
        ) {
            $search = $_SESSION['category'];
        }

        $fromCategory = ! empty($_SESSION['fromCategory']);

        if ($search !== '') {
            $recipes = Recipe::searchRecipes($search);
        } else {
            $recipes = Recipe::getAllRecipes();
        }

        $users = [];

        foreach ($recipes as $recipe) {
            $users[$recipe->id] = User::findById(
                $recipe->user_id
            );
        }

        if (
            $request->getHeaderLine('X-Requested-With') ===
            'XMLHttpRequest'
        ) {
            return $this->view->render(
                $response,
                'home/search.php',
                [
                    'withMenu' => false,
                    'recipes'  => $recipes,
                    'users'    => $users,
                ]
            );
        }

        return $this->view->render(
            $response,
            'home/list.php',
            [
                'fromCategory' => $fromCategory,
                'recipes'      => $recipes,
                'users'        => $users,
            ]
        );
    }

    public function create(
        Request $request,
        Response $response
    ): Response {
        if (! ConnexionService::connectedUser()) {
            return ConnexionService::redirectIfNotConnected(
                $request,
                $response
            );
        }

        $_SESSION['fromCategory'] = false;

        return $this->view->render(
            $response,
            'recipe/create.php',
            [
                'fromCategory' => false,
                'categories'   => Category::getAllCategories(),
                'ingredients'  => Ingredient::getAllIngredients(),
            ]
        );
    }

    public function store(
        Request $request,
        Response $response
    ): Response {
        $user = ConnexionService::connectedUser();

        if ($user === null) {
            return ConnexionService::redirectIfNotConnected(
                $request,
                $response
            );
        }

        $data = (array) $request->getParsedBody();

        $name        = trim((string) ($data['name'] ?? ''));
        $description = trim(
            (string) ($data['description'] ?? '')
        );
        $categoryId = (int) ($data['category_id'] ?? 0);

        $categories  = Category::getAllCategories();
        $ingredients = Ingredient::getAllIngredients();

        if ($name === '') {
            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'Recipe name is required.',
                $data
            );
        }

        if (strlen($name) > 255) {
            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'Recipe name cannot exceed 255 characters.',
                $data
            );
        }

        if ($description === '') {
            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'Recipe description is required.',
                $data
            );
        }

        $steps = $data['steps'] ?? [];

        if (! is_array($steps)) {
            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'Invalid instruction data.',
                $data
            );
        }

        $cleanSteps = [];

        foreach ($steps as $step) {
            $step = trim((string) $step);

            if ($step !== '') {
                $cleanSteps[] = $step;
            }
        }

        if (count($cleanSteps) === 0) {
            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'Please add at least one cooking step.',
                $data
            );
        }

        $steps = implode('|', $cleanSteps);

        if (Category::findById($categoryId) === null) {
            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'Please select a valid category.',
                $data
            );
        }

        $ingredientIds = $data['ingredient_id'] ?? [];
        $quantities    = $data['quantity'] ?? [];
        $units         = $data['unit'] ?? [];

        if (
            ! is_array($ingredientIds) ||
            ! is_array($quantities) ||
            ! is_array($units)
        ) {
            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'Invalid ingredient data.',
                $data
            );
        }

        if (count($ingredientIds) === 0) {
            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'Please add at least one ingredient.',
                $data
            );
        }

        $recipeIngredients = [];
        $usedIngredients   = [];

        foreach ($ingredientIds as $index => $ingredientId) {
            $ingredientId = (int) $ingredientId;

            if ($ingredientId <= 0) {
                return $this->renderCreateForm(
                    $response,
                    $categories,
                    $ingredients,
                    'Please select a valid ingredient.',
                    $data
                );
            }

            if (isset($usedIngredients[$ingredientId])) {
                return $this->renderCreateForm(
                    $response,
                    $categories,
                    $ingredients,
                    'The same ingredient cannot be added twice.',
                    $data
                );
            }

            if (Ingredient::findById($ingredientId) === null) {
                return $this->renderCreateForm(
                    $response,
                    $categories,
                    $ingredients,
                    'One of the selected ingredients does not exist.',
                    $data
                );
            }

            $quantity = trim(
                (string) ($quantities[$index] ?? '')
            );

            $unit = trim(
                (string) ($units[$index] ?? '')
            );

            if ($quantity === '') {
                return $this->renderCreateForm(
                    $response,
                    $categories,
                    $ingredients,
                    'Every ingredient must have a quantity.',
                    $data
                );
            }

            if (
                ! is_numeric($quantity) ||
                (float) $quantity <= 0
            ) {
                return $this->renderCreateForm(
                    $response,
                    $categories,
                    $ingredients,
                    'Ingredient quantities must be positive numbers.',
                    $data
                );
            }

            if ($unit === '') {
                return $this->renderCreateForm(
                    $response,
                    $categories,
                    $ingredients,
                    'Every ingredient must have a unit.',
                    $data
                );
            }

            if (strlen($unit) > 100) {
                return $this->renderCreateForm(
                    $response,
                    $categories,
                    $ingredients,
                    'Ingredient units cannot exceed 100 characters.',
                    $data
                );
            }

            $usedIngredients[$ingredientId] = true;

            $recipeIngredients[] = [
                'ingredient_id' => $ingredientId,
                'quantity'      => (float) $quantity,
                'unit'          => $unit,
            ];
        }

        $pdo = Database::connection();

        try {
            $pdo->beginTransaction();

            $recipe              = new Recipe();
            $recipe->name        = $name;
            $recipe->description = $description;
            $recipe->steps       = $steps;
            $recipe->user_id     = $user->id;
            $recipe->category_id = $categoryId;

            if (! $recipe->insert()) {
                throw new \RuntimeException(
                    'Could not create recipe'
                );
            }

            $recipe->addIngredients($recipeIngredients);

            $pdo->commit();

            return $response
                ->withHeader(
                    'Location',
                    '/recipe/' . $recipe->id
                )
                ->withStatus(302);
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->renderCreateForm(
                $response,
                $categories,
                $ingredients,
                'An error occurred while creating the recipe.',
                $data
            );
        }
    }

    private function renderCreateForm(
        Response $response,
        array $categories,
        array $ingredients,
        string $error,
        array $data
    ): Response {
        return $this->view->render(
            $response,
            'recipe/create.php',
            [
                'categories'  => $categories,
                'ingredients' => $ingredients,
                'error'       => $error,
                'data'        => $data,
            ]
        );
    }
}
