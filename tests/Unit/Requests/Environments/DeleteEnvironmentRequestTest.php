<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Data\Environments\CreateEnvironmentData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Redberry\LaravelCloudSdk\Requests\Applications\CreateApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\DeleteApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\CreateEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\DeleteEnvironmentRequest;
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
        CreateApplicationRequest::class => new LaravelCloudFixture('environments/delete-create-app'),
        CreateEnvironmentRequest::class => new LaravelCloudFixture('environments/delete-create'),
        DeleteEnvironmentRequest::class => new LaravelCloudFixture('environments/delete'),
        DeleteApplicationRequest::class => new LaravelCloudFixture('environments/delete-cleanup-app'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $app = $connector->send(new CreateApplicationRequest(new CreateApplicationData(
        repository: 'RedberryProducts/redberry-automations',
        name: 'sdk-env-delete-test',
        region: CloudRegion::US_EAST_1,
        sourceControlProviderType: SourceControlProvider::GITHUB,
    )))->dtoOrFail();

    $env = $connector->send(new CreateEnvironmentRequest($app->id, new CreateEnvironmentData(
        branch: 'main',
        name: 'sdk-delete-test',
    )))->dtoOrFail();

    $response = $connector->send(new DeleteEnvironmentRequest($env->id));

    Saloon::assertSent(DeleteEnvironmentRequest::class);
    expect($response->successful())->toBeTrue();

    $connector->send(new DeleteApplicationRequest($app->id));
});
