<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\GetDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\ListDeploymentsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetDeploymentRequest('deploy-123');

    expect($request->resolveEndpoint())->toBe('/deployments/deploy-123');
});

it('has the correct HTTP method', function () {
    $request = new GetDeploymentRequest('deploy-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a deployment and returns DeploymentData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListDeploymentsRequest::class => new LaravelCloudFixture('deployments/list'),
        GetDeploymentRequest::class => new LaravelCloudFixture('deployments/get'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();
    $firstDeployment = $connector->send(new ListDeploymentsRequest($firstEnvironment->id))->dtoOrFail()->first();
    $response = $connector->send(new GetDeploymentRequest($firstDeployment->id));

    Saloon::assertSent(GetDeploymentRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DeploymentData::class);
});
