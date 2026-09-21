<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Models\Category;
use BastienJcln\SwissCooking\Models\Ingredient;
use Override;

class Recipe extends AbstractModel
{
    /**
     * @var string
     */
    protected static ?string $primaryKey = 'id';

    public ?int $id = null;

    public ?string $name = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->name = $value;
            } else {
                throw new \InvalidArgumentException("Name must be a non-empty string");
            }
        }
    }

    public ?string $description = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->description = $value;
            } else {
                throw new \InvalidArgumentException("Description must be a non-empty string");
            }
        }
    }

    public ?int $user_id = null {
        set {
            if (is_int($value) && $value > 0 && User::findById($value) !== null) {
                $this->user_id = $value;
            } else {
                throw new \InvalidArgumentException("User ID must be a positive integer");
            }
        }
    }

    public ?int $category_id = null {
        set {
            if (is_int($value) && $value > 0 && Category::findById($value) !== null) {
                $this->category_id = $value;
            } else {
                throw new \InvalidArgumentException("Category ID must be a positive integer");
            }
        }
    }

    public array $ingredients = [] {
        set {
            if (!is_array($value) || count($value) === 0) {
                throw new \InvalidArgumentException(
                    'Ingredients must be a non-empty array'
                );
            }

            foreach ($value as $ingredient) {
                if (!$ingredient instanceof Ingredient) {
                    throw new \InvalidArgumentException(
                        'All ingredients must be instances of Ingredient'
                    );
                }
            }

            $this->ingredients = $value;
        }
    }

    public ?float $averageRating = null {
        set {
            if ($value !== null && (!is_float($value) || $value < 0 || $value > 5)) {
                throw new \InvalidArgumentException(
                    'Average rating must be a float between 0 and 5 or null'
                );
            }
            $this->averageRating = $value;
        }
    }

    public ?string $category = null {
        set {
            if ($value !== null && (!is_string($value) || strlen($value) === 0)) {
                throw new \InvalidArgumentException(
                    'Category must be a non-empty string or null'
                );
            }
            $this->category = $value;
        }
    }

    public static function getAllRecipes(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT * FROM recipes");

        $ingredientsStmt = $pdo->prepare(
            "SELECT i.*, ri.quantity, ri.unit FROM ingredients i
             JOIN recipe_ingredients ri ON i.id = ri.ingredient_id
             WHERE ri.recipe_id = :recipe_id"
        );

        $recipes = [];
        while ($recipe = $stmt->fetchObject(self::class)) {
            $ingredientsStmt->execute(['recipe_id' => $recipe->id]);
            $recipe->category = Category::getCategoryNameById($recipe->category_id);
            $recipe->ingredients = $ingredientsStmt->fetchAll(\PDO::FETCH_CLASS, Ingredient::class);
            $recipe->averageRating = Rating::getAverageRatingForRecipe($recipe->id);
            $recipes[] = $recipe;
        }

        return $recipes;
    }

    public static function findById(int $id): ?self
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $recipe = $stmt->fetchObject(self::class);

        $ingredientsStmt = $pdo->prepare(
            "SELECT i.*, ri.quantity, ri.unit FROM ingredients i
             JOIN recipe_ingredients ri ON i.id = ri.ingredient_id
             WHERE ri.recipe_id = :recipe_id"
        );

        if ($recipe) {
            $ingredientsStmt->execute(['recipe_id' => $recipe->id]);
            $recipe->category = Category::getCategoryNameById($recipe->category_id);
            $recipe->ingredients = $ingredientsStmt->fetchAll(\PDO::FETCH_CLASS, Ingredient::class);
            $recipe->averageRating = Rating::getAverageRatingForRecipe($recipe->id);
        }

        return $recipe;
    }

    #[Override]
    public function insert(): bool
    {
        throw new \Exception('Not implemented');
    }
    
    #[Override]
    public function update(): bool
    {
        throw new \Exception('Not implemented');
    }
}
