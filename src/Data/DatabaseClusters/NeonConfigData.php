<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class NeonConfigData extends Data
{
    public function __construct(
        public float $cuMin,
        public float $cuMax,
        public int $suspendSeconds,
        public int $retentionDays,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            cuMin: $attributes['cu_min'],
            cuMax: $attributes['cu_max'],
            suspendSeconds: $attributes['suspend_seconds'],
            retentionDays: $attributes['retention_days'],
        );
    }
}
