<?php

namespace Redberry\LaravelCloudSdk\Data\Metrics;

use Spatie\LaravelData\Data;

class StandardMetricData extends Data
{
    public function __construct(
        /** @var string[] */
        public array $labels,
        /** @var float[] */
        public array $average,
        /** @var StandardMetricPointData[] */
        public array $data,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            labels: $attributes['labels'],
            average: $attributes['average'],
            data: array_map(
                fn (array $point) => StandardMetricPointData::fromResponse($point),
                $attributes['data'],
            ),
        );
    }
}
