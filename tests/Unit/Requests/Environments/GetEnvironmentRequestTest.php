<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\GetEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetEnvironmentRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123');
});

it('has the correct HTTP method', function () {
    $request = new GetEnvironmentRequest('env-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets an environment and returns EnvironmentData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    Saloon::fake([
        GetEnvironmentRequest::class => new LaravelCloudFixture('environments/get'),
    ]);

    $response = $connector->send(new GetEnvironmentRequest($firstEnvironment->id));

    Saloon::assertSent(GetEnvironmentRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentData::class);
    expect($dto->id)->toBeString();
    expect($dto->name)->toBeString();
});
