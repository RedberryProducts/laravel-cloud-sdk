<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Data\Environments\CreateEnvironmentData;
use Redberry\LaravelCloudSdk\Data\Instances\CreateInstanceData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\InstanceScalingType;
use Redberry\LaravelCloudSdk\Enums\InstanceSize;
use Redberry\LaravelCloudSdk\Enums\InstanceType;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Redberry\LaravelCloudSdk\Requests\Applications\CreateApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\DeleteApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\CreateEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\CreateInstanceRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\DeleteInstanceRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteInstanceRequest('inst-123');

    expect($request->resolveEndpoint())->toBe('/instances/inst-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteInstanceRequest('inst-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        CreateApplicationRequest::class => new LaravelCloudFixture('instances/delete-create-app'),
        CreateEnvironmentRequest::class => new LaravelCloudFixture('instances/delete-create-env'),
        CreateInstanceRequest::class => new LaravelCloudFixture('instances/delete-create'),
        DeleteInstanceRequest::class => new LaravelCloudFixture('instances/delete'),
        DeleteApplicationRequest::class => new LaravelCloudFixture('instances/delete-cleanup-app'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $app = $connector->send(new CreateApplicationRequest(new CreateApplicationData(
        repository: 'RedberryProducts/redberry-automations',
        name: 'sdk-inst-delete-test',
        region: CloudRegion::UsEast1,
        sourceControlProviderType: SourceControlProvider::Github,
    )))->dtoOrFail();

    $env = $connector->send(new CreateEnvironmentRequest($app->id, new CreateEnvironmentData(
        branch: 'main',
        name: 'sdk-delete-test',
    )))->dtoOrFail();

    $instance = $connector->send(new CreateInstanceRequest($env->id, new CreateInstanceData(
        name: 'sdk-delete-test',
        type: InstanceType::Service,
        size: InstanceSize::FlexC1vcpu256mb,
        scalingType: InstanceScalingType::None,
        maxReplicas: 1,
        minReplicas: 1,
    )))->dtoOrFail();

    $response = $connector->send(new DeleteInstanceRequest($instance->id));

    Saloon::assertSent(DeleteInstanceRequest::class);
    expect($response->successful())->toBeTrue();

    $connector->send(new DeleteApplicationRequest($app->id));
});
