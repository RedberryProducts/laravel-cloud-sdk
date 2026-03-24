<?php

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListEnvironmentsRequest('app-123');

    expect($request->resolveEndpoint())->toBe('/applications/app-123/environments');
});

it('has the correct HTTP method', function () {
    $request = new ListEnvironmentsRequest('app-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists environments and returns EnvironmentData collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $response = $connector->send(new ListEnvironmentsRequest($firstApplication->id));

    Saloon::assertSent(ListEnvironmentsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(EnvironmentData::class);
});
