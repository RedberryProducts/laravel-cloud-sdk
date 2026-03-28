<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Deployments\CreateDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\GetDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\ListDeploymentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists deployments for an environment', function () {
    Saloon::fake([
        ListDeploymentsRequest::class => new LaravelCloudFixture('deployments/list'),
    ]);

    $result = (new LaravelCloud('token'))->deployments('env-a14fe550-4e39-4ff2-8016-a20e4d32a996');

    Saloon::assertSent(ListDeploymentsRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(DeploymentData::class);
});

it('retrieves a single deployment by id', function () {
    Saloon::fake([
        GetDeploymentRequest::class => new LaravelCloudFixture('deployments/get'),
    ]);

    $result = (new LaravelCloud('token'))->deployment('depl-a168da57-c492-49de-82e9-bfe247f829e9');

    Saloon::assertSent(GetDeploymentRequest::class);
    expect($result)->toBeInstanceOf(DeploymentData::class);
});

it('creates a deployment', function () {
    Saloon::fake([
        CreateDeploymentRequest::class => new LaravelCloudFixture('deployments/create'),
    ]);

    $result = (new LaravelCloud('token'))->deploy('env-a14fe550-4e39-4ff2-8016-a20e4d32a996');

    Saloon::assertSent(CreateDeploymentRequest::class);
    expect($result)->toBeInstanceOf(DeploymentData::class);
});
