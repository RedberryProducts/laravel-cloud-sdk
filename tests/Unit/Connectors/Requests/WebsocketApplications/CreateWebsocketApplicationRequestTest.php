<?php

use App\Data\LaravelCloud\WebsocketApplications\CreateWebsocketApplicationData;
use App\Data\LaravelCloud\WebsocketApplications\WebsocketApplicationData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketApplications\CreateWebsocketApplicationRequest;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

it('sends correct body', function () {
    $data = new CreateWebsocketApplicationData(name: 'test-app', pingInterval: 30);
    $request = new CreateWebsocketApplicationRequest('cluster-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('test-app');
    expect($body['ping_interval'])->toBe(30);
});

it('creates a websocket application and returns WebsocketApplicationData', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        CreateWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/create'),
    ]);

    $data = new CreateWebsocketApplicationData(name: 'test-ws-app');
    $response = $connector->send(new CreateWebsocketApplicationRequest($firstCluster->id, $data));

    Saloon::assertSent(CreateWebsocketApplicationRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketApplicationData::class);
    expect($dto->name)->toBeString();
    expect($dto->appId)->toBeString();
});
