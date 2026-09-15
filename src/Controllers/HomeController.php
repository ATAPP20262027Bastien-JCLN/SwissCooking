<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Models\Role;
use BastienJcln\SwissCooking\Models\Recipe;
use BastienJcln\SwissCooking\Models\Category;
use BastienJcln\SwissCooking\Models\Ingerdient;
use BastienJcln\SwissCooking\Models\Rating;
use BastienJcln\SwissCooking\Models\Comment;

class HomeController extends BaseController
{
    public function index(Request $request, Response $response): Response
    {
        $roles = Role::getAllRoles();
        $users = User::getAllUsers();
        $categories = Category::getAllCategories();
        $ingerdients = Ingerdient::getAllIngerdients();
        $recipes = Recipe::getAllRecipes();
        $ratings = Rating::getAllRatings();
        $comments = Comment::getAllComments();

        return $this->view->render($response, 'home/index.php', [
            'users' => $users,
            'roles' => $roles,
            'categories' => $categories,
            'ingerdients' => $ingerdients,
            'recipes' => $recipes,
            'ratings' => $ratings,
            'comments' => $comments,
        ]);
    }
}