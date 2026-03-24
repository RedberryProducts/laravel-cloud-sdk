<?php

namespace Redberry\LaravelCloudSdk;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Redberry\LaravelCloudSdk\Commands\LaravelCloudCommand;

class LaravelCloudServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-cloud-sdk')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_cloud_sdk_table')
            ->hasCommand(LaravelCloudCommand::class);
    }
}
