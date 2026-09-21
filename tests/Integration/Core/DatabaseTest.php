<?php

declare(strict_types=1);

use BastienJcln\SwissCooking\Core\Database;
use PDO;

test('database connection works', function () {
    $pdo = Database::connection();

    expect($pdo)
        ->toBeInstanceOf(PDO::class);

    expect($pdo->query('SELECT 1')->fetchColumn())
        ->toBe(1);
});

test('database connection is reused', function () {
    $first = Database::connection();
    $second = Database::connection();

    expect($first)->toBe($second);
});