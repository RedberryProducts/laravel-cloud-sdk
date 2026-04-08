<?php

namespace Redberry\LaravelCloudSdk\Data\Metrics;

use Spatie\LaravelData\Data;

class TotalMetricData extends Data
{
    public function __construct(
        /** @var TotalMetricPointData[] */
        public array $data,
        public float $total,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            data: array_map(
                fn (array $point) => TotalMetricPointData::fromResponse($point),
                $attributes['data'],
            ),
            total: $attributes['total'],
        );
    }
}
