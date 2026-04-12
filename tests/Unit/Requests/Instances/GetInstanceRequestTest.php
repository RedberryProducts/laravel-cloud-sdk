<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\GetInstanceRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetInstanceRequest('inst-123');

    expect($request->resolveEndpoint())->toBe('/instances/inst-123');
});

it('has the correct HTTP method', function () {
    $request = new GetInstanceRequest('inst-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes relationships in default query', function () {
    $request = new GetInstanceRequest('inst-123');

    expect($request->query()->all())->toBe([
        'include' => 'environment,backgroundProcesses',
    ]);
});

it('gets an instance and returns InstanceData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];

    Saloon::fake([
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
    ]);

    $firstInstance = $connector->send(new ListInstancesRequest($firstEnvironment->id))->dtoOrFail()[0];

    Saloon::fake([
        GetInstanceRequest::class => new LaravelCloudFixture('instances/get'),
    ]);

    $response = $connector->send(new GetInstanceRequest($firstInstance->id));

    Saloon::assertSent(GetInstanceRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(InstanceData::class);
});
