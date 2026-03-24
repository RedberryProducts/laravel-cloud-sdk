<?php

use App\Data\LaravelCloud\WebsocketClusters\WebsocketClusterData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketClusters\GetWebsocketClusterRequest;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new GetWebsocketClusterRequest('ws-123');

    expect($request->resolveEndpoint())->toBe('/websocket-servers/ws-123');
});

it('has the correct HTTP method', function () {
    $request = new GetWebsocketClusterRequest('ws-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a websocket cluster and returns WebsocketClusterData', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        GetWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/get'),
    ]);

    $response = $connector->send(new GetWebsocketClusterRequest($firstCluster->id));

    Saloon::assertSent(GetWebsocketClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketClusterData::class);
});
