<?php

use App\Data\LaravelCloud\DatabaseClusters\CreateDatabaseClusterData;
use App\Data\LaravelCloud\DatabaseClusters\DatabaseClusterData;
use App\Data\LaravelCloud\DatabaseClusters\DatabaseConnectionData;
use App\Data\LaravelCloud\DatabaseClusters\NeonConfigData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\DatabaseDriver;
use App\Enums\LaravelCloud\DatabaseProtocol;
use App\Enums\LaravelCloud\DatabaseStatus;
use App\Enums\LaravelCloud\DatabaseType;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateDatabaseClusterData(
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
    $request = new CreateDatabaseClusterRequest($data);

    expect($request->resolveEndpoint())->toBe('/databases/clusters');
});

it('has the correct HTTP method', function () {
    $data = new CreateDatabaseClusterData(
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
    $request = new CreateDatabaseClusterRequest($data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('sends correct body', function () {
    $data = new CreateDatabaseClusterData(
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
        config: new NeonConfigData(
            cuMin: 0.25,
            cuMax: 0.25,
            suspendSeconds: 300,
            retentionDays: 7,
        ),
    );

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new CreateDatabaseClusterRequest($data));

    Saloon::assertSent(CreateDatabaseClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DatabaseClusterData::class);
    expect($dto->id)->toBe('red-paper-65989343');
    expect($dto->name)->toBe('test-cluster');
    expect($dto->type)->toBe(DatabaseType::NEON_SERVERLESS_POSTGRES_17);
    expect($dto->status)->toBe(DatabaseStatus::CREATING);
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
