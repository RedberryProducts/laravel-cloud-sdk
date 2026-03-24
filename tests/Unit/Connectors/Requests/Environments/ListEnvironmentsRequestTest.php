<?php

use App\Data\LaravelCloud\Environments\EnvironmentData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListEnvironmentsRequest('app-123');

    expect($request->resolveEndpoint())->toBe('/applications/app-123/environments');
});

it('has the correct HTTP method', function () {
    $request = new ListEnvironmentsRequest('app-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists environments and returns EnvironmentData collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $response = $connector->send(new ListEnvironmentsRequest($firstApplication->id));

    Saloon::assertSent(ListEnvironmentsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(EnvironmentData::class);
});
