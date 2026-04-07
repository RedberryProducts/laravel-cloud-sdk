<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\DeleteApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteApplicationRequest('app-123');

    expect($request->resolveEndpoint())->toBe('/applications/app-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteApplicationRequest('app-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        DeleteApplicationRequest::class => new LaravelCloudFixture('applications/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    $response = $connector->send(new DeleteApplicationRequest($firstApplication->id));

    Saloon::assertSent(DeleteApplicationRequest::class);
    expect($response->successful())->toBeTrue();
})->skip('Fixture pending: record in Phase 10.');
