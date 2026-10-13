<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;
use PDO;

class Category extends AbstractModel
{
    protected static ?string $primaryKey = 'id';

    public ?int $id = null;
    public ?string $name = null;
    public ?string $description = null;

    public static function getAllCategories(bool $byid = false): array
    {
        $pdo = Database::connection();

        if ($byid) {
            $stmt = $pdo->query(
                'SELECT * FROM categories ORDER BY id ASC'
            );
        } else {
            $stmt = $pdo->query(
                'SELECT * FROM categories ORDER BY name ASC'
            );
        }
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function findById(int $id): ?self
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT * FROM categories WHERE id = :id'
        );

        $stmt->execute(['id' => $id]);

        $category = $stmt->fetchObject(self::class);

        return $category ?: null;
    }

    public static function getCategoryNameById(int $id): ?string
    {
        return self::findById($id)?->name;
    }

    public function insert(): bool
    {
        $this->validate();

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO categories (name, description)
             VALUES (:name, :description)'
        );

        $success = $stmt->execute([
            'name' => trim($this->name),
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
                'Cannot update a category without an ID'
            );
        }

        $this->validate();

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'UPDATE categories
             SET name = :name, description = :description
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $this->id,
            'name' => trim($this->name),
            'description' => $this->description,
        ]);
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            throw new \LogicException(
                'Cannot delete a category without an ID'
            );
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'DELETE FROM categories WHERE id = :id'
        );

        return $stmt->execute(['id' => $this->id]);
    }

    private function validate(): void
    {
        if (
            $this->name === null
            || trim($this->name) === ''
            || mb_strlen(trim($this->name)) > 255
        ) {
            throw new \InvalidArgumentException(
                'Category name must contain between 1 and 255 characters'
            );
        }

        if (
            $this->description !== null
            && mb_strlen($this->description) > 65535
        ) {
            throw new \InvalidArgumentException(
                'Category description is too long'
            );
        }

        if ($this->description === '') {
            $this->description = null;
        }
    }
}
