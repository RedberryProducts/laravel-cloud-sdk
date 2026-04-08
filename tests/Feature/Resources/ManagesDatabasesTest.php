<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Databases\CreateDatabaseData;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Databases\CreateDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\DeleteDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\GetDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\ListDatabasesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists databases for a cluster', function () {
    Saloon::fake([
        ListDatabasesRequest::class => new LaravelCloudFixture('databases/list'),
    ]);

    $result = (new LaravelCloud('token'))->databases('red-paper-65989343');

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(DatabaseData::class);
    Saloon::assertSent(ListDatabasesRequest::class);
});

it('retrieves a single database by id', function () {
    Saloon::fake([
        GetDatabaseRequest::class => new LaravelCloudFixture('databases/get'),
    ]);

    $result = (new LaravelCloud('token'))->database('red-paper-65989343', '47336204');

    Saloon::assertSent(GetDatabaseRequest::class);
    expect($result)->toBeInstanceOf(DatabaseData::class);
    expect($result->id)->toBe('47336204');
    expect($result->name)->toBe('main');
});

it('creates a database with a name', function () {
    Saloon::fake([
        CreateDatabaseRequest::class => new LaravelCloudFixture('databases/create'),
    ]);

    $result = (new LaravelCloud('token'))->createDatabase('red-paper-65989343', 'my_database');

    Saloon::assertSent(CreateDatabaseRequest::class);
    expect($result)->toBeInstanceOf(DatabaseData::class);
});

it('creates a database via createDatabaseWith()', function () {
    Saloon::fake([
        CreateDatabaseRequest::class => new LaravelCloudFixture('databases/create'),
    ]);

    $result = (new LaravelCloud('token'))->createDatabaseWith(
        'red-paper-65989343',
        new CreateDatabaseData(name: 'my_database'),
    );

    Saloon::assertSent(CreateDatabaseRequest::class);
    expect($result)->toBeInstanceOf(DatabaseData::class);
});

it('deletes a database', function () {
    Saloon::fake([
        DeleteDatabaseRequest::class => new LaravelCloudFixture('databases/delete'),
    ]);

    (new LaravelCloud('token'))->deleteDatabase('red-paper-65989343', '47343217');

    Saloon::assertSent(DeleteDatabaseRequest::class);
});
