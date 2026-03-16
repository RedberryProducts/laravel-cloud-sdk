<?php

use App\Data\LaravelCloud\WebsocketClusters\CreateWebsocketClusterData;
use App\Data\LaravelCloud\WebsocketClusters\WebsocketClusterData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\WebsocketMaxConnections;
use App\Enums\LaravelCloud\WebsocketServerType;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketClusters\CreateWebsocketClusterRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

it('creates a websocket cluster and returns WebsocketClusterData', function () {
    Saloon::fake([
        CreateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/create'),
    ]);

    $data = new CreateWebsocketClusterData(
        name: 'test-ws-cluster',
        type: WebsocketServerType::REVERB,
        region: CloudRegion::US_EAST_1,
        maxConnections: WebsocketMaxConnections::CONNECTIONS_100,
    );

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new CreateWebsocketClusterRequest($data));

    Saloon::assertSent(CreateWebsocketClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketClusterData::class);
});
