<?php

use App\Data\LaravelCloud\Instances\InstanceData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Instances\ListInstancesRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
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
