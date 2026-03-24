<?php

use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListInstancesRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/instances');
});

it('has the correct HTTP method', function () {
    $request = new ListInstancesRequest('env-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists instances and returns InstanceData collection', function () {
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
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
    ]);

    $response = $connector->send(new ListInstancesRequest($firstEnvironment->id));

    Saloon::assertSent(ListInstancesRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(InstanceData::class);
});
