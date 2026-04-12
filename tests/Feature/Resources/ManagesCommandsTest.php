<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Data\Commands\RunCommandData;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Users\UserData;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Commands\GetCommandRequest;
use Redberry\LaravelCloudSdk\Requests\Commands\ListCommandsRequest;
use Redberry\LaravelCloudSdk\Requests\Commands\RunCommandRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists commands for an environment', function () {
    Saloon::fake([
        ListCommandsRequest::class => new LaravelCloudFixture('commands/list'),
    ]);

    $result = (new LaravelCloud('token'))->commands('env-a15fd671-0b6a-401a-84bf-14105ce69023');

    expect($result)->toBeInstanceOf(LazyCollection::class);

    $first = $result->first();
    expect($first)->toBeInstanceOf(CommandData::class);
    expect($first->environment)->toBeInstanceOf(EnvironmentData::class);
    expect($first->deployment)->toBeInstanceOf(DeploymentData::class);
    expect($first->initiator)->toBeInstanceOf(UserData::class);

    Saloon::assertSent(ListCommandsRequest::class);
});

it('retrieves a single command by id', function () {
    Saloon::fake([
        GetCommandRequest::class => new LaravelCloudFixture('commands/get'),
    ]);

    $result = (new LaravelCloud('token'))->command('comm-a186814b-3d65-4f30-9b54-6e860a3b29de');

    Saloon::assertSent(GetCommandRequest::class);
    expect($result)->toBeInstanceOf(CommandData::class);
    expect($result->environment)->toBeInstanceOf(EnvironmentData::class);
    expect($result->deployment)->toBeInstanceOf(DeploymentData::class);
    expect($result->initiator)->toBeInstanceOf(UserData::class);
});

it('runs a command with named params', function () {
    Saloon::fake([
        RunCommandRequest::class => new LaravelCloudFixture('commands/create'),
    ]);

    $result = (new LaravelCloud('token'))->runCommand(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        command: 'php artisan cache:clear',
    );

    Saloon::assertSent(RunCommandRequest::class);
    expect($result)->toBeInstanceOf(CommandData::class);
});

it('runs a command via runCommandWith()', function () {
    Saloon::fake([
        RunCommandRequest::class => new LaravelCloudFixture('commands/create'),
    ]);

    $result = (new LaravelCloud('token'))->runCommandWith(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        new RunCommandData(command: 'php artisan cache:clear'),
    );

    Saloon::assertSent(RunCommandRequest::class);
    expect($result)->toBeInstanceOf(CommandData::class);
});
