<?php

use Redberry\LaravelCloudSdk\Tests\TestCase;
use Saloon\MockConfig;

uses(TestCase::class)->in(__DIR__);

MockConfig::setFixturePath(__DIR__.'/Fixtures/Saloon');
