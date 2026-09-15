<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;
use PDO;

class Ingerdient extends AbstractModel
{
    protected static ?string $primaryKey = 'id';

    public ?int $id = null;

    public ?string $name = null {
        set {
            if ($value !== null && trim($value) !== '') {
                $this->name = $value;
            } else {
                throw new \InvalidArgumentException(
                    'Name must be a non-empty string'
                );
            }
        }
    }

    public ?string $description = null {
        set {
            if ($value !== null && trim($value) !== '') {
                $this->description = $value;
            } else {
                throw new \InvalidArgumentException(
                    'Description must be a non-empty string'
                );
            }
        }
    }

    /**
     * Quantity of this ingredient for a specific recipe.
     *
     * This comes from recipe_ingredients, not ingredients.
     */
    public ?float $quantity = null;

    /**
     * Unit of this ingredient for a specific recipe.
     *
     * This comes from recipe_ingredients, not ingredients.
     */
    public ?string $unit = null;

    public static function getAllIngerdients(): array
    {
        $pdo = Database::connection();

        $stmt = $pdo->query(
            'SELECT * FROM ingredients'
        );

        return $stmt->fetchAll(
            PDO::FETCH_CLASS,
            self::class
        );
    }

    public static function findById(int $id): ?self
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT *
             FROM ingredients
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
        ]);

        $ingredient = $stmt->fetchObject(self::class);

        return $ingredient ?: null;
    }

    #[Override]
    public function insert(): bool
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO ingredients
                (name, description)
             VALUES
                (:name, :description)'
        );

        $success = $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
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
            throw new \LogicException(
                'Cannot update an ingredient without an ID'
            );
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'UPDATE ingredients
             SET
                name = :name,
                description = :description
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ]);
    }
}
