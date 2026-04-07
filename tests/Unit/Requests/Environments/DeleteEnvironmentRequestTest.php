<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\DeleteEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteEnvironmentRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteEnvironmentRequest('env-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        DeleteEnvironmentRequest::class => new LaravelCloudFixture('environments/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    $response = $connector->send(new DeleteEnvironmentRequest($firstEnvironment->id));

    Saloon::assertSent(DeleteEnvironmentRequest::class);
    expect($response->successful())->toBeTrue();
})->skip('Fixture pending: record in Phase 10.');
