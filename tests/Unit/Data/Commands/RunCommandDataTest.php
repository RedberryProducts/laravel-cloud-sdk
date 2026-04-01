<?php

use Redberry\LaravelCloudSdk\Data\Commands\RunCommandData;

it('can be constructed with required parameters', function () {
    $data = new RunCommandData(command: 'php artisan migrate:status');

    expect($data->command)->toBe('php artisan migrate:status');
});

it('serializes the command correctly', function () {
    $data = new RunCommandData(command: 'php artisan cache:clear');

    $array = $data->toArray();

    expect($array)->toHaveKey('command');
    expect($array['command'])->toBe('php artisan cache:clear');
});
