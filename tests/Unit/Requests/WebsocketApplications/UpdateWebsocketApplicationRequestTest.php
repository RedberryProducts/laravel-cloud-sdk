<?php

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\UpdateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\ListWebsocketApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\UpdateWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new UpdateWebsocketApplicationData(name: 'updated-app');
    $request = new UpdateWebsocketApplicationRequest('app-123', $data);

    expect($request->resolveEndpoint())->toBe('/websocket-applications/app-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateWebsocketApplicationData(name: 'updated-app');
    $request = new UpdateWebsocketApplicationRequest('app-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body with all optional fields', function () {
    $data = new UpdateWebsocketApplicationData(
        name: 'updated-app',
        pingInterval: 45,
        activityTimeout: 90,
        allowedOrigins: ['https://example.com'],
    );
    $request = new UpdateWebsocketApplicationRequest('app-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-app');
    expect($body['ping_interval'])->toBe(45);
    expect($body['activity_timeout'])->toBe(90);
    expect($body['allowed_origins'])->toBe(['https://example.com']);
});

it('excludes unset optional fields from body', function () {
    $data = new UpdateWebsocketApplicationData(name: 'updated-app');
    $request = new UpdateWebsocketApplicationRequest('app-123', $data);
    $body = $request->body()->all();

    expect($body)->toHaveKey('name');
    expect($body)->not->toHaveKey('ping_interval');
    expect($body)->not->toHaveKey('activity_timeout');
    expect($body)->not->toHaveKey('allowed_origins');
});

it('updates a websocket application and returns WebsocketApplicationData with all fields', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListWebsocketApplicationsRequest::class => new LaravelCloudFixture('websocket-applications/list'),
    ]);

    $firstApp = $connector->send(new ListWebsocketApplicationsRequest($firstCluster->id))->dtoOrFail()->first();

    Saloon::fake([
        UpdateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/update'),
    ]);

    $data = new UpdateWebsocketApplicationData(name: 'updated-app');
    $response = $connector->send(new UpdateWebsocketApplicationRequest($firstApp->id, $data));

    Saloon::assertSent(UpdateWebsocketApplicationRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketApplicationData::class);
    expect($dto->id)->toBe('wsa-a14fcb1a-1f09-4091-85b8-3eb74c8ce500');
    expect($dto->name)->toBe('updated-app');
    expect($dto->appId)->toBe('10001');
    expect($dto->allowedOrigins)->toBe([]);
    expect($dto->pingInterval)->toBe(60);
    expect($dto->activityTimeout)->toBe(30);
    expect($dto->maxMessageSize)->toBe(10000);
    expect($dto->maxConnections)->toBe(50);
    expect($dto->key)->toBeString();
    expect($dto->secret)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
