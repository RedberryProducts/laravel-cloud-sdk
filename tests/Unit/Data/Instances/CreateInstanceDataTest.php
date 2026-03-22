<?php

use App\Data\LaravelCloud\Instances\BackgroundProcessConfigData;
use App\Data\LaravelCloud\Instances\BackgroundProcessData;
use App\Data\LaravelCloud\Instances\CreateInstanceData;
use App\Enums\LaravelCloud\DaemonType;
use App\Enums\LaravelCloud\InstanceScalingType;
use App\Enums\LaravelCloud\InstanceSize;
use App\Enums\LaravelCloud\InstanceType;
use Spatie\LaravelData\Optional;

it('requires name, type, size, scalingType, maxReplicas, minReplicas', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::NONE,
        maxReplicas: 1,
        minReplicas: 1,
    );

    $array = $data->toArray();

    expect($array['name'])->toBe('test-worker');
    expect($array['type'])->toBe('service');
    expect($array['size'])->toBe('flex.m-1vcpu-1gb');
    expect($array['scaling_type'])->toBe('none');
    expect($array['max_replicas'])->toBe(1);
    expect($array['min_replicas'])->toBe(1);
});

it('defaults optional fields to Optional', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::NONE,
        maxReplicas: 1,
        minReplicas: 1,
    );

    expect($data->usesScheduler)->toBeInstanceOf(Optional::class);
    expect($data->scalingCpuThresholdPercentage)->toBeInstanceOf(Optional::class);
    expect($data->scalingMemoryThresholdPercentage)->toBeInstanceOf(Optional::class);
    expect($data->backgroundProcesses)->toBeInstanceOf(Optional::class);
});

it('serializes optional fields when set and excludes unset ones', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::AUTO,
        maxReplicas: 5,
        minReplicas: 1,
        usesScheduler: true,
        scalingCpuThresholdPercentage: 70,
    );

    $array = $data->toArray();

    expect($array['uses_scheduler'])->toBeTrue();
    expect($array['scaling_cpu_threshold_percentage'])->toBe(70);
    expect($array)->not->toHaveKey('scaling_memory_threshold_percentage');
    expect($array)->not->toHaveKey('background_processes');
});

it('serializes background_processes when set', function () {
    $data = new CreateInstanceData(
        name: 'test-worker',
        type: InstanceType::SERVICE,
        size: InstanceSize::FLEX_M_1VCPU_1GB,
        scalingType: InstanceScalingType::NONE,
        maxReplicas: 1,
        minReplicas: 1,
        backgroundProcesses: [
            new BackgroundProcessData(
                id: 'bp-1',
                type: DaemonType::WORKER,
                processes: 2,
            ),
        ],
    );

    $array = $data->toArray();

    expect($array['background_processes'])->toBeArray();
    expect($array['background_processes'][0]['type'])->toBe('worker');
    expect($array['background_processes'][0]['processes'])->toBe(2);
});
