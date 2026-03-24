<?php

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\GetApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new GetApplicationRequest('app-123');

    expect($request->resolveEndpoint())->toBe('/applications/app-123');
});

it('has the correct HTTP method', function () {
    $request = new GetApplicationRequest('app-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets an application and returns ApplicationData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        GetApplicationRequest::class => new LaravelCloudFixture('applications/get'),
    ]);

    $response = $connector->send(new GetApplicationRequest($firstApplication->id));

    Saloon::assertSent(GetApplicationRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(ApplicationData::class);
});
