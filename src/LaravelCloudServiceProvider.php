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
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind(LaravelCloud::class, function () {
            return new LaravelCloud(config('laravel-cloud-sdk.token'));
        });
    }
}
