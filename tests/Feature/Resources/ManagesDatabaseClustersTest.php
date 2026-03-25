<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseTypeData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\UpdateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseTypesRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\UpdateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists database clusters', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $result = (new LaravelCloud('token'))->databaseClusters();

    Saloon::assertSent(ListDatabaseClustersRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(DatabaseClusterData::class);
});

it('retrieves a single database cluster by id', function () {
    Saloon::fake([
        GetDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/get'),
    ]);

    $result = (new LaravelCloud('token'))->databaseCluster('red-paper-65989343');

    Saloon::assertSent(GetDatabaseClusterRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterData::class);
    expect($result->id)->toBe('red-paper-65989343');
    expect($result->name)->toBe('test-cluster');
});

it('creates a database cluster with named params', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/create'),
    ]);

    $result = (new LaravelCloud('token'))->createDatabaseCluster(
        name: 'test-cluster',
        type: DatabaseType::NEON_SERVERLESS_POSTGRES_17,
        region: CloudRegion::US_EAST_1,
        config: new NeonConfigData(
            cuMin: 0.25,
            cuMax: 0.25,
            suspendSeconds: 300,
            retentionDays: 7,
        ),
    );

    Saloon::assertSent(CreateDatabaseClusterRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterData::class);
});

it('creates a database cluster with string type and region', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/create'),
    ]);

    $result = (new LaravelCloud('token'))->createDatabaseCluster(
        name: 'test-cluster',
        type: 'neon_serverless_postgres_17',
        region: 'us-east-1',
        config: new NeonConfigData(
            cuMin: 0.25,
            cuMax: 0.25,
            suspendSeconds: 300,
            retentionDays: 7,
        ),
    );

    Saloon::assertSent(CreateDatabaseClusterRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterData::class);
});

it('creates a database cluster via createDatabaseClusterWith()', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/create'),
    ]);

    $config = new NeonConfigData(cuMin: 0.25, cuMax: 0.25, suspendSeconds: 300, retentionDays: 7);

    $result = (new LaravelCloud('token'))->createDatabaseClusterWith(
        new CreateDatabaseClusterData(
            name: 'test-cluster',
            type: DatabaseType::NEON_SERVERLESS_POSTGRES_17,
            region: CloudRegion::US_EAST_1,
            config: $config,
        )
    );

    Saloon::assertSent(CreateDatabaseClusterRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterData::class);
});

it('updates a database cluster with named params', function () {
    Saloon::fake([
        UpdateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateDatabaseCluster(
        'red-paper-65989343',
        config: new NeonConfigData(cuMin: 0.25, cuMax: 0.5, suspendSeconds: 300, retentionDays: 7),
    );

    Saloon::assertSent(UpdateDatabaseClusterRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterData::class);
});

it('updates a database cluster via updateDatabaseClusterWith()', function () {
    Saloon::fake([
        UpdateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateDatabaseClusterWith(
        'red-paper-65989343',
        new UpdateDatabaseClusterData(
            config: new NeonConfigData(cuMin: 0.25, cuMax: 0.5, suspendSeconds: 300, retentionDays: 7),
        ),
    );

    Saloon::assertSent(UpdateDatabaseClusterRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterData::class);
});

it('lists available database types', function () {
    Saloon::fake([
        ListDatabaseTypesRequest::class => new LaravelCloudFixture('database-clusters/types'),
    ]);

    $result = (new LaravelCloud('token'))->databaseTypes();

    Saloon::assertSent(ListDatabaseTypesRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(DatabaseTypeData::class);
});
