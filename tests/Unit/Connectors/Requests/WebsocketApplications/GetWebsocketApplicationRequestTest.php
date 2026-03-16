<?php

use App\Data\LaravelCloud\WebsocketApplications\WebsocketApplicationData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketApplications\GetWebsocketApplicationRequest;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketApplications\ListWebsocketApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new GetWebsocketApplicationRequest('app-123');

    expect($request->resolveEndpoint())->toBe('/websocket-applications/app-123');
});

it('has the correct HTTP method', function () {
    $request = new GetWebsocketApplicationRequest('app-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a websocket application and returns WebsocketApplicationData', function () {
    Saloon::fake([
        ListWebsocketClustersRequest::class => new LaravelCloudFixture('websocket-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstCluster = $connector->send(new ListWebsocketClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListWebsocketApplicationsRequest::class => new LaravelCloudFixture('websocket-applications/list'),
    ]);

    $firstApp = $connector->send(new ListWebsocketApplicationsRequest($firstCluster->id))->dtoOrFail()->first();

    Saloon::fake([
        GetWebsocketApplicationRequest::class => new LaravelCloudFixture('websocket-applications/get'),
    ]);

    $response = $connector->send(new GetWebsocketApplicationRequest($firstApp->id));

    Saloon::assertSent(GetWebsocketApplicationRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(WebsocketApplicationData::class);
    expect($dto->id)->toBeString();
    expect($dto->name)->toBeString();
});
