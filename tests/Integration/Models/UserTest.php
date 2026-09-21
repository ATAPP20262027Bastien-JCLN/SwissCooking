<?php

declare(strict_types=1);

use BastienJcln\SwissCooking\Models\User;

test('findByEmail finds a user', function () {
    $pdo = \BastienJcln\SwissCooking\Core\Database::connection();

    $email = 'pest-' . uniqid() . '@example.com';

    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, password_hash, id_role)
         VALUES (:name, :email, :password_hash, :id_role)'
    );

    $stmt->execute([
        'name' => 'Pest User',
        'email' => $email,
        'password_hash' => password_hash('password', PASSWORD_DEFAULT),
        'id_role' => 1,
    ]);

    $user = User::findByEmail($email);

    expect($user)->not->toBeNull();
    expect($user->email)->toBe($email);
});