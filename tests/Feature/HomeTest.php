<?php

declare(strict_types=1);

use Slim\Psr7\Factory\ServerRequestFactory;

test('home page returns 302', function () {
    $request = (new ServerRequestFactory())
        ->createServerRequest('GET', '/');

    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(302);
});