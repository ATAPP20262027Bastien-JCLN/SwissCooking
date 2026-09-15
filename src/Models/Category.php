<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;

class Category extends AbstractModel
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

    public static function getAllCategories(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findById(int $id): ?self
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetchObject(self::class);
        return $category ?: null;
    }

    public static function getCategoryNameById(int $id): ?string
    {
        $category = self::findById($id);
        return $category ? $category->name : null;
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