<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\GetWebsocketClusterMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetWebsocketClusterMetricsRequest('ws-123');

    expect($request->resolveEndpoint())->toBe('/websocket-servers/ws-123/metrics');
});

it('has the correct HTTP method', function () {
    $request = new GetWebsocketClusterMetricsRequest('ws-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes period in query when provided', function () {
    $request = new GetWebsocketClusterMetricsRequest('ws-123', MetricPeriod::SixHours);

    expect($request->query()->all())->toBe(['period' => '6h']);
});

it('omits period from query when null', function () {
    $request = new GetWebsocketClusterMetricsRequest('ws-123');

    expect($request->query()->all())->toBe([]);
});

it('gets websocket cluster metrics and returns WebsocketClusterMetricsData', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/metrics-list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()[0];

    Saloon::fake([
        GetWebsocketClusterMetricsRequest::class => new LaravelCloudFixture('websocket-clusters/metrics'),
    ]);

    $response = $connector->send(new GetWebsocketClusterMetricsRequest($firstCluster->id));

    Saloon::assertSent(GetWebsocketClusterMetricsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketClusterMetricsData::class);
});
