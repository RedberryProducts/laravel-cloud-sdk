<?php

use Redberry\LaravelCloudSdk\Data\Environments\DeleteEnvironmentVariablesData;

it('can be constructed with required parameters', function () {
    $data = new DeleteEnvironmentVariablesData(keys: ['APP_ENV', 'APP_KEY']);

    expect($data->keys)->toBe(['APP_ENV', 'APP_KEY']);
});

it('serializes keys correctly', function () {
    $data = new DeleteEnvironmentVariablesData(keys: ['APP_ENV', 'APP_KEY']);

    $array = $data->toArray();

    expect($array)->toHaveKey('keys');
    expect($array['keys'])->toBe(['APP_ENV', 'APP_KEY']);
});
