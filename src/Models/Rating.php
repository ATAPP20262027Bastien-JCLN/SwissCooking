<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;

class Rating extends AbstractModel
{
    /**
     * @var string
     */
    protected static ?string $primaryKey = 'id';

    public ?int $id = null;

    public ?int $user_id = null {
        set {
            if (is_int($value) && $value > 0 && User::findById($value) !== null) {
                $this->user_id = $value;
            } else {
                throw new \InvalidArgumentException("User ID must be a positive integer");
            }
        }
    }

    public ?int $recipe_id = null {
        set {
            if (is_int($value) && $value > 0 && Recipe::findById($value) !== null) {
                $this->recipe_id = $value;
            } else {
                throw new \InvalidArgumentException("Recipe ID must be a positive integer");
            }
        }
    }

    public ?int $score = null {
        set {
            if (is_int($value) && $value >= 1 && $value <= 5) {
                $this->score = $value;
            } else {
                throw new \InvalidArgumentException("Rating must be an integer between 1 and 5");
            }
        }
    }

    public static function getAllRatings(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT * FROM ratings");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function getAverageRatingForRecipe(int $recipeId): ?float
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT AVG(score) as average_rating FROM ratings WHERE recipe_id = :recipe_id");
        $stmt->execute(['recipe_id' => $recipeId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result && isset($result['average_rating']) ? (float)$result['average_rating'] : null;
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