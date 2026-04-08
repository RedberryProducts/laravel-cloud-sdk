<?php

namespace Redberry\LaravelCloudSdk\Data\Metrics;

use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Spatie\LaravelData\Data;

class MetricsMetaData extends Data
{
    public function __construct(
        public string|MetricPeriod $period,
        /** @var string[] */
        public array $availablePeriods,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            period: MetricPeriod::tryFrom($attributes['period']) ?? $attributes['period'],
            availablePeriods: $attributes['available_periods'],
        );
    }
}
