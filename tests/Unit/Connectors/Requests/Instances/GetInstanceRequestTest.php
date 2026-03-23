<?php

use App\Data\LaravelCloud\Instances\InstanceData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Instances\GetInstanceRequest;
use App\Http\Integrations\LaravelCloud\Requests\Instances\ListInstancesRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new GetInstanceRequest('inst-123');

    expect($request->resolveEndpoint())->toBe('/instances/inst-123');
});

it('has the correct HTTP method', function () {
    $request = new GetInstanceRequest('inst-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets an instance and returns InstanceData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    Saloon::fake([
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
    ]);

    $firstInstance = $connector->send(new ListInstancesRequest($firstEnvironment->id))->dtoOrFail()->first();

    Saloon::fake([
        GetInstanceRequest::class => new LaravelCloudFixture('instances/get'),
    ]);

    $response = $connector->send(new GetInstanceRequest($firstInstance->id));

    Saloon::assertSent(GetInstanceRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(InstanceData::class);
});
