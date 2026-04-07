<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseSnapshotData;
use Spatie\LaravelData\Optional;

it('can be constructed with name only', function () {
    $data = new CreateDatabaseSnapshotData(name: 'my-snapshot');

    expect($data->name)->toBe('my-snapshot');
    expect($data->description)->toBeInstanceOf(Optional::class);
});

it('serializes name to array', function () {
    $data = new CreateDatabaseSnapshotData(name: 'my-snapshot');

    $array = $data->toArray();

    expect($array['name'])->toBe('my-snapshot');
    expect($array)->not->toHaveKey('description');
});

it('serializes description when set', function () {
    $data = new CreateDatabaseSnapshotData(name: 'my-snapshot', description: 'Weekly backup');

    $array = $data->toArray();

    expect($array['name'])->toBe('my-snapshot');
    expect($array['description'])->toBe('Weekly backup');
});

it('includes null description when explicitly set to null', function () {
    $data = new CreateDatabaseSnapshotData(name: 'my-snapshot', description: null);

    $array = $data->toArray();

    expect($array)->toHaveKey('description', null);
});
