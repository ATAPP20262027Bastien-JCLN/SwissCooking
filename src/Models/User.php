<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;

class User extends AbstractModel
{
    protected static ?string $primaryKey = 'id';

    public ?int $id = null;

    public ?string $name = null {
        set {
            if (is_string($value) && strlen($value) > 0 && strlen($value) <= 100) {
                $this->name = $value;
            } else {
                throw new \InvalidArgumentException('Name must be a non-empty string with a maximum length of 100 characters');
            }
        }
    }

    public ?string $email = null {
        set {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->email = $value;
            } else {
                throw new \InvalidArgumentException('Invalid email format');
            }
        }
    }

    public ?string $password_hash = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->password_hash = $value;
            } else {
                throw new \InvalidArgumentException('Password must be a non-empty string');
            }
        }
    }

    public ?int $id_role = null {
        set {
            if (is_int($value) && $value > 0 && Role::findById($value) !== null) {
                $this->id_role = $value;
            } else {
                throw new \InvalidArgumentException('Role ID must be a positive integer');
            }
        }
    }

    public array $favorite_recipes = []{
        set {
            if (is_array($value)) {
                $this->favorite_recipes = $value;
            } else {
                throw new \InvalidArgumentException('Favorite recipes must be an array');
            }
        }
    }

    public ?string $profile_picture = null {
        set {
            if ($value === null || (is_string($value) && strlen($value) <= 2048)) {
                $this->profile_picture = $value;
            } else {
                throw new \InvalidArgumentException('Invalid profile picture path or URL');
            }
        }
    }

    public static function getAllUsers(): array
    {
        $pdo  = Database::connection();
        $stmt = $pdo->query('SELECT * FROM users');

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findById(int $id): ?self
    {
        $pdo  = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetchObject(self::class);

        return $user ?: null;
    }

    public static function findByEmail(string $email): ?self
    {
        $pdo  = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetchObject(self::class);

        return $user ?: null;
    }

    public function getProfilePicture(): string
    {
        if ($this->profile_picture !== null && $this->profile_picture !== '') {
            if (str_starts_with($this->profile_picture, 'https://') || str_starts_with($this->profile_picture, 'http://')) {
                return $this->profile_picture;
            }

            $localPath = dirname(__DIR__, 2) . '/public/' . ltrim($this->profile_picture, '/');

            if (is_file($localPath)) {
                return '/' . ltrim(
                    $this->profile_picture,
                    '/'
                );
            }
        }

        $profileDirectory = dirname(__DIR__, 2). '/public/upload/profile_pic/';

        $extensions = [
            'png',
            'jpg',
            'jpeg',
            'webp',
            'ico',
        ];

        foreach ($extensions as $extension) {
            $filename = $this->name . '_pfp.' . $extension;

            if (is_file($profileDirectory . $filename)) {
                return '/upload/profile_pic/' . $filename;
            }
        }

        return 'https://ui-avatars.com/api/?name='. urlencode($this->name ?? 'User'). '&size=256';
    }

    public function isFavorite(int $recipeId): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT id
            FROM favorites
            WHERE user_id = :user_id
            AND recipe_id = :recipe_id
            LIMIT 1'
        );

        $stmt->execute([
            'user_id'   => $this->id,
            'recipe_id' => $recipeId,
        ]);

        return $stmt->fetchColumn() !== false;
    }

    public function addFavorite(int $recipeId): bool
    {
        if ($this->id === null) {
            return false;
        }

        if ($this->isFavorite($recipeId)) {
            return true;
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO favorites (user_id, recipe_id)
            VALUES (:user_id, :recipe_id)'
        );

        return $stmt->execute([
            'user_id'   => $this->id,
            'recipe_id' => $recipeId,
        ]);
    }

    public function removeFavorite(int $recipeId): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'DELETE FROM favorites
            WHERE user_id = :user_id
            AND recipe_id = :recipe_id'
        );

        return $stmt->execute([
            'user_id'   => $this->id,
            'recipe_id' => $recipeId,
        ]);
    }

    public function getFavoriteRecipes(): array
    {
        if ($this->id === null) {
            return [];
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT r.*
            FROM recipes r
            INNER JOIN favorites f
                ON f.recipe_id = r.id
            WHERE f.user_id = :user_id
            ORDER BY f.id DESC'
        );

        $stmt->execute([
            'user_id' => $this->id,
        ]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Recipe::class);
    }

    #[Override]
    public function insert(): bool
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash, id_role, profile_picture)
            VALUES (:name, :email, :password_hash, :id_role, :profile_picture)'
        );

        $result = $stmt->execute([
            'name'            => $this->name,
            'email'           => $this->email,
            'password_hash'   => $this->password_hash,
            'id_role'         => $this->id_role,
            'profile_picture' => $this->profile_picture,
        ]);

        if ($result) {
            $this->id = (int) $pdo->lastInsertId();
        }

        return $result;
    }

    #[Override]
    public function update(): bool
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'UPDATE users
            SET
                name = :name,
                email = :email,
                password_hash = :password_hash,
                id_role = :id_role,
                profile_picture = :profile_picture
            WHERE id = :id'
        );

        return $stmt->execute([
            'name'            => $this->name,
            'email'           => $this->email,
            'password_hash'   => $this->password_hash,
            'id_role'         => $this->id_role,
            'profile_picture' => $this->profile_picture,
            'id'              => $this->id,
        ]);
    }
}
