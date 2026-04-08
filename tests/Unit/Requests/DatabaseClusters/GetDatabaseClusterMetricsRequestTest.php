<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseClusterMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetDatabaseClusterMetricsRequest('cluster-123');

    expect($request->resolveEndpoint())->toBe('/databases/clusters/cluster-123/metrics');
});

it('has the correct HTTP method', function () {
    $request = new GetDatabaseClusterMetricsRequest('cluster-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes period in query when provided', function () {
    $request = new GetDatabaseClusterMetricsRequest('cluster-123', MetricPeriod::SevenDays);

    expect($request->query()->all())->toBe(['period' => '7d']);
});

it('omits period from query when null', function () {
    $request = new GetDatabaseClusterMetricsRequest('cluster-123');

    expect($request->query()->all())->toBe([]);
});

it('gets database cluster metrics and returns DatabaseClusterMetricsData', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/metrics-list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListDatabaseClustersRequest)->dtoOrFail()[0];

    Saloon::fake([
        GetDatabaseClusterMetricsRequest::class => new LaravelCloudFixture('database-clusters/metrics'),
    ]);

    $response = $connector->send(new GetDatabaseClusterMetricsRequest($firstCluster->id));

    Saloon::assertSent(GetDatabaseClusterMetricsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DatabaseClusterMetricsData::class);
});
