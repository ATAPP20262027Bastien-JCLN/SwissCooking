<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Core;

require_once __DIR__ . '/../../config/database.php';

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    public static function connection(): PDO
    {
        static $pdo = null;

        if ($pdo === null) {
            try {
                $dsn = 'mysql:host=' . DB_HOST
                    . ';dbname=' . DB_NAME
                    . ';charset=' . DB_CHARSET;

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                $pdo = new PDO(
                    $dsn,
                    DB_USER,
                    DB_PASSWORD,
                    $options
                );
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());

                throw new RuntimeException(
                    'Database connection failed.',
                    0,
                    $e
                );
            }
        }

        return $pdo;
    }
}