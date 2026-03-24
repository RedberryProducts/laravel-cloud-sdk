<?php

use App\Data\LaravelCloud\Instances\BackgroundProcessData;
use App\Data\LaravelCloud\Instances\CreateInstanceData;
use App\Data\LaravelCloud\Instances\InstanceData;
use App\Enums\LaravelCloud\DaemonType;
use App\Enums\LaravelCloud\InstanceScalingType;
use App\Enums\LaravelCloud\InstanceSize;
use App\Enums\LaravelCloud\InstanceType;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Instances\CreateInstanceRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::NONE,
        maxReplicas: 1,
        minReplicas: 1,
    );
    $request = new CreateInstanceRequest('env-123', $data);

    expect($request->resolveEndpoint())->toBe('/environments/env-123/instances');
});

it('has the correct HTTP method', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::NONE,
        maxReplicas: 1,
        minReplicas: 1,
    );
    $request = new CreateInstanceRequest('env-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::NONE,
        maxReplicas: 1,
        minReplicas: 1,
    );
    $request = new CreateInstanceRequest('env-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body with all optional fields', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::CUSTOM,
        maxReplicas: 5,
        minReplicas: 1,
        usesScheduler: true,
        scalingCpuThresholdPercentage: 70,
        scalingMemoryThresholdPercentage: 80,
    );
    $request = new CreateInstanceRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('test-worker');
    expect($body['type'])->toBe('service');
    expect($body['size'])->toBe('flex.m-1vcpu-1gb');
    expect($body['scaling_type'])->toBe('custom');
    expect($body['max_replicas'])->toBe(5);
    expect($body['min_replicas'])->toBe(1);
    expect($body['uses_scheduler'])->toBeTrue();
    expect($body['scaling_cpu_threshold_percentage'])->toBe(70);
    expect($body['scaling_memory_threshold_percentage'])->toBe(80);
});

it('excludes unset optional fields from body', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::NONE,
        maxReplicas: 1,
        minReplicas: 1,
    );
    $request = new CreateInstanceRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body)->not->toHaveKey('uses_scheduler');
    expect($body)->not->toHaveKey('scaling_cpu_threshold_percentage');
    expect($body)->not->toHaveKey('scaling_memory_threshold_percentage');
    expect($body)->not->toHaveKey('background_processes');
});

it('creates an instance and returns InstanceData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    Saloon::fake([
        CreateInstanceRequest::class => new LaravelCloudFixture('instances/create'),
    ]);

    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::CUSTOM,
        maxReplicas: 5,
        minReplicas: 1,
        usesScheduler: true,
        backgroundProcesses: [
            new BackgroundProcessData(
                type: DaemonType::CUSTOM,
                processes: 1,
                command: 'php artisan queue:work',
            ),
        ],
    );
    $response = $connector->send(new CreateInstanceRequest($firstEnvironment->id, $data));

    Saloon::assertSent(CreateInstanceRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(InstanceData::class);
});
