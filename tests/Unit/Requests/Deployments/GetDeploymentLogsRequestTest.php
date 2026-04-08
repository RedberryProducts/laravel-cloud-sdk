<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentLogsData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\GetDeploymentLogsRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\ListDeploymentsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetDeploymentLogsRequest('deploy-123');

    expect($request->resolveEndpoint())->toBe('/deployments/deploy-123/logs');
});

it('has the correct HTTP method', function () {
    $request = new GetDeploymentLogsRequest('deploy-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets deployment logs and returns DeploymentLogsData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/logs-list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/logs-list'),
        ListDeploymentsRequest::class => new LaravelCloudFixture('deployments/logs-deploy-list'),
        GetDeploymentLogsRequest::class => new LaravelCloudFixture('deployments/logs'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApp = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnv = $connector->send(new ListEnvironmentsRequest($firstApp->id))->dtoOrFail()[0];
    $firstDeployment = $connector->send(new ListDeploymentsRequest($firstEnv->id))->dtoOrFail()[0];

    $response = $connector->send(new GetDeploymentLogsRequest($firstDeployment->id));

    Saloon::assertSent(GetDeploymentLogsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DeploymentLogsData::class);
});
