<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\DeleteDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\DeleteDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\ListDatabasesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteDatabaseClusterRequest('db-123');

    expect($request->resolveEndpoint())->toBe('/databases/clusters/db-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteDatabaseClusterRequest('db-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        CreateDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/delete-create'),
        ListDatabasesRequest::class => new LaravelCloudFixture('database-clusters/delete-list-databases'),
        DeleteDatabaseRequest::class => new LaravelCloudFixture('database-clusters/delete-database'),
        DeleteDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $cluster = $connector->send(new CreateDatabaseClusterRequest(new CreateDatabaseClusterData(
        name: 'sdk-delete-test',
        type: DatabaseType::NEON_SERVERLESS_POSTGRES_17,
        region: CloudRegion::US_EAST_1,
        config: new NeonServerlessPostgresConfigData(
            cuMin: 0.25,
            cuMax: 0.25,
            suspendSeconds: 300,
            retentionDays: 1,
        ),
    )))->dtoOrFail();

    $databases = $connector->paginate(new ListDatabasesRequest($cluster->id))->collect();

    $databases->each(function (DatabaseData $database) use ($connector, $cluster) {
        $connector->send(new DeleteDatabaseRequest($cluster->id, $database->id));
    });

    $response = $connector->send(new DeleteDatabaseClusterRequest($cluster->id));

    Saloon::assertSent(DeleteDatabaseClusterRequest::class);
    expect($response->successful())->toBeTrue();
});
