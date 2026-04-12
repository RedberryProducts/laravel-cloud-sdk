<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\CreateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\UpdateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationMetricsData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\CreateWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\DeleteWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\GetWebsocketApplicationMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\GetWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\ListWebsocketApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\UpdateWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists websocket applications for a cluster', function () {
    Saloon::fake([
        ListWebsocketApplicationsRequest::class => new LaravelCloudFixture('websocket-applications/list'),
    ]);

    $result = (new LaravelCloud('token'))->websocketApplications('ws-a17ede9f-e861-47e3-ae13-000ceb6f467e');

    expect($result)->toBeInstanceOf(LazyCollection::class);

    $first = $result->first();
    expect($first)->toBeInstanceOf(WebsocketApplicationData::class);
    expect($first->websocketCluster)->toBeInstanceOf(WebsocketClusterData::class);
    Saloon::assertSent(ListWebsocketApplicationsRequest::class);
});

it('retrieves a single websocket application by id', function () {
    Saloon::fake([
        GetWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/get'),
    ]);

    $result = (new LaravelCloud('token'))->websocketApplication('wsa-a17ede9f-eaa2-48aa-abc3-c18545415d86');

    Saloon::assertSent(GetWebsocketApplicationRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationData::class);
    expect($result->id)->toBe('wsa-a17ede9f-eaa2-48aa-abc3-c18545415d86');
    expect($result->name)->toBe('main');
    expect($result->websocketCluster)->toBeInstanceOf(WebsocketClusterData::class);
});

it('creates a websocket application with named params', function () {
    Saloon::fake([
        CreateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/create'),
    ]);

    $result = (new LaravelCloud('token'))->createWebsocketApplication(
        clusterId: 'ws-a17ede9f-e861-47e3-ae13-000ceb6f467e',
        name: 'main',
    );

    Saloon::assertSent(CreateWebsocketApplicationRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationData::class);
});

it('creates a websocket application with optional params', function () {
    Saloon::fake([
        CreateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/create'),
    ]);

    $result = (new LaravelCloud('token'))->createWebsocketApplication(
        clusterId: 'ws-a17ede9f-e861-47e3-ae13-000ceb6f467e',
        name: 'main',
        pingInterval: 60,
        activityTimeout: 30,
    );

    Saloon::assertSent(CreateWebsocketApplicationRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationData::class);
});

it('creates a websocket application via createWebsocketApplicationWith()', function () {
    Saloon::fake([
        CreateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/create'),
    ]);

    $result = (new LaravelCloud('token'))->createWebsocketApplicationWith(
        'ws-a17ede9f-e861-47e3-ae13-000ceb6f467e',
        new CreateWebsocketApplicationData(name: 'main'),
    );

    Saloon::assertSent(CreateWebsocketApplicationRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationData::class);
});

it('updates a websocket application with named params', function () {
    Saloon::fake([
        UpdateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateWebsocketApplication(
        'wsa-a17ede9f-eaa2-48aa-abc3-c18545415d86',
        name: 'main',
    );

    Saloon::assertSent(UpdateWebsocketApplicationRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationData::class);
});

it('updates a websocket application via updateWebsocketApplicationWith()', function () {
    Saloon::fake([
        UpdateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateWebsocketApplicationWith(
        'wsa-a17ede9f-eaa2-48aa-abc3-c18545415d86',
        new UpdateWebsocketApplicationData(name: 'main'),
    );

    Saloon::assertSent(UpdateWebsocketApplicationRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationData::class);
});

it('gets websocket application metrics', function () {
    Saloon::fake([
        GetWebsocketApplicationMetricsRequest::class => new LaravelCloudFixture('websocket-applications/metrics'),
    ]);

    $result = (new LaravelCloud('token'))->websocketApplicationMetrics('wsa-a17ede9f-eaa2-48aa-abc3-c18545415d86');

    Saloon::assertSent(GetWebsocketApplicationMetricsRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationMetricsData::class);
});

it('deletes a websocket application', function () {
    Saloon::fake([
        DeleteWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/delete'),
    ]);

    (new LaravelCloud('token'))->deleteWebsocketApplication('wsa-a17ede9f-eaa2-48aa-abc3-c18545415d86');

    Saloon::assertSent(DeleteWebsocketApplicationRequest::class);
});
