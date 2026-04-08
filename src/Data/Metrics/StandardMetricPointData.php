<?php

namespace Redberry\LaravelCloudSdk\Data\Metrics;

use Spatie\LaravelData\Data;

class StandardMetricPointData extends Data
{
    public function __construct(
        public string $x,
        /** @var float[] */
        public array $y,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            x: $attributes['x'],
            y: $attributes['y'],
        );
    }
}
