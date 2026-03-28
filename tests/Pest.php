<?php

use Redberry\LaravelCloudSdk\Tests\TestCase;
use Saloon\MockConfig;

if (file_exists(__DIR__.'/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
    $dotenv->safeLoad();
}

uses(TestCase::class)->in(__DIR__);

MockConfig::setFixturePath(__DIR__.'/Fixtures/Saloon');
