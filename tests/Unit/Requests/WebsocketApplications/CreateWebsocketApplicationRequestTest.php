<?php

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\CreateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\CreateWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateWebsocketApplicationData(name: 'test-app');
    $request = new CreateWebsocketApplicationRequest('cluster-123', $data);

    expect($request->resolveEndpoint())->toBe('/websocket-servers/cluster-123/applications');
});

it('has the correct HTTP method', function () {
    $data = new CreateWebsocketApplicationData(name: 'test-app');
    $request = new CreateWebsocketApplicationRequest('cluster-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new CreateWebsocketApplicationData(name: 'test-app');
    $request = new CreateWebsocketApplicationRequest('cluster-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body with all optional fields', function () {
    $data = new CreateWebsocketApplicationData(
        name: 'test-app',
        pingInterval: 30,
        activityTimeout: 60,
        allowedOrigins: ['https://example.com'],
    );
    $request = new CreateWebsocketApplicationRequest('cluster-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('test-app');
    expect($body['ping_interval'])->toBe(30);
    expect($body['activity_timeout'])->toBe(60);
    expect($body['allowed_origins'])->toBe(['https://example.com']);
});

it('excludes unset optional fields from body', function () {
    $data = new CreateWebsocketApplicationData(name: 'test-app');
    $request = new CreateWebsocketApplicationRequest('cluster-123', $data);
    $body = $request->body()->all();

    expect($body)->toHaveKey('name');
    expect($body)->not->toHaveKey('ping_interval');
    expect($body)->not->toHaveKey('activity_timeout');
    expect($body)->not->toHaveKey('allowed_origins');
});

it('creates a websocket application and returns WebsocketApplicationData with all fields', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        CreateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/create'),
    ]);

    $data = new CreateWebsocketApplicationData(name: 'test-ws-app');
    $response = $connector->send(new CreateWebsocketApplicationRequest($firstCluster->id, $data));

    Saloon::assertSent(CreateWebsocketApplicationRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketApplicationData::class);
    expect($dto->id)->toBe('wsa-a15081a0-8fda-4ff7-bdc5-433f7c6b44d7');
    expect($dto->name)->toBe('test-ws-app');
    expect($dto->appId)->toBe('10002');
    expect($dto->allowedOrigins)->toBe([]);
    expect($dto->pingInterval)->toBe(60);
    expect($dto->activityTimeout)->toBe(30);
    expect($dto->maxMessageSize)->toBe(10000);
    expect($dto->maxConnections)->toBe(50);
    expect($dto->key)->toBeString();
    expect($dto->secret)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
