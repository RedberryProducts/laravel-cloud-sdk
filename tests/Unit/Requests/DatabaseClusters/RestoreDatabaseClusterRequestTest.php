<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\RestoreDatabaseClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseClusterSize;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseSnapshotsRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\RestoreDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new RestoreDatabaseClusterRequest('db-cluster-123', new RestoreDatabaseClusterData(name: 'restored'));

    expect($request->resolveEndpoint())->toBe('/databases/clusters/db-cluster-123/restore');
});

it('has the correct HTTP method', function () {
    $request = new RestoreDatabaseClusterRequest('db-cluster-123', new RestoreDatabaseClusterData(name: 'restored'));

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $request = new RestoreDatabaseClusterRequest('db-cluster-123', new RestoreDatabaseClusterData(name: 'restored'));

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('restores a database cluster from a snapshot and returns DatabaseClusterData', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/create-mysql'),
        ListDatabaseSnapshotsRequest::class => new LaravelCloudFixture('database-clusters/snapshots'),
        RestoreDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/restore'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $mysqlCluster = $connector->send(new CreateDatabaseClusterRequest(new CreateDatabaseClusterData(
        name: 'test-mysql-snapshot-cluster',
        type: DatabaseType::LARAVEL_MYSQL_84,
        region: CloudRegion::US_EAST_1,
        config: new LaravelMysqlConfigData(
            size: DatabaseClusterSize::FLEX_1VCPU_512MB,
            storage: 10,
            isPublic: false,
            usesScheduledSnapshots: false,
            retentionDays: 7,
            maintenanceWindow: null,
        ),
    )))->dtoOrFail();

    $firstSnapshot = $connector->send(new ListDatabaseSnapshotsRequest($mysqlCluster->id))->dtoOrFail()[0];

    $dto = $connector->send(new RestoreDatabaseClusterRequest(
        $mysqlCluster->id,
        new RestoreDatabaseClusterData(
            name: 'restored-cluster',
            databaseSnapshotId: $firstSnapshot->id,
        ),
    ))->dtoOrFail();

    Saloon::assertSent(RestoreDatabaseClusterRequest::class);
    expect($dto)->toBeInstanceOf(DatabaseClusterData::class);
});

it('sends the correct body', function () {
    Saloon::fake([
        RestoreDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/restore'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $connector->send(new RestoreDatabaseClusterRequest(
        'db-cluster-123',
        new RestoreDatabaseClusterData(
            name: 'restored-cluster',
            databaseSnapshotId: 'snap-abc123',
        ),
    ));

    Saloon::assertSent(RestoreDatabaseClusterRequest::class, function ($request) {
        $body = json_decode($request->body()->all(), true);

        return $body['name'] === 'restored-cluster'
            && $body['database_snapshot_id'] === 'snap-abc123';
    });
});
