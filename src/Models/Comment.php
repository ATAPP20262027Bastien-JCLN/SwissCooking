<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use Override;

class Comment extends AbstractModel
{
    protected static ?string $primaryKey = 'id';

    public ?int $id = null;

    public ?int $user_id = null {
        set {
            if (is_int($value) && $value > 0) {
                $this->user_id = $value;
            } else {
                throw new \InvalidArgumentException('User ID must be a positive integer');
            }
        }
    }

    public ?int $recipe_id = null {
        set {
            if (is_int($value) && $value > 0) {
                $this->recipe_id = $value;
            } else {
                throw new \InvalidArgumentException('Recipe ID must be a positive integer');
            }
        }
    }

    public ?string $content = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->content = $value;
            } else {
                throw new \InvalidArgumentException('Content must be a non-empty string');
            }
        }
    }

    public \DateTime|string|null $created_at = null {
        get {
            return $this->created_at instanceof \DateTime  ? $this->created_at->format('Y-m-d H:i:s') : $this->created_at;
        }
        set {
            if (is_string($value)) {
                $this->created_at = new \DateTime($value);
            } elseif ($value instanceof \DateTime  || $value === null) {
                $this->created_at = $value;
            } else {
                throw new \InvalidArgumentException('Invalid created_at value');
            }
        }
    }

    public \DateTime|string|null $updated_at = null {
        get {
            return $this->updated_at instanceof \DateTime  ? $this->updated_at->format('Y-m-d H:i:s') : $this->updated_at;
        }
        set {
            if (is_string($value)) {
                $this->updated_at = new \DateTime($value);
            } elseif ($value instanceof \DateTime  || $value === null) {
                $this->updated_at = $value;
            } else {
                throw new \InvalidArgumentException('Invalid updated_at value');
            }
        }
    }

    public ?string $user_name = null {
        set {
            if ($value === null || (is_string($value) && strlen($value) <= 100)) {
                $this->user_name = $value;
            } else {
                throw new \InvalidArgumentException('Invalid user name');
            }
        }
    }

    public ?string $user_profile_picture = null {
        set {
            if (
                $value === null ||
                (is_string($value) && strlen($value) <= 2048)
            ) {
                $this->user_profile_picture = $value;
            } else {
                throw new \InvalidArgumentException('Invalid user profile picture path or URL');
            }
        }
    }

    protected array $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getAllComments(): array
    {
        $pdo  = Database::connection();
        $stmt = $pdo->query('SELECT * FROM comments');

        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            self::class
        );
    }

    public static function getCommentsForRecipe(int $recipeId): array {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT c.id, c.user_id, c.recipe_id, c.content, c.created_at, c.updated_at, u.name AS user_name, u.profile_picture AS user_profile_picture
             FROM comments c
             INNER JOIN users u ON u.id = c.user_id
             WHERE c.recipe_id = :recipe_id
             ORDER BY c.created_at DESC'
        );
        $stmt->execute([
            'recipe_id' => $recipeId,
        ]);
        
        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            self::class
        );
    }

    #[Override]
    public function insert(): bool
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO comments (user_id, recipe_id, content)
             VALUES (:user_id, :recipe_id, :content)'
        );

        return $stmt->execute([
            'user_id'   => $this->user_id,
            'recipe_id' => $this->recipe_id,
            'content'   => $this->content,
        ]);
    }

    #[Override]
    public function update(): bool
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'UPDATE comments
             SET content = :content
             WHERE id = :id'
        );

        return $stmt->execute([
            'id'      => $this->id,
            'content' => $this->content,
        ]);
    }

    public static function deleteComment(int $commentId): bool
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'DELETE FROM comments
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $commentId,
        ]);
    }
}
