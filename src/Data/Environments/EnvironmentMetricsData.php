<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Redberry\LaravelCloudSdk\Data\Metrics\MetricData;
use Redberry\LaravelCloudSdk\Data\Metrics\MetricsMetaData;
use Spatie\LaravelData\Data;

class EnvironmentMetricsData extends Data
{
    public function __construct(
        public MetricData $cpuUsage,
        public MetricData $memoryUsage,
        public MetricData $httpResponseCount,
        public MetricData $replicaCount,
        public MetricData $webWorkersCount,
        public MetricsMetaData $meta,
    ) {}

    public static function fromResponse(array $data, array $meta): self
    {
        return new self(
            cpuUsage: MetricData::fromResponse($data['cpu_usage']),
            memoryUsage: MetricData::fromResponse($data['memory_usage']),
            httpResponseCount: MetricData::fromResponse($data['http_response_count']),
            replicaCount: MetricData::fromResponse($data['replica_count']),
            webWorkersCount: MetricData::fromResponse($data['web_workers_count']),
            meta: MetricsMetaData::fromResponse($meta),
        );
    }
}
