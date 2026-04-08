<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\StartEnvironmentRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new StartEnvironmentRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/start');
});

it('has the correct HTTP method', function () {
    $request = new StartEnvironmentRequest('env-123');

    expect($request->getMethod())->toBe(Method::POST);
});

it('includes redeploy in body when provided', function () {
    $request = new StartEnvironmentRequest('env-123', redeploy: true);

    expect($request->body()->all())->toBe(['redeploy' => true]);
});

it('omits redeploy from body when null', function () {
    $request = new StartEnvironmentRequest('env-123');

    expect($request->body()->all())->toBe([]);
});

it('starts an environment and returns DeploymentData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/start-list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/start-list'),
        StartEnvironmentRequest::class => new LaravelCloudFixture('environments/start'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApp = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnv = $connector->send(new ListEnvironmentsRequest($firstApp->id))->dtoOrFail()[0];

    $response = $connector->send(new StartEnvironmentRequest($firstEnv->id));

    Saloon::assertSent(StartEnvironmentRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DeploymentData::class);
});
