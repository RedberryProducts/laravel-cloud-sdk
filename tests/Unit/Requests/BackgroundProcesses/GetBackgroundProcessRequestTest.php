<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\GetBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\ListBackgroundProcessesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetBackgroundProcessRequest('bp-123');

    expect($request->resolveEndpoint())->toBe('/background-processes/bp-123');
});

it('has the correct HTTP method', function () {
    $request = new GetBackgroundProcessRequest('bp-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes relationships in default query', function () {
    $request = new GetBackgroundProcessRequest('bp-123');

    expect($request->query()->all())->toBe([
        'include' => 'instance',
    ]);
});

it('retrieves a background process and returns BackgroundProcessData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
        ListBackgroundProcessesRequest::class => new LaravelCloudFixture('background-processes/list'),
        GetBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/get'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];
    $firstInstance = $connector->send(new ListInstancesRequest($firstEnvironment->id))->dtoOrFail()[0];
    $firstBackgroundProcess = $connector->send(new ListBackgroundProcessesRequest($firstInstance->id))->dtoOrFail()[0];
    $response = $connector->send(new GetBackgroundProcessRequest($firstBackgroundProcess->id));

    Saloon::assertSent(GetBackgroundProcessRequest::class);
    expect($response->dtoOrFail())->toBeInstanceOf(BackgroundProcessData::class);
});
