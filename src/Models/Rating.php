<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;

class Rating extends AbstractModel
{
    protected static ?string $primaryKey = 'id';

    public ?int $id = null;

    public ?int $user_id = null {
        set {
            if (
                is_int($value) &&
                $value > 0 &&
                User::findById($value) !== null
            ) {
                $this->user_id = $value;
            } else {
                throw new \InvalidArgumentException(
                    'User ID must be a positive integer'
                );
            }
        }
    }

    public ?int $recipe_id = null {
        set {
            if (
                is_int($value) &&
                $value > 0 &&
                Recipe::findById($value) !== null
            ) {
                $this->recipe_id = $value;
            } else {
                throw new \InvalidArgumentException(
                    'Recipe ID must be a positive integer'
                );
            }
        }
    }

    public ?int $score = null {
        set {
            if (
                is_int($value) &&
                $value >= 1 &&
                $value <= 5
            ) {
                $this->score = $value;
            } else {
                throw new \InvalidArgumentException(
                    'Rating must be an integer between 1 and 5'
                );
            }
        }
    }

    public static function getAllRatings(): array
    {
        $pdo = Database::connection();

        $stmt = $pdo->query(
            'SELECT * FROM ratings'
        );

        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            self::class
        );
    }

    public static function getAverageRatingForRecipe(
        int $recipeId
    ): ?float {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT AVG(score) AS average_rating
             FROM ratings
             WHERE recipe_id = :recipe_id'
        );

        $stmt->execute([
            'recipe_id' => $recipeId,
        ]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result && $result['average_rating'] !== null
            ? (float) $result['average_rating']
            : null;
    }

    /**
     * Get the rating given by one user to one recipe.
     */
    public static function getUserRating(
        int $userId,
        int $recipeId
    ): ?int {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT score
             FROM ratings
             WHERE user_id = :user_id
               AND recipe_id = :recipe_id'
        );

        $stmt->execute([
            'user_id'   => $userId,
            'recipe_id' => $recipeId,
        ]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result !== false
            ? (int) $result['score']
            : null;
    }

    /**
     * Create or update a user's rating.
     */
    public static function saveRating(
        int $userId,
        int $recipeId,
        int $score
    ): bool {
        if ($score < 1 || $score > 5) {
            throw new \InvalidArgumentException(
                'Rating must be between 1 and 5.'
            );
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO ratings (
                user_id,
                recipe_id,
                score
            )
            VALUES (
                :user_id,
                :recipe_id,
                :score
            )
            ON DUPLICATE KEY UPDATE
                score = VALUES(score)'
        );

        return $stmt->execute([
            'user_id'   => $userId,
            'recipe_id' => $recipeId,
            'score'     => $score,
        ]);
    }

    /**
     * Delete a user's rating.
     */
    public static function deleteRating(
        int $userId,
        int $recipeId
    ): bool {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'DELETE FROM ratings
             WHERE user_id = :user_id
               AND recipe_id = :recipe_id'
        );

        return $stmt->execute([
            'user_id'   => $userId,
            'recipe_id' => $recipeId,
        ]);
    }

    #[Override]
    public function insert(): bool
    {
        if (
            $this->user_id === null ||
            $this->recipe_id === null ||
            $this->score === null
        ) {
            throw new \LogicException(
                'User, recipe and score are required.'
            );
        }

        return self::saveRating(
            $this->user_id,
            $this->recipe_id,
            $this->score
        );
    }

    #[Override]
    public function update(): bool
    {
        if (
            $this->user_id === null ||
            $this->recipe_id === null ||
            $this->score === null
        ) {
            throw new \LogicException(
                'User, recipe and score are required.'
            );
        }

        return self::saveRating(
            $this->user_id,
            $this->recipe_id,
            $this->score
        );
    }
}