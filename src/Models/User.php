<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;

class User extends AbstractModel
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

    public ?string $email = null {
        set {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->email = $value;
            } else {
                throw new \InvalidArgumentException("Invalid email format");
            }
        }
    }

    public ?string $password_hash = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->password_hash = $value;
            } else {
                throw new \InvalidArgumentException("Password must be a non-empty string");
            }
        }
    }

    public ?int $id_role = null {
        set {
            if (is_int($value) && $value > 0 && Role::findById($value) !== null) {
                $this->id_role = $value;
            } else {
                throw new \InvalidArgumentException("Role ID must be a positive integer");
            }
        }
    }

    public array $favorite_recipes = [] {
        set {
            if (is_array($value)) {
                $this->favorite_recipes = $value;
            } else {
                throw new \InvalidArgumentException("Favorite recipes must be an array");
            }
        }
    }

    public static function getAllUsers(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findById(int $id): ?self
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetchObject(self::class);
        return $user ?: null;
    }

    public static function findByEmail(string $email): ?self
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetchObject(self::class);
        return $user ?: null;
    }

    #[Override]
    public function insert(): bool
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, id_role) VALUES (:name, :email, :password_hash, :id_role)");
        $result = $stmt->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password_hash' => $this->password_hash,
            'id_role' => $this->id_role,
        ]);

        if ($result) {
            $this->id = (int)$pdo->lastInsertId();
        }

        return $result;
    }

    #[Override]
    public function update(): bool
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password_hash = :password_hash, id_role = :id_role WHERE id = :id");
        return $stmt->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password_hash' => $this->password_hash,
            'id_role' => $this->id_role,
            'id' => $this->id,
        ]);
    }
}