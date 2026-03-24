<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class LaravelMysqlConfigData extends Data
{
    public function __construct(
        public string $size,
        public int $storage,
        public bool $isPublic,
        public bool $usesScheduledSnapshots,
        public int $retentionDays,
        public ?string $maintenanceWindow,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            size: $attributes['size'],
            storage: $attributes['storage'],
            isPublic: $attributes['is_public'],
            usesScheduledSnapshots: $attributes['uses_scheduled_snapshots'],
            retentionDays: $attributes['retention_days'],
            maintenanceWindow: $attributes['maintenance_window'] ?? null,
        );
    }
}
