<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseConnectionData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\UpdateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseDriver;
use Redberry\LaravelCloudSdk\Enums\DatabaseProtocol;
use Redberry\LaravelCloudSdk\Enums\DatabaseStatus;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\UpdateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new UpdateDatabaseClusterData(
        config: new NeonConfigData(cuMin: 1, cuMax: 1, suspendSeconds: 300, retentionDays: 7),
    );
    $request = new UpdateDatabaseClusterRequest('cluster-123', $data);

    expect($request->resolveEndpoint())->toBe('/databases/clusters/cluster-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateDatabaseClusterData(
        config: new NeonConfigData(cuMin: 1, cuMax: 1, suspendSeconds: 300, retentionDays: 7),
    );
    $request = new UpdateDatabaseClusterRequest('cluster-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body', function () {
    $data = new UpdateDatabaseClusterData(
        config: new NeonConfigData(cuMin: 0.25, cuMax: 4, suspendSeconds: 300, retentionDays: 7),
    );
    $request = new UpdateDatabaseClusterRequest('cluster-123', $data);
    $body = $request->body()->all();

    expect($body['config'])->toBe([
        'cu_min' => 0.25,
        'cu_max' => 4.0,
        'suspend_seconds' => 300,
        'retention_days' => 7,
    ]);
});

it('updates a database cluster and returns DatabaseClusterData with all fields', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $listResponse = $connector->send(new ListDatabaseClustersRequest);
    $firstCluster = $listResponse->dtoOrFail()->first();

    Saloon::fake([
        UpdateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/update'),
    ]);

    $data = new UpdateDatabaseClusterData(
        config: new NeonConfigData(cuMin: 0.25, cuMax: 0.25, suspendSeconds: 300, retentionDays: 7),
    );
    $response = $connector->send(new UpdateDatabaseClusterRequest($firstCluster->id, $data));

    Saloon::assertSent(UpdateDatabaseClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DatabaseClusterData::class);
    expect($dto->id)->toBe('red-paper-65989343');
    expect($dto->name)->toBe('test-cluster');
    expect($dto->type)->toBe(DatabaseType::NEON_SERVERLESS_POSTGRES_17);
    expect($dto->status)->toBe(DatabaseStatus::UPDATING);
    expect($dto->region)->toBe(CloudRegion::US_EAST_1);
    expect($dto->config)->toBeInstanceOf(NeonConfigData::class);
    expect($dto->config->cuMin)->toBe(0.25);
    expect($dto->config->cuMax)->toBe(0.25);
    expect($dto->config->suspendSeconds)->toBe(300);
    expect($dto->config->retentionDays)->toBe(7);
    expect($dto->connection)->toBeInstanceOf(DatabaseConnectionData::class);
    expect($dto->connection->hostname)->toBeString();
    expect($dto->connection->port)->toBe(5432);
    expect($dto->connection->protocol)->toBe(DatabaseProtocol::POSTGRES);
    expect($dto->connection->driver)->toBe(DatabaseDriver::PGSQL);
    expect($dto->connection->username)->toBeString();
    expect($dto->connection->password)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
