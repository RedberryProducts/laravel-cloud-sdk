<?php

namespace Redberry\LaravelCloudSdk\Data\Metrics;

use Spatie\LaravelData\Data;

class MetricPointData extends Data
{
    public function __construct(
        public string $x,
        /** @var float[]|float|int */
        public array|float|int $y,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            x: $attributes['x'],
            y: $attributes['y'],
        );
    }
}
