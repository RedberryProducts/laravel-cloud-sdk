<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\DeleteDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
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
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
        DeleteDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListDatabaseClustersRequest)->dtoOrFail()[0];

    $response = $connector->send(new DeleteDatabaseClusterRequest($firstCluster->id));

    Saloon::assertSent(DeleteDatabaseClusterRequest::class);
    expect($response->successful())->toBeTrue();
})->skip('Fixture pending: Laravel Cloud auto-attaches a schema to every new MySQL cluster, preventing deletion. Record fixture in Phase 10.');
