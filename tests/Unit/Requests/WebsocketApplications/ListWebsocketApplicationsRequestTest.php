<?php

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\ListWebsocketApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListWebsocketApplicationsRequest('cluster-123');

    expect($request->resolveEndpoint())->toBe('/websocket-servers/cluster-123/applications');
});

it('has the correct HTTP method', function () {
    $request = new ListWebsocketApplicationsRequest('cluster-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists websocket applications and returns WebsocketApplicationData collection', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListWebsocketApplicationsRequest::class => new LaravelCloudFixture('websocket-applications/list'),
    ]);

    $response = $connector->send(new ListWebsocketApplicationsRequest($firstCluster->id));

    Saloon::assertSent(ListWebsocketApplicationsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(WebsocketApplicationData::class);
});
