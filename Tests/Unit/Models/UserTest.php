<?php

declare(strict_types=1);

use BastienJcln\SwissCooking\Models\User;

test('user accepts a valid name', function () {
    $user = new User();

    $user->name = 'Bastien';

    expect($user->name)->toBe('Bastien');
});

test('user rejects an empty name', function () {
    $user = new User();

    $user->name = '';
})->throws(InvalidArgumentException::class, 'Name must be a non-empty string');

test('user accepts a valid email', function () {
    $user = new User();

    $user->email = 'test@example.com';

    expect($user->email)->toBe('test@example.com');
});

test('user rejects an invalid email', function () {
    $user = new User();

    $user->email = 'not-an-email';
})->throws(InvalidArgumentException::class, 'Invalid email format');

test('user accepts a password hash', function () {
    $user = new User();

    $user->password_hash = password_hash('password', PASSWORD_DEFAULT);

    expect($user->password_hash)->not->toBeEmpty();
});

test('user rejects an empty password hash', function () {
    $user = new User();

    $user->password_hash = '';
})->throws(InvalidArgumentException::class, 'Password must be a non-empty string');

test('password hash can be verified', function () {
    $password = 'secret-password';

    $hash = password_hash($password, PASSWORD_DEFAULT);

    expect(password_verify($password, $hash))
        ->toBeTrue();
});

test('wrong password does not verify', function () {
    $password = 'secret-password';

    $hash = password_hash($password, PASSWORD_DEFAULT);

    expect(password_verify('wrong-password', $hash))
        ->toBeFalse();
});