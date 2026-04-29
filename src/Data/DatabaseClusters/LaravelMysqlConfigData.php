<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Enums\DatabaseClusterSize;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class LaravelMysqlConfigData extends Data
{
    public function __construct(
        public string|DatabaseClusterSize $size,
        public int $storage,
        public bool $isPublic,
        public bool $usesScheduledSnapshots,
        public int $retentionDays,
        public ?string $maintenanceWindow,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            size: DatabaseClusterSize::tryFrom($attributes['size']) ?? $attributes['size'],
            storage: $attributes['storage'],
            isPublic: $attributes['is_public'],
            usesScheduledSnapshots: $attributes['uses_scheduled_snapshots'],
            retentionDays: $attributes['retention_days'],
            maintenanceWindow: $attributes['maintenance_window'] ?? null,
        );
    }
}
