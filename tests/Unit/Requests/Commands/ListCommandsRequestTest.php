<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Commands\ListCommandsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListCommandsRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/commands');
});

it('has the correct HTTP method', function () {
    $request = new ListCommandsRequest('env-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists commands and returns CommandData collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListCommandsRequest::class => new LaravelCloudFixture('commands/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();
    $response = $connector->send(new ListCommandsRequest($firstEnvironment->id));

    Saloon::assertSent(ListCommandsRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
});
