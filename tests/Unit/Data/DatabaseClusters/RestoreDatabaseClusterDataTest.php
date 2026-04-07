<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\RestoreDatabaseClusterData;
use Spatie\LaravelData\Optional;

it('can be constructed with name only', function () {
    $data = new RestoreDatabaseClusterData(name: 'restored-cluster');

    expect($data->name)->toBe('restored-cluster');
    expect($data->restoreTime)->toBeInstanceOf(Optional::class);
    expect($data->databaseSnapshotId)->toBeInstanceOf(Optional::class);
});

it('serializes name to array', function () {
    $data = new RestoreDatabaseClusterData(name: 'restored-cluster');

    $array = $data->toArray();

    expect($array['name'])->toBe('restored-cluster');
    expect($array)->not->toHaveKey('restore_time');
    expect($array)->not->toHaveKey('database_snapshot_id');
});

it('serializes restore_time when set', function () {
    $data = new RestoreDatabaseClusterData(
        name: 'restored-cluster',
        restoreTime: '2026-04-04T10:00:00Z',
    );

    $array = $data->toArray();

    expect($array['restore_time'])->toBe('2026-04-04T10:00:00Z');
    expect($array)->not->toHaveKey('database_snapshot_id');
});

it('serializes database_snapshot_id when set', function () {
    $data = new RestoreDatabaseClusterData(
        name: 'restored-cluster',
        databaseSnapshotId: 'snap-abc123',
    );

    $array = $data->toArray();

    expect($array['database_snapshot_id'])->toBe('snap-abc123');
    expect($array)->not->toHaveKey('restore_time');
});
