<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\ListBackgroundProcessesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Saloon\PaginationPlugin\Contracts\Paginatable;

it('resolves the endpoint correctly', function () {
    $request = new ListBackgroundProcessesRequest('inst-123');

    expect($request->resolveEndpoint())->toBe('/instances/inst-123/background-processes');
});

it('has the correct HTTP method', function () {
    $request = new ListBackgroundProcessesRequest('inst-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('implements Paginatable', function () {
    $request = new ListBackgroundProcessesRequest('inst-123');

    expect($request)->toBeInstanceOf(Paginatable::class);
});

it('lists background processes and returns a collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
        ListBackgroundProcessesRequest::class => new LaravelCloudFixture('background-processes/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];
    $firstInstance = $connector->send(new ListInstancesRequest($firstEnvironment->id))->dtoOrFail()[0];
    $response = $connector->send(new ListBackgroundProcessesRequest($firstInstance->id));

    Saloon::assertSent(ListBackgroundProcessesRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeArray();
    expect($dto[0])->toBeInstanceOf(BackgroundProcessData::class);
});
