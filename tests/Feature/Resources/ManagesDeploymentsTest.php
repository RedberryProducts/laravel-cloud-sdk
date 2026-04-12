<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentLogsData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Users\UserData;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Deployments\CreateDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\GetDeploymentLogsRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\GetDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\ListDeploymentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists deployments for an environment', function () {
    Saloon::fake([
        ListDeploymentsRequest::class => new LaravelCloudFixture('deployments/list'),
    ]);

    $result = (new LaravelCloud('token'))->deployments('env-a15fd671-0b6a-401a-84bf-14105ce69023');

    expect($result)->toBeInstanceOf(LazyCollection::class);

    $first = $result->first();
    expect($first)->toBeInstanceOf(DeploymentData::class);
    expect($first->environment)->toBeInstanceOf(EnvironmentData::class);
    expect($first->initiator)->toBeInstanceOf(UserData::class);

    Saloon::assertSent(ListDeploymentsRequest::class);
});

it('retrieves a single deployment by id', function () {
    Saloon::fake([
        GetDeploymentRequest::class => new LaravelCloudFixture('deployments/get'),
    ]);

    $result = (new LaravelCloud('token'))->deployment('depl-a186809c-c09d-4b3f-b3f9-cebb5b288738');

    Saloon::assertSent(GetDeploymentRequest::class);
    expect($result)->toBeInstanceOf(DeploymentData::class);
    expect($result->environment)->toBeInstanceOf(EnvironmentData::class);
    expect($result->initiator)->toBeInstanceOf(UserData::class);
});

it('creates a deployment', function () {
    Saloon::fake([
        CreateDeploymentRequest::class => new LaravelCloudFixture('deployments/create'),
    ]);

    $result = (new LaravelCloud('token'))->deploy('env-a14fe550-4e39-4ff2-8016-a20e4d32a996');

    Saloon::assertSent(CreateDeploymentRequest::class);
    expect($result)->toBeInstanceOf(DeploymentData::class);
});

it('gets deployment logs', function () {
    Saloon::fake([
        GetDeploymentLogsRequest::class => new LaravelCloudFixture('deployments/logs'),
    ]);

    $result = (new LaravelCloud('token'))->deploymentLogs('depl-a17efa8f-bc0b-4ac2-8971-a787b7a802ea');

    Saloon::assertSent(GetDeploymentLogsRequest::class);
    expect($result)->toBeInstanceOf(DeploymentLogsData::class);
});
