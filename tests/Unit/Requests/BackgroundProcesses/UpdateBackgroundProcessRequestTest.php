<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\UpdateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\ListBackgroundProcessesRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\UpdateBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new UpdateBackgroundProcessRequest('bp-123', new UpdateBackgroundProcessData);

    expect($request->resolveEndpoint())->toBe('/background-processes/bp-123');
});

it('has the correct HTTP method', function () {
    $request = new UpdateBackgroundProcessRequest('bp-123', new UpdateBackgroundProcessData);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('implements HasBody', function () {
    $request = new UpdateBackgroundProcessRequest('bp-123', new UpdateBackgroundProcessData);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('updates a background process and returns BackgroundProcessData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
        ListBackgroundProcessesRequest::class => new LaravelCloudFixture('background-processes/list'),
        UpdateBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/update'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];
    $firstInstance = $connector->send(new ListInstancesRequest($firstEnvironment->id))->dtoOrFail()[0];
    $firstBackgroundProcess = $connector->send(new ListBackgroundProcessesRequest($firstInstance->id))->dtoOrFail()[0];

    $data = new UpdateBackgroundProcessData(processes: 2);
    $response = $connector->send(new UpdateBackgroundProcessRequest($firstBackgroundProcess->id, $data));

    Saloon::assertSent(UpdateBackgroundProcessRequest::class);
    expect($response->dtoOrFail())->toBeInstanceOf(BackgroundProcessData::class);
});
