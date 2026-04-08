<?php

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterMetricsData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseTypeData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\RestoreDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\UpdateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseSnapshotRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\DeleteDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\DeleteDatabaseSnapshotRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseClusterMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseSnapshotRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseSnapshotsRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseTypesRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\RestoreDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\UpdateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists database clusters', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $result = (new LaravelCloud('token'))->databaseClusters();

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(DatabaseClusterData::class);
    Saloon::assertSent(ListDatabaseClustersRequest::class);
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
        config: new NeonServerlessPostgresConfigData(
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
        config: new NeonServerlessPostgresConfigData(
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

    $config = new NeonServerlessPostgresConfigData(cuMin: 0.25, cuMax: 0.25, suspendSeconds: 300, retentionDays: 7);

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
        config: new NeonServerlessPostgresConfigData(cuMin: 0.25, cuMax: 0.5, suspendSeconds: 300, retentionDays: 7),
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
            config: new NeonServerlessPostgresConfigData(cuMin: 0.25, cuMax: 0.5, suspendSeconds: 300, retentionDays: 7),
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

it('deletes a database cluster', function () {
    Saloon::fake([
        DeleteDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/delete'),
    ]);

    (new LaravelCloud('token'))->deleteDatabaseCluster('red-paper-65989343');

    Saloon::assertSent(DeleteDatabaseClusterRequest::class);
});

it('creates a database snapshot with named params', function () {
    Saloon::fake([
        CreateDatabaseSnapshotRequest::class => new LaravelCloudFixture('database-clusters/snapshot-create'),
    ]);

    $result = (new LaravelCloud('token'))->createDatabaseSnapshot('db-cluster-123', name: 'test-snapshot');

    Saloon::assertSent(CreateDatabaseSnapshotRequest::class);
    expect($result)->toBeInstanceOf(DatabaseSnapshotData::class);
});

it('creates a database snapshot via createDatabaseSnapshotWith()', function () {
    Saloon::fake([
        CreateDatabaseSnapshotRequest::class => new LaravelCloudFixture('database-clusters/snapshot-create'),
    ]);

    $result = (new LaravelCloud('token'))->createDatabaseSnapshotWith(
        'db-cluster-123',
        new CreateDatabaseSnapshotData(name: 'test-snapshot'),
    );

    Saloon::assertSent(CreateDatabaseSnapshotRequest::class);
    expect($result)->toBeInstanceOf(DatabaseSnapshotData::class);
});

it('lists database snapshots for a cluster', function () {
    Saloon::fake([
        ListDatabaseSnapshotsRequest::class => new LaravelCloudFixture('database-clusters/snapshots'),
    ]);

    $result = (new LaravelCloud('token'))->databaseSnapshots('db-cluster-123');

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(DatabaseSnapshotData::class);
    Saloon::assertSent(ListDatabaseSnapshotsRequest::class);
});

it('retrieves a single database snapshot by id', function () {
    Saloon::fake([
        GetDatabaseSnapshotRequest::class => new LaravelCloudFixture('database-clusters/snapshot-get'),
    ]);

    $result = (new LaravelCloud('token'))->databaseSnapshot('snap-123');

    Saloon::assertSent(GetDatabaseSnapshotRequest::class);
    expect($result)->toBeInstanceOf(DatabaseSnapshotData::class);
});

it('restores a database cluster with named params', function () {
    Saloon::fake([
        RestoreDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/restore'),
    ]);

    $result = (new LaravelCloud('token'))->restoreDatabaseCluster(
        'db-cluster-123',
        name: 'restored-cluster',
        databaseSnapshotId: 'snap-abc123',
    );

    Saloon::assertSent(RestoreDatabaseClusterRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterData::class);
});

it('restores a database cluster via restoreDatabaseClusterWith()', function () {
    Saloon::fake([
        RestoreDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/restore'),
    ]);

    $result = (new LaravelCloud('token'))->restoreDatabaseClusterWith(
        'db-cluster-123',
        new RestoreDatabaseClusterData(
            name: 'restored-cluster',
            databaseSnapshotId: 'snap-abc123',
        ),
    );

    Saloon::assertSent(RestoreDatabaseClusterRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterData::class);
});

it('gets database cluster metrics', function () {
    Saloon::fake([
        GetDatabaseClusterMetricsRequest::class => new LaravelCloudFixture('database-clusters/metrics'),
    ]);

    $result = (new LaravelCloud('token'))->databaseClusterMetrics('red-paper-65989343');

    Saloon::assertSent(GetDatabaseClusterMetricsRequest::class);
    expect($result)->toBeInstanceOf(DatabaseClusterMetricsData::class);
});

it('deletes a database snapshot', function () {
    Saloon::fake([
        DeleteDatabaseSnapshotRequest::class => new LaravelCloudFixture('database-clusters/snapshot-delete'),
    ]);

    (new LaravelCloud('token'))->deleteDatabaseSnapshot('snap-123');

    Saloon::assertSent(DeleteDatabaseSnapshotRequest::class);
});
