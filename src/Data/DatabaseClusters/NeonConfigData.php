<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use Spatie\LaravelData\Data;

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
