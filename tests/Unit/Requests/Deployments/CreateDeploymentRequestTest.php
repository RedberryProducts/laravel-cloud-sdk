<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\CreateDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new CreateDeploymentRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/deployments');
});

it('has the correct HTTP method', function () {
    $request = new CreateDeploymentRequest('env-123');

    expect($request->getMethod())->toBe(Method::POST);
});

it('creates a deployment and returns DeploymentData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        CreateDeploymentRequest::class => new LaravelCloudFixture('deployments/create'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();
    $response = $connector->send(new CreateDeploymentRequest($firstEnvironment->id));

    Saloon::assertSent(CreateDeploymentRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DeploymentData::class);
});
