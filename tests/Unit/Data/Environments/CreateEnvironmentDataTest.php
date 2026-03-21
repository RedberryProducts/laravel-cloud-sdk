<?php

use App\Data\LaravelCloud\Environments\CreateEnvironmentData;
use Spatie\LaravelData\Optional;

it('can be constructed with required parameters', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');

    expect($data->branch)->toBe('main');
    expect($data->name)->toBe('staging');
    expect($data->clusterId)->toBeInstanceOf(Optional::class);
});

it('serializes fields as snake_case', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging', clusterId: 'cluster-abc');

    $array = $data->toArray();

    expect($array)->toHaveKey('branch');
    expect($array)->toHaveKey('name');
    expect($array)->toHaveKey('cluster_id');
    expect($array)->not->toHaveKey('clusterId');
    expect($array['cluster_id'])->toBe('cluster-abc');
});

it('omits optional cluster_id when not set', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');

    $array = $data->toArray();

    expect($array)->not->toHaveKey('cluster_id');
    expect($array)->not->toHaveKey('clusterId');
});
