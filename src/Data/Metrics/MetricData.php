<?php

namespace Redberry\LaravelCloudSdk\Data\Metrics;

use Spatie\LaravelData\Data;

class MetricData extends Data
{
    public function __construct(
        /** @var MetricPointData[] */
        public array $data,
        /** @var string[]|null */
        public ?array $labels = null,
        /** @var float[]|float|int|null */
        public array|float|int|null $average = null,
        public float|int|null $total = null,
        public float|int|null $current = null,
        public float|int|null $min = null,
        public float|int|null $max = null,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            data: array_map(
                fn (array $point) => MetricPointData::fromResponse($point),
                $attributes['data'],
            ),
            labels: $attributes['labels'] ?? null,
            average: $attributes['average'] ?? null,
            total: $attributes['total'] ?? null,
            current: $attributes['current'] ?? null,
            min: $attributes['min'] ?? null,
            max: $attributes['max'] ?? null,
        );
    }
}
