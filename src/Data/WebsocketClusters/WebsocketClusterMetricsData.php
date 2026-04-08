<?php

namespace Redberry\LaravelCloudSdk\Data\WebsocketClusters;

use Redberry\LaravelCloudSdk\Data\Metrics\MetricData;
use Redberry\LaravelCloudSdk\Data\Metrics\MetricsMetaData;
use Spatie\LaravelData\Data;

class WebsocketClusterMetricsData extends Data
{
    public function __construct(
        public MetricData $connectionCount,
        public MetricData $messageRate,
        public MetricsMetaData $meta,
    ) {}

    public static function fromResponse(array $data, array $meta): self
    {
        return new self(
            connectionCount: MetricData::fromResponse($data['connection_count']),
            messageRate: MetricData::fromResponse($data['message_rate']),
            meta: MetricsMetaData::fromResponse($meta),
        );
    }
}
