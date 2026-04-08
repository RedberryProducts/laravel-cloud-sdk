<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\GetWebsocketApplicationMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\ListWebsocketApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetWebsocketApplicationMetricsRequest('ws-app-123');

    expect($request->resolveEndpoint())->toBe('/websocket-applications/ws-app-123/metrics');
});

it('has the correct HTTP method', function () {
    $request = new GetWebsocketApplicationMetricsRequest('ws-app-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes period in query when provided', function () {
    $request = new GetWebsocketApplicationMetricsRequest('ws-app-123', MetricPeriod::ThreeDays);

    expect($request->query()->all())->toBe(['period' => '3d']);
});

it('omits period from query when null', function () {
    $request = new GetWebsocketApplicationMetricsRequest('ws-app-123');

    expect($request->query()->all())->toBe([]);
});

it('gets websocket application metrics and returns WebsocketApplicationMetricsData', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/metrics-list'),
        ListWebsocketApplicationsRequest::class => new LaravelCloudFixture('websocket-applications/metrics-list'),
        GetWebsocketApplicationMetricsRequest::class => new LaravelCloudFixture('websocket-applications/metrics'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()[0];
    $firstApp = $connector->send(new ListWebsocketApplicationsRequest($firstCluster->id))->dtoOrFail()[0];

    $response = $connector->send(new GetWebsocketApplicationMetricsRequest($firstApp->id));

    Saloon::assertSent(GetWebsocketApplicationMetricsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketApplicationMetricsData::class);
});
