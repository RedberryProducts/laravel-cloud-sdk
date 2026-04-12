<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseClusterSize;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseSnapshotRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseSnapshotsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetDatabaseSnapshotRequest('snap-123');

    expect($request->resolveEndpoint())->toBe('/database-snapshots/snap-123');
});

it('has the correct HTTP method', function () {
    $request = new GetDatabaseSnapshotRequest('snap-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes relationships in default query', function () {
    $request = new GetDatabaseSnapshotRequest('snap-123');

    expect($request->query()->all())->toBe([
        'include' => 'database',
    ]);
});

it('retrieves a database snapshot and returns DatabaseSnapshotData', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/create-mysql'),
        ListDatabaseSnapshotsRequest::class => new LaravelCloudFixture('database-clusters/snapshots'),
        GetDatabaseSnapshotRequest::class => new LaravelCloudFixture('database-clusters/snapshot-get'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $mysqlCluster = $connector->send(new CreateDatabaseClusterRequest(new CreateDatabaseClusterData(
        name: 'test-mysql-snapshot-cluster',
        type: DatabaseType::LaravelMysql84,
        region: CloudRegion::UsEast1,
        config: new LaravelMysqlConfigData(
            size: DatabaseClusterSize::Flex1vcpu512mb,
            storage: 10,
            isPublic: false,
            usesScheduledSnapshots: false,
            retentionDays: 7,
            maintenanceWindow: null,
        ),
    )))->dtoOrFail();

    $firstSnapshot = $connector->send(new ListDatabaseSnapshotsRequest($mysqlCluster->id))->dtoOrFail()[0];

    $dto = $connector->send(new GetDatabaseSnapshotRequest($firstSnapshot->id))->dtoOrFail();

    Saloon::assertSent(GetDatabaseSnapshotRequest::class);
    expect($dto)->toBeInstanceOf(DatabaseSnapshotData::class);
});
