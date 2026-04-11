<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Enums\DatabaseSnapshotStatus;
use Redberry\LaravelCloudSdk\Enums\DatabaseSnapshotType;
use Spatie\LaravelData\Data;

class DatabaseSnapshotData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description,
        public string|DatabaseSnapshotType $type,
        public string|DatabaseSnapshotStatus $status,
        public ?int $storageBytes,
        public ?bool $pitrEnabled,
        public ?CarbonImmutable $pitrEndsAt,
        public ?CarbonImmutable $completedAt,
        public ?CarbonImmutable $createdAt,
        public ?DatabaseClusterData $databaseCluster = null,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            description: $attributes['description'] ?? null,
            type: DatabaseSnapshotType::tryFrom($attributes['type']) ?? $attributes['type'],
            status: DatabaseSnapshotStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            storageBytes: $attributes['storage_bytes'] ?? null,
            pitrEnabled: $attributes['pitr_enabled'],
            pitrEndsAt: isset($attributes['pitr_ends_at'])
                ? CarbonImmutable::parse($attributes['pitr_ends_at'])
                : null,
            completedAt: isset($attributes['completed_at'])
                ? CarbonImmutable::parse($attributes['completed_at'])
                : null,
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
