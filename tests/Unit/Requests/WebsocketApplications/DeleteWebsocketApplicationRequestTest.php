<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\CreateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\CreateWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\DeleteWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\CreateWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\DeleteWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteWebsocketApplicationRequest('wsa-123');

    expect($request->resolveEndpoint())->toBe('/websocket-applications/wsa-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteWebsocketApplicationRequest('wsa-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        CreateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-applications/delete-create-cluster'),
        CreateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/delete-create'),
        DeleteWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/delete'),
        DeleteWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-applications/delete-cleanup-cluster'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $cluster = $connector->send(new CreateWebsocketClusterRequest(new CreateWebsocketClusterData(
        name: 'sdk-ws-delete-test',
        type: WebsocketServerType::Reverb,
        region: CloudRegion::UsEast1,
        maxConnections: WebsocketMaxConnections::Connections100,
    )))->dtoOrFail();

    $wsApp = $connector->send(new CreateWebsocketApplicationRequest($cluster->id, new CreateWebsocketApplicationData(
        name: 'sdk-delete-test',
    )))->dtoOrFail();

    $response = $connector->send(new DeleteWebsocketApplicationRequest($wsApp->id));

    Saloon::assertSent(DeleteWebsocketApplicationRequest::class);
    expect($response->successful())->toBeTrue();

    $connector->send(new DeleteWebsocketClusterRequest($cluster->id));
});
