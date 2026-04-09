<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\UpdateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterMetricsData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\CreateWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\DeleteWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\GetWebsocketClusterMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\GetWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\UpdateWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists websocket clusters', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $result = (new LaravelCloud('token'))->websocketClusters();

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(WebsocketClusterData::class);
    Saloon::assertSent(ListWebsocketClustersRequest::class);
});

it('retrieves a single websocket cluster by id', function () {
    Saloon::fake([
        GetWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/get'),
    ]);

    $result = (new LaravelCloud('token'))->websocketCluster('ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273');

    Saloon::assertSent(GetWebsocketClusterRequest::class);
    expect($result)->toBeInstanceOf(WebsocketClusterData::class);
    expect($result->id)->toBe('ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273');
    expect($result->name)->toBe('test-ws-cluster');
});

it('creates a websocket cluster with named params', function () {
    Saloon::fake([
        CreateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/create'),
    ]);

    $result = (new LaravelCloud('token'))->createWebsocketCluster(
        name: 'production-ws',
        type: WebsocketServerType::Reverb,
        region: CloudRegion::UsEast1,
        maxConnections: WebsocketMaxConnections::Connections100,
    );

    Saloon::assertSent(CreateWebsocketClusterRequest::class);
    expect($result)->toBeInstanceOf(WebsocketClusterData::class);
});

it('creates a websocket cluster with string enums', function () {
    Saloon::fake([
        CreateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/create'),
    ]);

    $result = (new LaravelCloud('token'))->createWebsocketCluster(
        name: 'production-ws',
        type: 'reverb',
        region: 'us-east-1',
        maxConnections: WebsocketMaxConnections::Connections100,
    );

    Saloon::assertSent(CreateWebsocketClusterRequest::class);
    expect($result)->toBeInstanceOf(WebsocketClusterData::class);
});

it('creates a websocket cluster via createWebsocketClusterWith()', function () {
    Saloon::fake([
        CreateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/create'),
    ]);

    $result = (new LaravelCloud('token'))->createWebsocketClusterWith(
        new CreateWebsocketClusterData(
            name: 'production-ws',
            type: WebsocketServerType::Reverb,
            region: CloudRegion::UsEast1,
            maxConnections: WebsocketMaxConnections::Connections100,
        )
    );

    Saloon::assertSent(CreateWebsocketClusterRequest::class);
    expect($result)->toBeInstanceOf(WebsocketClusterData::class);
});

it('updates a websocket cluster with named params', function () {
    Saloon::fake([
        UpdateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateWebsocketCluster(
        'ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273',
        name: 'test-ws-cluster',
    );

    Saloon::assertSent(UpdateWebsocketClusterRequest::class);
    expect($result)->toBeInstanceOf(WebsocketClusterData::class);
});

it('updates a websocket cluster via updateWebsocketClusterWith()', function () {
    Saloon::fake([
        UpdateWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateWebsocketClusterWith(
        'ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273',
        new UpdateWebsocketClusterData(name: 'test-ws-cluster'),
    );

    Saloon::assertSent(UpdateWebsocketClusterRequest::class);
    expect($result)->toBeInstanceOf(WebsocketClusterData::class);
});

it('gets websocket cluster metrics', function () {
    Saloon::fake([
        GetWebsocketClusterMetricsRequest::class => new LaravelCloudFixture('websocket-clusters/metrics'),
    ]);

    $result = (new LaravelCloud('token'))->websocketClusterMetrics('ws-a17ede9f-e861-47e3-ae13-000ceb6f467e');

    Saloon::assertSent(GetWebsocketClusterMetricsRequest::class);
    expect($result)->toBeInstanceOf(WebsocketClusterMetricsData::class);
});

it('deletes a websocket cluster', function () {
    Saloon::fake([
        DeleteWebsocketClusterRequest::class => new LaravelCloudFixture('websocket-clusters/delete'),
    ]);

    (new LaravelCloud('token'))->deleteWebsocketCluster('ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273');

    Saloon::assertSent(DeleteWebsocketClusterRequest::class);
});
