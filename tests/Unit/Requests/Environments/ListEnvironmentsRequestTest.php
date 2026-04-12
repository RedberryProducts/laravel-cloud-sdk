<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Saloon\PaginationPlugin\Contracts\Paginatable;

it('resolves the endpoint correctly', function () {
    $request = new ListEnvironmentsRequest('app-123');

    expect($request->resolveEndpoint())->toBe('/applications/app-123/environments');
});

it('has the correct HTTP method', function () {
    $request = new ListEnvironmentsRequest('app-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('implements Paginatable', function () {
    $request = new ListEnvironmentsRequest('app-123');

    expect($request)->toBeInstanceOf(Paginatable::class);
});

it('includes relationships in default query', function () {
    $request = new ListEnvironmentsRequest('app-123');

    expect($request->query()->all())->toBe([
        'include' => 'application,branch,deployments,currentDeployment,primaryDomain,instances,database,cache,buckets,websocketApplication',
    ]);
});

it('lists environments and returns EnvironmentData collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $response = $connector->send(new ListEnvironmentsRequest($firstApplication->id));

    Saloon::assertSent(ListEnvironmentsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeArray();
    expect($dto[0])->toBeInstanceOf(EnvironmentData::class);
});
