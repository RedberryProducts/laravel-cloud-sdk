<?php

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentVariableData;
use Redberry\LaravelCloudSdk\Data\Environments\SetEnvironmentVariablesData;
use Redberry\LaravelCloudSdk\Enums\EnvironmentVariableMethod;

it('can be constructed with required parameters', function () {
    $variables = [new EnvironmentVariableData(key: 'APP_ENV', value: 'production')];
    $data = new SetEnvironmentVariablesData(method: EnvironmentVariableMethod::Append, variables: $variables);

    expect($data->method)->toBe(EnvironmentVariableMethod::Append);
    expect($data->variables)->toBe($variables);
});

it('accepts a string method value', function () {
    $data = new SetEnvironmentVariablesData(method: 'set', variables: []);

    expect($data->method)->toBe('set');
});

it('serializes method as enum value', function () {
    $data = new SetEnvironmentVariablesData(
        method: EnvironmentVariableMethod::Set,
        variables: [new EnvironmentVariableData(key: 'APP_KEY', value: 'base64:abc')],
    );

    $array = $data->toArray();

    expect($array['method'])->toBe('set');
    expect($array['variables'])->toBeArray();
    expect($array['variables'][0]['key'])->toBe('APP_KEY');
    expect($array['variables'][0]['value'])->toBe('base64:abc');
});
