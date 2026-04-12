<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\GetWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetWebsocketClusterRequest('ws-123');

    expect($request->resolveEndpoint())->toBe('/websocket-servers/ws-123');
});

it('has the correct HTTP method', function () {
    $request = new GetWebsocketClusterRequest('ws-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes relationships in default query', function () {
    $request = new GetWebsocketClusterRequest('ws-123');

    expect($request->query()->all())->toBe([
        'include' => 'applications',
    ]);
});

it('gets a websocket cluster and returns WebsocketClusterData', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()[0];

    Saloon::fake([
        GetWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/get'),
    ]);

    $response = $connector->send(new GetWebsocketClusterRequest($firstCluster->id));

    Saloon::assertSent(GetWebsocketClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketClusterData::class);
});
