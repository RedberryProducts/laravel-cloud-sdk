<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\StopEnvironmentRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new StopEnvironmentRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/stop');
});

it('has the correct HTTP method', function () {
    $request = new StopEnvironmentRequest('env-123');

    expect($request->getMethod())->toBe(Method::POST);
});

it('stops an environment and returns EnvironmentData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/stop-list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/stop-list'),
        StopEnvironmentRequest::class => new LaravelCloudFixture('environments/stop'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApp = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnv = $connector->send(new ListEnvironmentsRequest($firstApp->id))->dtoOrFail()[0];

    $response = $connector->send(new StopEnvironmentRequest($firstEnv->id));

    Saloon::assertSent(StopEnvironmentRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentData::class);
});
