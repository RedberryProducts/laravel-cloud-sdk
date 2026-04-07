<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\DeleteWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\ListWebsocketApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
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
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
        ListWebsocketApplicationsRequest::class => new LaravelCloudFixture('websocket-applications/list'),
        DeleteWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()->first();
    $firstApp = $connector->send(new ListWebsocketApplicationsRequest($firstCluster->id))->dtoOrFail()->first();

    $response = $connector->send(new DeleteWebsocketApplicationRequest($firstApp->id));

    Saloon::assertSent(DeleteWebsocketApplicationRequest::class);
    expect($response->successful())->toBeTrue();
})->skip('Fixture pending: record in Phase 10.');
