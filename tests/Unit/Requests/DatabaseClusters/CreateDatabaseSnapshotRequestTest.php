<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseClusterSize;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseSnapshotRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new CreateDatabaseSnapshotRequest('db-cluster-123', new CreateDatabaseSnapshotData(name: 'snap'));

    expect($request->resolveEndpoint())->toBe('/databases/clusters/db-cluster-123/snapshots');
});

it('has the correct HTTP method', function () {
    $request = new CreateDatabaseSnapshotRequest('db-cluster-123', new CreateDatabaseSnapshotData(name: 'snap'));

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $request = new CreateDatabaseSnapshotRequest('db-cluster-123', new CreateDatabaseSnapshotData(name: 'snap'));

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('creates a database snapshot and returns DatabaseSnapshotData', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/create-mysql'),
        CreateDatabaseSnapshotRequest::class => new LaravelCloudFixture('database-clusters/snapshot-create'),
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

    $dto = $connector->send(new CreateDatabaseSnapshotRequest(
        $mysqlCluster->id,
        new CreateDatabaseSnapshotData(name: 'test-snapshot'),
    ))->dtoOrFail();

    Saloon::assertSent(CreateDatabaseSnapshotRequest::class);
    expect($dto)->toBeInstanceOf(DatabaseSnapshotData::class);
});

it('sends the correct body', function () {
    Saloon::fake([
        CreateDatabaseSnapshotRequest::class => new LaravelCloudFixture('database-clusters/snapshot-create'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $connector->send(new CreateDatabaseSnapshotRequest(
        'db-cluster-123',
        new CreateDatabaseSnapshotData(name: 'test-snapshot', description: 'My backup'),
    ));

    Saloon::assertSent(CreateDatabaseSnapshotRequest::class, function ($request) {
        $body = json_decode($request->body()->all(), true);

        return $body['name'] === 'test-snapshot'
            && $body['description'] === 'My backup';
    });
});
