<?php

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Enums\DatabaseSnapshotStatus;
use Redberry\LaravelCloudSdk\Enums\DatabaseSnapshotType;

it('can be created from response attributes', function () {
    $data = DatabaseSnapshotData::fromResponse([
        'name' => 'my-snapshot',
        'description' => 'Weekly backup',
        'type' => 'scheduled',
        'status' => 'available',
        'storage_bytes' => 1048576,
        'pitr_enabled' => true,
        'pitr_ends_at' => '2026-04-11T10:00:00.000000Z',
        'completed_at' => '2026-04-04T10:00:00.000000Z',
        'created_at' => '2026-04-04T09:00:00.000000Z',
    ], 'snap-123');

    expect($data->id)->toBe('snap-123');
    expect($data->name)->toBe('my-snapshot');
    expect($data->description)->toBe('Weekly backup');
    expect($data->type)->toBe(DatabaseSnapshotType::SCHEDULED);
    expect($data->status)->toBe(DatabaseSnapshotStatus::AVAILABLE);
    expect($data->storageBytes)->toBe(1048576);
    expect($data->pitrEnabled)->toBeTrue();
    expect($data->pitrEndsAt)->toBeInstanceOf(CarbonImmutable::class);
    expect($data->completedAt)->toBeInstanceOf(CarbonImmutable::class);
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null optional fields', function () {
    $data = DatabaseSnapshotData::fromResponse([
        'name' => 'my-snapshot',
        'type' => 'manual',
        'status' => 'creating',
        'storage_bytes' => null,
        'pitr_enabled' => false,
    ], 'snap-456');

    expect($data->description)->toBeNull();
    expect($data->storageBytes)->toBeNull();
    expect($data->pitrEnabled)->toBeFalse();
    expect($data->pitrEndsAt)->toBeNull();
    expect($data->completedAt)->toBeNull();
    expect($data->createdAt)->toBeNull();
});

it('casts type and status to enums', function () {
    $data = DatabaseSnapshotData::fromResponse([
        'name' => 'snap',
        'type' => 'manual',
        'status' => 'pending',
        'pitr_enabled' => false,
    ], 'snap-789');

    expect($data->type)->toBe(DatabaseSnapshotType::MANUAL);
    expect($data->status)->toBe(DatabaseSnapshotStatus::PENDING);
});

it('falls back to raw string for unknown enum values', function () {
    $data = DatabaseSnapshotData::fromResponse([
        'name' => 'snap',
        'type' => 'unknown_type',
        'status' => 'unknown_status',
        'pitr_enabled' => false,
    ], 'snap-000');

    expect($data->type)->toBe('unknown_type');
    expect($data->status)->toBe('unknown_status');
});
