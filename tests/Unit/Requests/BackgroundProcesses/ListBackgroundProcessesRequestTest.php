<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\ListBackgroundProcessesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListBackgroundProcessesRequest('inst-123');

    expect($request->resolveEndpoint())->toBe('/instances/inst-123/background-processes');
});

it('has the correct HTTP method', function () {
    $request = new ListBackgroundProcessesRequest('inst-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists background processes and returns a collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
        ListBackgroundProcessesRequest::class => new LaravelCloudFixture('background-processes/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();
    $firstInstance = $connector->send(new ListInstancesRequest($firstEnvironment->id))->dtoOrFail()->first();
    $response = $connector->send(new ListBackgroundProcessesRequest($firstInstance->id));

    Saloon::assertSent(ListBackgroundProcessesRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(BackgroundProcessData::class);
});
