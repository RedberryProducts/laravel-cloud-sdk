<?php

use Redberry\LaravelCloudSdk\Data\Instances\InstanceSizeData;

it('builds from response attributes', function () {
    $data = InstanceSizeData::fromResponse([
        'name' => 'flex.m-1vcpu-1gb',
        'label' => 'Flex M (1 vCPU, 1 GB)',
        'description' => 'Suitable for small workloads',
        'cpu_type' => 'shared',
        'compute_class' => 'flex',
        'cpu_count' => 1,
        'memory_mib' => 1024,
    ], 'size-abc');

    expect($data->id)->toBe('size-abc');
    expect($data->name)->toBe('flex.m-1vcpu-1gb');
    expect($data->label)->toBe('Flex M (1 vCPU, 1 GB)');
    expect($data->description)->toBe('Suitable for small workloads');
    expect($data->cpuType)->toBe('shared');
    expect($data->computeClass)->toBe('flex');
    expect($data->cpuCount)->toBe(1);
    expect($data->memoryMib)->toBe(1024);
});

it('maps all fields correctly from response', function () {
    $data = InstanceSizeData::fromResponse([
        'name' => 'flex.l-2vcpu-4gb',
        'label' => 'Flex L (2 vCPU, 4 GB)',
        'description' => 'Suitable for larger workloads',
        'cpu_type' => 'dedicated',
        'compute_class' => 'flex',
        'cpu_count' => 2,
        'memory_mib' => 4096,
    ], 'size-xyz');

    expect($data->cpuType)->toBe('dedicated');
    expect($data->cpuCount)->toBe(2);
    expect($data->memoryMib)->toBe(4096);
});
