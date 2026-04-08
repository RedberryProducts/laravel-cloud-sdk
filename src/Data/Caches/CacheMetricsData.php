<?php

namespace Redberry\LaravelCloudSdk\Data\Caches;

use Redberry\LaravelCloudSdk\Data\Metrics\MetricData;
use Redberry\LaravelCloudSdk\Data\Metrics\MetricsMetaData;
use Spatie\LaravelData\Data;

class CacheMetricsData extends Data
{
    public function __construct(
        public MetricData $hitsAndMisses,
        public MetricData $throughput,
        public MetricData $size,
        public MetricData $bandwidthUsage,
        public MetricsMetaData $meta,
    ) {}

    public static function fromResponse(array $data, array $meta): self
    {
        return new self(
            hitsAndMisses: MetricData::fromResponse($data['hits_and_misses']),
            throughput: MetricData::fromResponse($data['throughput']),
            size: MetricData::fromResponse($data['size']),
            bandwidthUsage: MetricData::fromResponse($data['bandwidth_usage']),
            meta: MetricsMetaData::fromResponse($meta),
        );
    }
}
