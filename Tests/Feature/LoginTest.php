<?php

declare(strict_types=1);

use Slim\Psr7\Factory\ServerRequestFactory;

test('login page returns 200', function () {
    $request = (new ServerRequestFactory())
        ->createServerRequest('GET', '/login');

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(200);
});

test('login with invalid credentials shows an error', function () {
    $request = (new ServerRequestFactory())
        ->createServerRequest('POST', '/login')
        ->withParsedBody([
            'email' => 'wrong@example.com',
            'password' => 'wrong-password',
        ]);

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(200);
    expect($_SESSION['error'])->toBe('Invalid email or password');
});

test('register page returns 200', function () {
    $request = (new ServerRequestFactory())
        ->createServerRequest('GET', '/register');

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(200);
});

test('logout redirects to home', function () {
    $request = (new ServerRequestFactory())
        ->createServerRequest('GET', '/logout');

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(302);
    expect($response->getHeaderLine('Location'))->toBe('/');
});