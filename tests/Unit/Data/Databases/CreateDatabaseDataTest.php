<?php

use App\Data\LaravelCloud\Databases\CreateDatabaseData;

it('can be constructed with a name', function () {
    $data = new CreateDatabaseData(name: 'my-database');

    expect($data->name)->toBe('my-database');
});

it('serializes to array with name key', function () {
    $data = new CreateDatabaseData(name: 'my-database');

    $array = $data->toArray();

    expect($array)->toHaveKey('name');
    expect($array['name'])->toBe('my-database');
});
