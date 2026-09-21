<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Slim\App;
use BastienJcln\SwissCooking\MyApp as Application;

abstract class TestCase extends BaseTestCase
{
    protected App $app;

    protected function setUp(): void
    {
        parent::setUp();

        $_SESSION = [];

        $this->app = Application::create();
    }
}