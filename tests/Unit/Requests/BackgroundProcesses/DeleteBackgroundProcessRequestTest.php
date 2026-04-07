<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\DeleteBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\ListBackgroundProcessesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteBackgroundProcessRequest('bp-123');

    expect($request->resolveEndpoint())->toBe('/background-processes/bp-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteBackgroundProcessRequest('bp-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
        ListBackgroundProcessesRequest::class => new LaravelCloudFixture('background-processes/list'),
        DeleteBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];
    $firstInstance = $connector->send(new ListInstancesRequest($firstEnvironment->id))->dtoOrFail()[0];
    $firstBackgroundProcess = $connector->send(new ListBackgroundProcessesRequest($firstInstance->id))->dtoOrFail()[0];

    $response = $connector->send(new DeleteBackgroundProcessRequest($firstBackgroundProcess->id));

    Saloon::assertSent(DeleteBackgroundProcessRequest::class);
    expect($response->successful())->toBeTrue();
});
