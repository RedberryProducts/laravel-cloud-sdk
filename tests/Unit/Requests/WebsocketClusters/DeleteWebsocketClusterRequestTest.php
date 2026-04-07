<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\DeleteWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteWebsocketClusterRequest('wsc-123');

    expect($request->resolveEndpoint())->toBe('/websocket-servers/wsc-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteWebsocketClusterRequest('wsc-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
        DeleteWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()[0];

    $response = $connector->send(new DeleteWebsocketClusterRequest($firstCluster->id));

    Saloon::assertSent(DeleteWebsocketClusterRequest::class);
    expect($response->successful())->toBeTrue();
});
