<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use BastienJcln\SwissCooking\Services\ConnexionService;
use BastienJcln\SwissCooking\Models\Category;

class CategoryController extends BaseController
{
    public function index(
        Request $request,
        Response $response
    ): Response {
        // if (!ConnexionService::connectedUser()) {
        //     return ConnexionService::redirectIfNotConnected(
        //         $request,
        //         $response
        //     );
        // }

        $categories = Category::getAllCategories();

        return $this->view->render(
            $response,
            'home/categories.php',
            [
                'categories' => $categories,
            ]
        );
    }
}