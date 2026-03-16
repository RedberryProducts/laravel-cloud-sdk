<?php

use App\Data\LaravelCloud\WebsocketClusters\UpdateWebsocketClusterData;
use App\Data\LaravelCloud\WebsocketClusters\WebsocketClusterData;
use App\Enums\LaravelCloud\WebsocketMaxConnections;
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

it('sends correct body', function () {
    $data = new UpdateWebsocketClusterData(
        name: 'updated-cluster',
        maxConnections: WebsocketMaxConnections::CONNECTIONS_200,
    );
    $request = new UpdateWebsocketClusterRequest('ws-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-cluster');
    expect($body['max_connections'])->toBe(200);
});

it('updates a websocket cluster and returns WebsocketClusterData', function () {
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
});
