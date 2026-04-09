<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\DeleteApplicationAvatarRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteApplicationAvatarRequest('app-123');

    expect($request->resolveEndpoint())->toBe('/applications/app-123/avatar');
});

it('has the correct HTTP method', function () {
    $request = new DeleteApplicationAvatarRequest('app-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete avatar request successfully', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/avatar-list'),
        DeleteApplicationAvatarRequest::class => new LaravelCloudFixture('applications/delete-avatar'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApp = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];

    $response = $connector->send(new DeleteApplicationAvatarRequest($firstApp->id));

    Saloon::assertSent(DeleteApplicationAvatarRequest::class);
    expect($response->successful())->toBeTrue();
});
