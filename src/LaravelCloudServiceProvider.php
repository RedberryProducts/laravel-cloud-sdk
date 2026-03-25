<?php

namespace Redberry\LaravelCloudSdk;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCloudServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cloud-sdk')
            ->hasConfigFile('laravel-cloud-sdk');
    }

    public function packageRegistered(): void
    {
        $this->app->bind(LaravelCloud::class, function () {
            $token = config('laravel-cloud-sdk.token');

            if (! $token) {
                throw new \RuntimeException('Laravel Cloud token is not configured. Set LARAVEL_CLOUD_TOKEN in your .env file.');
            }

            return new LaravelCloud($token);
        });
    }
}
