<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\CreateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\UpdateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\CreateBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\DeleteBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\GetBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\ListBackgroundProcessesRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\UpdateBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists background processes for an instance', function () {
    Saloon::fake([
        ListBackgroundProcessesRequest::class => new LaravelCloudFixture('background-processes/list'),
    ]);

    $result = (new LaravelCloud('token'))->backgroundProcesses('inst-a14fe550-5c7b-4986-9a0d-d0ab1dcda9ba');

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(BackgroundProcessData::class);
    Saloon::assertSent(ListBackgroundProcessesRequest::class);
});

it('retrieves a single background process by id', function () {
    Saloon::fake([
        GetBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/get'),
    ]);

    $result = (new LaravelCloud('token'))->backgroundProcess('bp-abc123');

    Saloon::assertSent(GetBackgroundProcessRequest::class);
    expect($result)->toBeInstanceOf(BackgroundProcessData::class);
});

it('creates a background process with named params', function () {
    Saloon::fake([
        CreateBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/create'),
    ]);

    $result = (new LaravelCloud('token'))->createBackgroundProcess(
        'inst-a14fe550-5c7b-4986-9a0d-d0ab1dcda9ba',
        type: DaemonType::Custom,
        processes: 1,
        command: 'php artisan my:command',
    );

    Saloon::assertSent(CreateBackgroundProcessRequest::class);
    expect($result)->toBeInstanceOf(BackgroundProcessData::class);
});

it('creates a background process via createBackgroundProcessWith()', function () {
    Saloon::fake([
        CreateBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/create'),
    ]);

    $result = (new LaravelCloud('token'))->createBackgroundProcessWith(
        'inst-a14fe550-5c7b-4986-9a0d-d0ab1dcda9ba',
        new CreateBackgroundProcessData(
            type: DaemonType::Worker,
            processes: 1,
            command: 'php artisan queue:work',
        ),
    );

    Saloon::assertSent(CreateBackgroundProcessRequest::class);
    expect($result)->toBeInstanceOf(BackgroundProcessData::class);
});

it('updates a background process with named params', function () {
    Saloon::fake([
        UpdateBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateBackgroundProcess(
        'bp-abc123',
        processes: 2,
    );

    Saloon::assertSent(UpdateBackgroundProcessRequest::class);
    expect($result)->toBeInstanceOf(BackgroundProcessData::class);
});

it('updates a background process via updateBackgroundProcessWith()', function () {
    Saloon::fake([
        UpdateBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateBackgroundProcessWith(
        'bp-abc123',
        new UpdateBackgroundProcessData(processes: 2),
    );

    Saloon::assertSent(UpdateBackgroundProcessRequest::class);
    expect($result)->toBeInstanceOf(BackgroundProcessData::class);
});

it('deletes a background process', function () {
    Saloon::fake([
        DeleteBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/delete'),
    ]);

    (new LaravelCloud('token'))->deleteBackgroundProcess('bp-abc123');

    Saloon::assertSent(DeleteBackgroundProcessRequest::class);
});
