<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Models;

use BastienJcln\SwissCooking\Core\Database;
use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Models\Recipe;
use Override;

class Comment extends AbstractModel
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

    public ?string $content = null {
        set {
            if (is_string($value) && strlen($value) > 0) {
                $this->content = $value;
            } else {
                throw new \InvalidArgumentException("Content must be a non-empty string");
            }
        }
    }

    public \DateTime|string|null $created_at = null {
        get {
            return $this->created_at instanceof \DateTime ? $this->created_at->format('Y-m-d H:i:s') : $this->created_at;
        }
        set {
            if (is_string($value)) {
                $this->created_at = new \DateTime($value);
            } elseif ($value instanceof \DateTime || $value === null) {
                $this->created_at = $value;
            } else {
                throw new \InvalidArgumentException(
                    'Invalid created_at value'
                );
            }
        }
    }

    public \DateTime|string|null $updated_at = null {
        get {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format('Y-m-d H:i:s') : $this->updated_at;
        }
        set {
            if (is_string($value)) {
                $this->updated_at = new \DateTime($value);
            } elseif ($value instanceof \DateTime || $value === null) {
                $this->updated_at = $value;
            } else {
                throw new \InvalidArgumentException(
                    'Invalid updated_at value'
                );
            }
        }
    }

    protected array $casts = [
        "created_at" => 'datetime',
        "updated_at" => 'datetime',
    ];

    public static function getAllComments(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT * FROM comments");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
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
