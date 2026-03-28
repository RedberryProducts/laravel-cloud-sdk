<?php

namespace Redberry\LaravelCloudSdk\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Redberry\LaravelCloudSdk\LaravelCloudServiceProvider;
use Saloon\Laravel\SaloonServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SaloonServiceProvider::class,
            LaravelDataServiceProvider::class,
            LaravelCloudServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        config()->set('laravel-cloud-sdk.token', env('LARAVEL_CLOUD_TOKEN', 'test-token'));
    }
}
