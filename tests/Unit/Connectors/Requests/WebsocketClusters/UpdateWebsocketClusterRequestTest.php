<?php

use App\Data\LaravelCloud\WebsocketClusters\UpdateWebsocketClusterData;
use App\Data\LaravelCloud\WebsocketClusters\WebsocketClusterData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\WebsocketConnectionDistributionStrategy;
use App\Enums\LaravelCloud\WebsocketMaxConnections;
use App\Enums\LaravelCloud\WebsocketServerType;
use App\Enums\LaravelCloud\WebsocketStatus;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketClusters\UpdateWebsocketClusterRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new UpdateWebsocketClusterData(name: 'updated-cluster');
    $request = new UpdateWebsocketClusterRequest('ws-123', $data);

    expect($request->resolveEndpoint())->toBe('/websocket-servers/ws-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateWebsocketClusterData(name: 'updated-cluster');
    $request = new UpdateWebsocketClusterRequest('ws-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body with all optional fields', function () {
    $data = new UpdateWebsocketClusterData(
        name: 'updated-cluster',
        maxConnections: WebsocketMaxConnections::CONNECTIONS_200,
    );
    $request = new UpdateWebsocketClusterRequest('ws-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-cluster');
    expect($body['max_connections'])->toBe(200);
});

it('excludes unset optional fields from body', function () {
    $data = new UpdateWebsocketClusterData(name: 'updated-cluster');
    $request = new UpdateWebsocketClusterRequest('ws-123', $data);
    $body = $request->body()->all();

    expect($body)->toHaveKey('name');
    expect($body)->not->toHaveKey('max_connections');
});

it('updates a websocket cluster and returns WebsocketClusterData with all fields', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        UpdateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/update'),
    ]);

    $data = new UpdateWebsocketClusterData(name: 'updated-cluster');
    $response = $connector->send(new UpdateWebsocketClusterRequest($firstCluster->id, $data));

    Saloon::assertSent(UpdateWebsocketClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketClusterData::class);
    expect($dto->id)->toBe('ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273');
    expect($dto->name)->toBe('updated-cluster');
    expect($dto->type)->toBe(WebsocketServerType::REVERB);
    expect($dto->region)->toBe(CloudRegion::US_EAST_1);
    expect($dto->status)->toBe(WebsocketStatus::UPDATING);
    expect($dto->maxConnections)->toBe(WebsocketMaxConnections::CONNECTIONS_100);
    expect($dto->connectionDistributionStrategy)->toBe(WebsocketConnectionDistributionStrategy::EVENLY);
    expect($dto->hostname)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
