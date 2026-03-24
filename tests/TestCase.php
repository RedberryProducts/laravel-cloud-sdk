<?php

namespace Redberry\LaravelCloudSdk\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Redberry\LaravelCloudSdk\LaravelCloudServiceProvider;
use Saloon\Laravel\SaloonServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SaloonServiceProvider::class,
            LaravelCloudServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        config()->set('laravel-cloud-sdk.token', 'test-token');
    }
}
