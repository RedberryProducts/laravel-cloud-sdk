<?php

namespace Redberry\LaravelCloudSdk\Data\WebsocketClusters;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\WebsocketConnectionDistributionStrategy;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;
use Redberry\LaravelCloudSdk\Enums\WebsocketStatus;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class WebsocketClusterData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public WebsocketServerType $type,
        public CloudRegion $region,
        public WebsocketStatus $status,
        public WebsocketMaxConnections $maxConnections,
        public WebsocketConnectionDistributionStrategy $connectionDistributionStrategy,
        public string $hostname,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            type: WebsocketServerType::from($attributes['type']),
            region: CloudRegion::from($attributes['region']),
            status: WebsocketStatus::from($attributes['status']),
            maxConnections: WebsocketMaxConnections::from($attributes['max_connections']),
            connectionDistributionStrategy: WebsocketConnectionDistributionStrategy::from($attributes['connection_distribution_strategy']),
            hostname: $attributes['hostname'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
