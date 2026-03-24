<?php

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\WebsocketConnectionDistributionStrategy;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;
use Redberry\LaravelCloudSdk\Enums\WebsocketStatus;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\CreateWebsocketClusterRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateWebsocketClusterData(
        name: 'test-ws-cluster',
        type: WebsocketServerType::REVERB,
        region: CloudRegion::US_EAST_1,
        maxConnections: WebsocketMaxConnections::CONNECTIONS_100,
    );
    $request = new CreateWebsocketClusterRequest($data);

    expect($request->resolveEndpoint())->toBe('/websocket-servers');
});

it('has the correct HTTP method', function () {
    $data = new CreateWebsocketClusterData(
        name: 'test-ws-cluster',
        type: WebsocketServerType::REVERB,
        region: CloudRegion::US_EAST_1,
        maxConnections: WebsocketMaxConnections::CONNECTIONS_100,
    );
    $request = new CreateWebsocketClusterRequest($data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('sends correct body', function () {
    $data = new CreateWebsocketClusterData(
        name: 'test-ws-cluster',
        type: WebsocketServerType::REVERB,
        region: CloudRegion::US_EAST_1,
        maxConnections: WebsocketMaxConnections::CONNECTIONS_100,
    );
    $request = new CreateWebsocketClusterRequest($data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('test-ws-cluster');
    expect($body['type'])->toBe('reverb');
    expect($body['region'])->toBe('us-east-1');
    expect($body['max_connections'])->toBe(100);
});

it('creates a websocket cluster and returns WebsocketClusterData with all fields', function () {
    Saloon::fake([
        CreateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/create'),
    ]);

    $data = new CreateWebsocketClusterData(
        name: 'test-ws-cluster',
        type: WebsocketServerType::REVERB,
        region: CloudRegion::US_EAST_1,
        maxConnections: WebsocketMaxConnections::CONNECTIONS_100,
    );

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new CreateWebsocketClusterRequest($data));

    Saloon::assertSent(CreateWebsocketClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketClusterData::class);
    expect($dto->id)->toBe('ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273');
    expect($dto->name)->toBe('test-ws-cluster');
    expect($dto->type)->toBe(WebsocketServerType::REVERB);
    expect($dto->region)->toBe(CloudRegion::US_EAST_1);
    expect($dto->status)->toBe(WebsocketStatus::CREATING);
    expect($dto->maxConnections)->toBe(WebsocketMaxConnections::CONNECTIONS_100);
    expect($dto->connectionDistributionStrategy)->toBe(WebsocketConnectionDistributionStrategy::EVENLY);
    expect($dto->hostname)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
