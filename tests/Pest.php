<?php

use Redberry\LaravelCloudSdk\Tests\TestCase;
use Saloon\Config;

uses(TestCase::class)->in(__DIR__);

Config::$fixtureStoragePath = __DIR__.'/Fixtures/Saloon';
