<?php

use App\Data\LaravelCloud\Environments\UpdateEnvironmentData;
use App\Enums\LaravelCloud\NodeVersion;
use App\Enums\LaravelCloud\PhpVersion;
use Spatie\LaravelData\Optional;

it('defaults all parameters to Optional', function () {
    $data = new UpdateEnvironmentData;

    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->slug)->toBeInstanceOf(Optional::class);
    expect($data->branch)->toBeInstanceOf(Optional::class);
    expect($data->phpVersion)->toBeInstanceOf(Optional::class);
    expect($data->nodeVersion)->toBeInstanceOf(Optional::class);
    expect($data->buildCommand)->toBeInstanceOf(Optional::class);
    expect($data->deployCommand)->toBeInstanceOf(Optional::class);
    expect($data->usesPushToDeploy)->toBeInstanceOf(Optional::class);
    expect($data->usesDeployHook)->toBeInstanceOf(Optional::class);
    expect($data->usesOctane)->toBeInstanceOf(Optional::class);
});

it('serializes set fields as snake_case and excludes unset optionals', function () {
    $data = new UpdateEnvironmentData(
        name: 'staging',
        phpVersion: PhpVersion::V8_4,
        nodeVersion: NodeVersion::V22,
        usesPushToDeploy: true,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('name');
    expect($array)->toHaveKey('php_version');
    expect($array)->toHaveKey('node_version');
    expect($array)->toHaveKey('uses_push_to_deploy');
    expect($array)->not->toHaveKey('phpVersion');
    expect($array)->not->toHaveKey('nodeVersion');
    expect($array)->not->toHaveKey('usesPushToDeploy');
    expect($array)->not->toHaveKey('build_command');
    expect($array['php_version'])->toBe('8.4:1');
    expect($array['node_version'])->toBe('22');
    expect($array['uses_push_to_deploy'])->toBeTrue();
});

it('serializes php_version in API request format with patch suffix', function () {
    expect((new UpdateEnvironmentData(phpVersion: PhpVersion::V8_2))->toArray()['php_version'])->toBe('8.2:1');
    expect((new UpdateEnvironmentData(phpVersion: PhpVersion::V8_3))->toArray()['php_version'])->toBe('8.3:1');
    expect((new UpdateEnvironmentData(phpVersion: PhpVersion::V8_4))->toArray()['php_version'])->toBe('8.4:1');
    expect((new UpdateEnvironmentData(phpVersion: PhpVersion::V8_5))->toArray()['php_version'])->toBe('8.5:1');
});
