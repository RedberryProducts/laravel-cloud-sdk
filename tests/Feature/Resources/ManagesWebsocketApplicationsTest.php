<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\CreateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\UpdateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\CreateWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\GetWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\ListWebsocketApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\UpdateWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists websocket applications for a cluster', function () {
    Saloon::fake([
        ListWebsocketApplicationsRequest::class => new LaravelCloudFixture('websocket-applications/list'),
    ]);

    $result = (new LaravelCloud('token'))->websocketApplications('ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273');

    Saloon::assertSent(ListWebsocketApplicationsRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(WebsocketApplicationData::class);
});

it('retrieves a single websocket application by id', function () {
    Saloon::fake([
        GetWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/get'),
    ]);

    $result = (new LaravelCloud('token'))->websocketApplication('wsa-a14fcb1a-1f09-4091-85b8-3eb74c8ce500');

    Saloon::assertSent(GetWebsocketApplicationRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationData::class);
    expect($result->id)->toBe('wsa-a14fcb1a-1f09-4091-85b8-3eb74c8ce500');
    expect($result->name)->toBe('main');
});

it('creates a websocket application with named params', function () {
    Saloon::fake([
        CreateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/create'),
    ]);

    $result = (new LaravelCloud('token'))->createWebsocketApplication(
        clusterId: 'ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273',
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
        clusterId: 'ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273',
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
        'ws-a14fcb1a-18a7-411d-9d82-456d3aa2c273',
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
        'wsa-a14fcb1a-1f09-4091-85b8-3eb74c8ce500',
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
        'wsa-a14fcb1a-1f09-4091-85b8-3eb74c8ce500',
        new UpdateWebsocketApplicationData(name: 'main'),
    );

    Saloon::assertSent(UpdateWebsocketApplicationRequest::class);
    expect($result)->toBeInstanceOf(WebsocketApplicationData::class);
});
