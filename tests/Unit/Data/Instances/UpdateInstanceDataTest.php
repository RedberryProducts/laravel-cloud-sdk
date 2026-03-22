<?php

use App\Data\LaravelCloud\Instances\UpdateInstanceData;
use App\Enums\LaravelCloud\InstanceScalingType;
use App\Enums\LaravelCloud\InstanceSize;
use Spatie\LaravelData\Optional;

it('defaults all parameters to Optional', function () {
    $data = new UpdateInstanceData;

    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->size)->toBeInstanceOf(Optional::class);
    expect($data->scalingType)->toBeInstanceOf(Optional::class);
    expect($data->maxReplicas)->toBeInstanceOf(Optional::class);
    expect($data->minReplicas)->toBeInstanceOf(Optional::class);
    expect($data->usesSleepMode)->toBeInstanceOf(Optional::class);
    expect($data->sleepTimeout)->toBeInstanceOf(Optional::class);
    expect($data->usesScheduler)->toBeInstanceOf(Optional::class);
    expect($data->usesOctane)->toBeInstanceOf(Optional::class);
    expect($data->usesInertiaSsr)->toBeInstanceOf(Optional::class);
    expect($data->scalingCpuThresholdPercentage)->toBeInstanceOf(Optional::class);
    expect($data->scalingMemoryThresholdPercentage)->toBeInstanceOf(Optional::class);
});

it('serializes set fields as snake_case and excludes unset optionals', function () {
    $data = new UpdateInstanceData(
        name: 'updated-worker',
        size: InstanceSize::FLEX_M_2VCPU_2GB,
        scalingType: InstanceScalingType::AUTO,
        maxReplicas: 10,
        minReplicas: 2,
        usesSleepMode: false,
        sleepTimeout: 30,
        usesScheduler: true,
        usesOctane: false,
        usesInertiaSsr: false,
        scalingCpuThresholdPercentage: 75,
        scalingMemoryThresholdPercentage: null,
    );

    $array = $data->toArray();

    expect($array['name'])->toBe('updated-worker');
    expect($array['size'])->toBe('flex.m-2vcpu-2gb');
    expect($array['scaling_type'])->toBe('auto');
    expect($array['max_replicas'])->toBe(10);
    expect($array['min_replicas'])->toBe(2);
    expect($array['uses_sleep_mode'])->toBeFalse();
    expect($array['sleep_timeout'])->toBe(30);
    expect($array['uses_scheduler'])->toBeTrue();
    expect($array['uses_octane'])->toBeFalse();
    expect($array['uses_inertia_ssr'])->toBeFalse();
    expect($array['scaling_cpu_threshold_percentage'])->toBe(75);
    expect($array['scaling_memory_threshold_percentage'])->toBeNull();
});

it('excludes unset optional fields', function () {
    $data = new UpdateInstanceData(name: 'updated-worker');

    $array = $data->toArray();

    expect($array)->toHaveKey('name');
    expect($array)->not->toHaveKey('size');
    expect($array)->not->toHaveKey('scaling_type');
    expect($array)->not->toHaveKey('uses_scheduler');
    expect($array)->not->toHaveKey('uses_sleep_mode');
    expect($array)->not->toHaveKey('scaling_cpu_threshold_percentage');
});
