<?php

namespace Redberry\LaravelCloudSdk\Data\Metrics;

use Spatie\LaravelData\Data;

class TotalMetricPointData extends Data
{
    public function __construct(
        public string $x,
        public float $y,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            x: $attributes['x'],
            y: $attributes['y'],
        );
    }
}
