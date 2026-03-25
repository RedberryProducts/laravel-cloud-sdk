<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListWebsocketClustersRequest;

    expect($request->resolveEndpoint())->toBe('/websocket-servers');
});

it('has the correct HTTP method', function () {
    $request = new ListWebsocketClustersRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists websocket clusters and returns WebsocketClusterData collection', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new ListWebsocketClustersRequest);

    Saloon::assertSent(ListWebsocketClustersRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/websocket-servers');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(WebsocketClusterData::class);
});
