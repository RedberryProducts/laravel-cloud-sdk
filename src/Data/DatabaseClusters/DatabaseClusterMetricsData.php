<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\Metrics\MetricData;
use Redberry\LaravelCloudSdk\Data\Metrics\MetricsMetaData;
use Spatie\LaravelData\Data;

class DatabaseClusterMetricsData extends Data
{
    public function __construct(
        public MetricData $cpuUsage,
        public MetricData $computeHours,
        public MetricData $memoryUsage,
        public MetricData $writes,
        public MetricData $storageUsage,
        public MetricsMetaData $meta,
    ) {}

    public static function fromResponse(array $data, array $meta): self
    {
        return new self(
            cpuUsage: MetricData::fromResponse($data['cpu_usage']),
            computeHours: MetricData::fromResponse($data['compute_hours']),
            memoryUsage: MetricData::fromResponse($data['memory_usage']),
            writes: MetricData::fromResponse($data['writes']),
            storageUsage: MetricData::fromResponse($data['storage_usage']),
            meta: MetricsMetaData::fromResponse($meta),
        );
    }
}
