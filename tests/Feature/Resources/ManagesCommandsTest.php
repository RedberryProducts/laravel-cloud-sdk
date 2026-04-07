<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Data\Commands\RunCommandData;
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

    $result = (new LaravelCloud('token'))->commands('env-a14fe550-4e39-4ff2-8016-a20e4d32a996');

    Saloon::assertSent(ListCommandsRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(CommandData::class);
});

it('retrieves a single command by id', function () {
    Saloon::fake([
        GetCommandRequest::class => new LaravelCloudFixture('commands/get'),
    ]);

    $result = (new LaravelCloud('token'))->command('cmd-abc123');

    Saloon::assertSent(GetCommandRequest::class);
    expect($result)->toBeInstanceOf(CommandData::class);
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
