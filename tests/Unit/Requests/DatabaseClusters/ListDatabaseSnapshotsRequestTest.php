<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseClusterSize;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseSnapshotsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListDatabaseSnapshotsRequest('db-cluster-123');

    expect($request->resolveEndpoint())->toBe('/databases/clusters/db-cluster-123/snapshots');
});

it('has the correct HTTP method', function () {
    $request = new ListDatabaseSnapshotsRequest('db-cluster-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists database snapshots and returns a collection', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/create-mysql'),
        ListDatabaseSnapshotsRequest::class => new LaravelCloudFixture('database-clusters/snapshots'),
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

    $dto = $connector->send(new ListDatabaseSnapshotsRequest($mysqlCluster->id))->dtoOrFail();

    Saloon::assertSent(ListDatabaseSnapshotsRequest::class);
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(DatabaseSnapshotData::class);
});
