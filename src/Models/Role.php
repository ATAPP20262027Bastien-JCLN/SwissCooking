<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;

class Role extends AbstractModel
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

    public static function getAllRoles(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT * FROM roles");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findById(int $id): ?self
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $role = $stmt->fetchObject(self::class);
        return $role ?: null;
    }

    public static function getRoleNameById(int $id): ?string
    {
        $role = self::findById($id);
        return $role ? $role->name : null;
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