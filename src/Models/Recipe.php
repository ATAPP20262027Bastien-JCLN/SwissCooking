<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;

class Recipe extends AbstractModel
{
    protected static ?string $primaryKey = 'id';

    public ?int $id = null;

    public ?string $name = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->name = $value;
            } else {
                throw new \InvalidArgumentException('Name must be a non-empty string');
            }
        }
    }

    public ?string $description = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->description = $value;
            } else {
                throw new \InvalidArgumentException('Description must be a non-empty string');
            }
        }
    }

    public ?string $steps = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->steps = $value;
            } else {
                throw new \InvalidArgumentException('Steps must be a non-empty string');
            }
        }
    }

    public ?int $user_id = null {
        set {
            if (is_int($value) && $value > 0 && User::findById($value) !== null) {
                $this->user_id = $value;
            } else {
                throw new \InvalidArgumentException('User ID must be a positive integer');
            }
        }
    }

    public ?int $category_id = null {
        set {
            if (is_int($value) && $value > 0 && Category::findById($value) !== null) {
                $this->category_id = $value;
            } else {
                throw new \InvalidArgumentException('Category ID must be a positive integer');
            }
        }
    }

    public array $ingredients = []{
        set {
            if (! is_array($value) || count($value) === 0) {
                throw new \InvalidArgumentException('Ingredients must be a non-empty array');
            }

            foreach ($value as $ingredient) {
                if (! $ingredient instanceof Ingredient) {
                    throw new \InvalidArgumentException('All ingredients must be instances of Ingredient');
                }
            }

            $this->ingredients = $value;
        }
    }

    public ?float $averageRating = null {
        set {
            if ($value !== null && (! is_float($value) || $value < 0 || $value > 5)) {
                throw new \InvalidArgumentException('Average rating must be a float between 0 and 5 or null');
            }
            $this->averageRating = $value;
        }
    }

    public ?string $category = null {
        set {
            if ($value !== null && (! is_string($value) || strlen($value) === 0)) {
                throw new \InvalidArgumentException('Category must be a non-empty string or null');
            }
            $this->category = $value;
        }
    }

    public array $comments = []{
        set {
            if (! is_array($value)) {
                throw new \InvalidArgumentException('Comments must be an array');
            }
            $this->comments = $value;
        }
    }

    public static function getAllRecipes(): array
    {
        $pdo  = Database::connection();
        $stmt = $pdo->query('SELECT * FROM recipes');

        $ingredientsStmt = $pdo->prepare(
            'SELECT i.*, ri.quantity, ri.unit
             FROM ingredients i
             JOIN recipe_ingredients ri
                 ON i.id = ri.ingredient_id
             WHERE ri.recipe_id = :recipe_id'
        );

        $recipes = [];

        while ($recipe = $stmt->fetchObject(self::class)) {
            $ingredientsStmt->execute([
                'recipe_id' => $recipe->id,
            ]);

            $recipe->category = Category::getCategoryNameById($recipe->category_id);

            $recipe->ingredients = $ingredientsStmt->fetchAll(
                \PDO::FETCH_CLASS,
                Ingredient::class
            );

            $recipe->averageRating = Rating::getAverageRatingForRecipe($recipe->id);
            $recipe->comments      = Comment::getCommentsForRecipe($recipe->id);

            $recipes[] = $recipe;
        }

        return $recipes;
    }

    public static function findById(int $id): ?self
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT * FROM recipes WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);

        $recipe = $stmt->fetchObject(self::class);

        if (! $recipe) {
            return null;
        }

        $ingredientsStmt = $pdo->prepare(
            'SELECT i.*, ri.quantity, ri.unit
             FROM ingredients i
             JOIN recipe_ingredients ri
                 ON i.id = ri.ingredient_id
             WHERE ri.recipe_id = :recipe_id'
        );

        $ingredientsStmt->execute([
            'recipe_id' => $recipe->id,
        ]);

        $recipe->category = Category::getCategoryNameById($recipe->category_id);

        $recipe->ingredients = $ingredientsStmt->fetchAll(
            \PDO::FETCH_CLASS,
            Ingredient::class
        );

        $recipe->averageRating = Rating::getAverageRatingForRecipe($recipe->id);
        $recipe->comments      = Comment::getCommentsForRecipe($recipe->id);

        return $recipe;
    }

    public static function searchRecipes(string $search): array
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT recipes.*
             FROM recipes
             JOIN users
                 ON recipes.user_id = users.id
             WHERE recipes.name LIKE :name_search
                OR recipes.category_id IN (
                    SELECT id
                    FROM categories
                    WHERE name LIKE :category_search
                )
                OR users.name LIKE :user_search'
        );

        $searchValue = '%' . $search . '%';

        $stmt->execute([
            'name_search'     => $searchValue,
            'category_search' => $searchValue,
            'user_search'     => $searchValue,
        ]);

        $ingredientsStmt = $pdo->prepare(
            'SELECT i.*, ri.quantity, ri.unit
             FROM ingredients i
             JOIN recipe_ingredients ri
                 ON i.id = ri.ingredient_id
             WHERE ri.recipe_id = :recipe_id'
        );

        $recipes = [];

        while ($recipe = $stmt->fetchObject(self::class)) {
            $ingredientsStmt->execute([
                'recipe_id' => $recipe->id,
            ]);

            $recipe->category = Category::getCategoryNameById($recipe->category_id);

            $recipe->ingredients = $ingredientsStmt->fetchAll(
                \PDO::FETCH_CLASS,
                Ingredient::class
            );

            $recipe->averageRating = Rating::getAverageRatingForRecipe($recipe->id);
            $recipe->comments      = Comment::getCommentsForRecipe($recipe->id);

            $recipes[] = $recipe;
        }

        return $recipes;
    }

    public static function getRecipesByUserId(int $userId): array
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT *
         FROM recipes
         WHERE user_id = :user_id
         ORDER BY id DESC'
        );

        $stmt->execute([
            'user_id' => $userId,
        ]);

        $recipes = [];

        while ($recipe = $stmt->fetchObject(self::class)) {
            $recipe->category = Category::getCategoryNameById($recipe->category_id);

            $ingredientsStmt = $pdo->prepare(
                'SELECT i.*, ri.quantity, ri.unit
                 FROM ingredients i
                 JOIN recipe_ingredients ri
                     ON i.id = ri.ingredient_id
                 WHERE ri.recipe_id = :recipe_id'
            );

            $ingredientsStmt->execute([
                'recipe_id' => $recipe->id,
            ]);

            $recipe->ingredients = $ingredientsStmt->fetchAll(
                \PDO::FETCH_CLASS,
                Ingredient::class
            );

            $recipe->averageRating = Rating::getAverageRatingForRecipe($recipe->id);
            $recipe->comments      = Comment::getCommentsForRecipe($recipe->id);

            $recipes[] = $recipe;
        }

        return $recipes;
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            throw new \LogicException('Cannot delete a recipe without an ID');
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'DELETE FROM recipes WHERE id = :id'
        );

        return $stmt->execute(['id' => $this->id]);
    }

    public function addIngredients(array $ingredients): bool
    {
        if ($this->id === null) {
            throw new \LogicException('Cannot add ingredients to a recipe without an ID');
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit)
            VALUES (:recipe_id, :ingredient_id, :quantity, :unit)'
        );

        foreach ($ingredients as $ingredient) {
            $stmt->execute([
                'recipe_id'     => $this->id,
                'ingredient_id' => $ingredient['ingredient_id'],
                'quantity'      => $ingredient['quantity'],
                'unit'          => $ingredient['unit'],
            ]);
        }

        return true;
    }

    public function deleteIngredients(): bool
    {
        $pdo = Database::connection();

        $sql = 'DELETE FROM recipe_ingredients WHERE recipe_id = :recipe_id ';

        $statement = $pdo->prepare($sql);

        return $statement->execute([
            'recipe_id' => $this->id,
        ]);
    }

    #[Override]
    public function insert(): bool
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO recipes (name, description, steps, user_id, category_id )
            VALUES (:name, :description, :steps, :user_id, :category_id)'
        );

        $success = $stmt->execute([
            'name'        => $this->name,
            'description' => $this->description,
            'steps'       => $this->steps,
            'user_id'     => $this->user_id,
            'category_id' => $this->category_id,
        ]);

        if ($success) {
            $this->id = (int) $pdo->lastInsertId();
        }

        return $success;
    }

    #[Override]
    public function update(): bool
    {
        if ($this->id === null) {
            throw new \LogicException('Cannot update a recipe without an ID');
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'UPDATE recipes
             SET name = :name,
                 description = :description,
                 steps = :steps,
                 user_id = :user_id,
                 category_id = :category_id
             WHERE id = :id'
        );

        return $stmt->execute([
            'name'        => $this->name,
            'description' => $this->description,
            'steps'       => $this->steps,
            'user_id'     => $this->user_id,
            'category_id' => $this->category_id,
            'id'          => $this->id,
        ]);
    }
}
