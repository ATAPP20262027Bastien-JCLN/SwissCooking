<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Controllers;

use BastienJcln\SwissCooking\Models\Category;
use BastienJcln\SwissCooking\Models\Ingredient;
use BastienJcln\SwissCooking\Models\Recipe;
use BastienJcln\SwissCooking\Models\Role;
use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Services\AdminService;
use BastienJcln\SwissCooking\Services\ConnexionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AdminController extends BaseController
{
    public function __construct(
        private AdminService $adminService,
        private ConnexionService $connexionService
    ) {
        parent::__construct();
    }

    private function authorize(
        ResponseInterface $response
    ): ?ResponseInterface {
        $user = $this->connexionService->connectedUser();

        if (! $this->adminService->isAdmin($user)) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        return null;
    }

    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        
        $cards = [
            [
                'title' => 'Utilisateurs',
                'count' => count(User::getAllUsers()),
                'url' => '/admin/users',
                'icon' => 'bi-people'
            ],
            [
                'title' => 'Recettes',
                'count' => count(Recipe::getAllRecipes(true)),
                'url' => '/admin/recipes',
                'icon' => 'bi-journal-text'
            ],
            [
                'title' => 'Ingrédients',
                'count' => count(Ingredient::getAllIngredients()),
                'url' => '/admin/ingredients',
                'icon' => 'bi-basket'
            ],
            [
                'title' => 'Catégories',
                'count' => count(Category::getAllCategories()),
                'url' => '/admin/categories',
                'icon' => 'bi-tags'
            ],
        ];

        return $this->view->render($response, 'admin/dashboard.php', [
            'cards'           => $cards,
        ]);
    }

    public function users(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        $users = User::getAllUsers();

        $currentUser = $this->connexionService->connectedUser();

        $lockedUserFromPermission = [];

        
                    // $lockedUserFromPermission[]['modify'] = $user->id;
                    // $lockedUserFromPermission[]['delete'] = $user->id;
                    // $lockedUserFromPermission[]['block'] = $user->id;

        if ($currentUser !== null) {
            foreach ($users as $user) {
                if ($user->id === $currentUser->id) {
                    $lockedUserFromPermission[$user->id] = [
                        'modify' => false,
                        'delete' => false,
                        'block'  => false,
                    ];
                }
                if ($user->id_role === ADMIN_ROLE_ID) {
                    $lockedUserFromPermission[$user->id] = [
                        'modify' => false,
                        'delete' => false,
                        'block'  => false,
                    ];
                }
                if ($user->id_role === MODERATOR_ROLE_ID) {
                    if ($currentUser->id_role !== ADMIN_ROLE_ID) {
                        $lockedUserFromPermission[$user->id] = [
                            'modify' => false,
                            'delete' => false,
                            'block'  => false,
                        ];
                    } else {
                        $lockedUserFromPermission[$user->id] = [
                            'modify' => true,
                            'delete' => true,
                            'block'  => true,
                        ];
                    }
                }
            }
        }

        return $this->view->render($response, 'admin/users.php', [
            'users' => $users,
            'lockedUserFromPermission' => $lockedUserFromPermission,
        ]);
    }

    public function editUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        $id = filter_var(
            $args['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id < 1) {
            return $response
                ->withHeader('Location', '/admin/users')
                ->withStatus(303);
        }

        $user = User::findById($id);

        if ($user === null) {
            return $response
                ->withHeader('Location', '/admin/users?error=user-not-found')
                ->withStatus(303);
        }

        return $this->view->render($response, 'admin/edit-user.php', [
            'user'  => $user,
            'roles' => Role::getAllRoles(),
        ]);
    }

    public function updateUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        $id = filter_var(
            $args['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id < 1) {
            return $response
                ->withHeader('Location', '/admin/users?error=invalid-id')
                ->withStatus(303);
        }

        $user = User::findById($id);

        if ($user === null) {
            return $response
                ->withHeader('Location', '/admin/users?error=user-not-found')
                ->withStatus(303);
        }

        $data = (array) $request->getParsedBody();

        try {
            $name   = trim((string) ($data['name'] ?? ''));
            $email  = trim((string) ($data['email'] ?? ''));
            $roleId = filter_var(
                $data['id_role'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($name === '' || mb_strlen($name) > 100) {
                throw new \InvalidArgumentException(
                    'Name must contain between 1 and 100 characters.'
                );
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException(
                    'Please enter a valid email address.'
                );
            }

            if ($roleId === false || $roleId < 1
                || Role::findById($roleId) === null) {
                throw new \InvalidArgumentException(
                    'Please select a valid role.'
                );
            }

            $existingUser = User::findByEmail($email);

            if ($existingUser !== null && (int) $existingUser->id !== $id) {
                throw new \InvalidArgumentException(
                    'That email address is already in use.'
                );
            }

            $user->name    = $name;
            $user->email   = $email;
            $user->id_role = $roleId;

            $user->update();

            return $response
                ->withHeader('Location', '/admin/users?success=user-updated')
                ->withStatus(303);

        } catch (\InvalidArgumentException $exception) {
            return $response
                ->withHeader(
                    'Location',
                    '/admin/users/' . $id . '/edit?error=' .
                    rawurlencode($exception->getMessage())
                )
                ->withStatus(303);
        }
    }

    public function blockUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        $id = filter_var(
            $args['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id < 1) {
            return $response
                ->withHeader('Location', '/admin/users?error=invalid-id')
                ->withStatus(303);
        }

        $user = User::findById($id);

        if ($user === null) {
            return $response
                ->withHeader('Location', '/admin/users?error=user-not-found')
                ->withStatus(303);
        }

        // Toggle the blocked status
        $user->blocked = !$user->blocked;
        $user->update();

        return $response
            ->withHeader('Location', '/admin/users?success=user-updated')
            ->withStatus(303);
    }

    public function deleteUser(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        $id = filter_var(
            $args['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id < 1) {
            return $response
                ->withHeader('Location', '/admin/users?error=invalid-id')
                ->withStatus(303);
        }

        $user = User::findById($id);

        if ($user === null) {
            return $response
                ->withHeader('Location', '/admin/users?error=user-not-found')
                ->withStatus(303);
        }

        // Delete the user
        $user->delete();

        return $response
            ->withHeader('Location', '/admin/users?success=user-deleted')
            ->withStatus(303);
    }

    public function recipes(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        return $this->view->render($response, 'admin/recipes.php', [
            'recipes' => Recipe::getAllRecipes(true),
        ]);
    }

    public function ingredients(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        return $this->view->render($response, 'admin/ingredients.php', [
            'ingredients' => Ingredient::getAllIngredients(),
        ]);
    }

    public function categories(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        return $this->view->render($response, 'admin/categories.php', [
            'categories' => Category::getAllCategories(true),
            'category'   => null,
        ]);
    }

    public function createCategory(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        return $this->view->render($response, 'admin/edit-category.php', [
            'category' => null,
        ]);
    }

    public function editCategory(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        $id = filter_var(
            $args['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id < 1) {
            return $response
                ->withHeader('Location', '/admin/categories')
                ->withStatus(303);
        }

        if ($id === 1) {
            return $response
                ->withHeader(
                    'Location',
                    '/admin/categories?error=categorie-par-defaut-ne-peut-pas-etre-modifiee'
                )
                ->withStatus(303);
        }

        $category = Category::findById($id);

        if ($category === null) {
            return $response
                ->withHeader('Location', '/admin/categories')
                ->withStatus(303);
        }

        return $this->view->render($response, 'admin/edit-category.php', [
            'category' => $category,
        ]);
    }

    public function saveCategory(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        $data = (array) $request->getParsedBody();

        try {
            $rawId = $data['id'] ?? '';

            $id = $rawId === ''
                ? null
                : filter_var($rawId, FILTER_VALIDATE_INT);

            if ($id === false || ($id !== null && $id < 1)) {
                throw new \InvalidArgumentException('Invalid category ID.');
            }

            $category = $id === null
                ? new Category()
                : Category::findById($id);

            if ($category === null) {
                throw new \InvalidArgumentException('Category not found.');
            }

            $category->name = trim((string) ($data['name'] ?? ''));

            $description = trim(
                (string) ($data['description'] ?? '')
            );

            $category->description = $description !== ''
                ? $description
                : null;

            if ($id === null) {
                $category->insert();
            } else {
                $category->update();
            }

            return $response
                ->withHeader('Location', '/admin/categories?success=1')
                ->withStatus(303);
        } catch (\InvalidArgumentException $exception) {
            return $response
                ->withHeader(
                    'Location',
                    '/admin/categories?error=' .
                    rawurlencode($exception->getMessage())
                )
                ->withStatus(303);
        }
    }

    public function deleteCategory(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        if ($redirect = $this->authorize($response)) {
            return $redirect;
        }

        $id = filter_var(
            $args['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id < 1) {
            return $response
                ->withHeader('Location', '/admin/categories?error=invalid-id')
                ->withStatus(303);
        }

        if ($id === 1) {
            return $response
                ->withHeader(
                    'Location',
                    '/admin/categories?error=categorie-par-defaut-ne-peut-pas-etre-supprimee'
                )
                ->withStatus(303);
        }

        $category = Category::findById($id);

        if ($category !== null) {
            $category->delete();
        }

        return $response
            ->withHeader('Location', '/admin/categories?success=deleted')
            ->withStatus(303);
    }
}
