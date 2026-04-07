<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseConnectionData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseDriver;
use Redberry\LaravelCloudSdk\Enums\DatabaseProtocol;
use Redberry\LaravelCloudSdk\Enums\DatabaseStatus;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Enums\NeonServerlessPostgresComputeUnit;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new CreateDatabaseClusterData(
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
    $request = new CreateDatabaseClusterRequest($data);

    expect($request->resolveEndpoint())->toBe('/databases/clusters');
});

it('has the correct HTTP method', function () {
    $data = new CreateDatabaseClusterData(
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
    $request = new CreateDatabaseClusterRequest($data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('sends correct body', function () {
    $data = new CreateDatabaseClusterData(
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
    $request = new CreateDatabaseClusterRequest($data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('test-cluster');
    expect($body['type'])->toBe('neon_serverless_postgres_17');
    expect($body['region'])->toBe('us-east-1');
    expect($body['config'])->toBeArray();
    expect($body['config']['cu_min'])->toBe(0.25);
    expect($body['config']['cu_max'])->toBe(0.25);
    expect($body['config']['suspend_seconds'])->toBe(300);
    expect($body['config']['retention_days'])->toBe(7);
});

it('creates a database cluster and returns DatabaseClusterData with all fields', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/create'),
    ]);

    $data = new CreateDatabaseClusterData(
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

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new CreateDatabaseClusterRequest($data));

    Saloon::assertSent(CreateDatabaseClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DatabaseClusterData::class);
    expect($dto->id)->toBe('red-paper-65989343');
    expect($dto->name)->toBe('test-cluster');
    expect($dto->type)->toBe(DatabaseType::NEON_SERVERLESS_POSTGRES_17);
    expect($dto->status)->toBe(DatabaseStatus::CREATING);
    expect($dto->region)->toBe(CloudRegion::US_EAST_1);
    expect($dto->config)->toBeInstanceOf(NeonServerlessPostgresConfigData::class);
    expect($dto->config->cuMin)->toBe(NeonServerlessPostgresComputeUnit::CU_0_25);
    expect($dto->config->cuMax)->toBe(NeonServerlessPostgresComputeUnit::CU_0_25);
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
