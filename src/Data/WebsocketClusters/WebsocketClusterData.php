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
        public string|WebsocketServerType $type,
        public string|CloudRegion $region,
        public string|WebsocketStatus $status,
        public string|WebsocketMaxConnections $maxConnections,
        public string|WebsocketConnectionDistributionStrategy $connectionDistributionStrategy,
        public string $hostname,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            type: WebsocketServerType::tryFrom($attributes['type']) ?? $attributes['type'],
            region: CloudRegion::tryFrom($attributes['region']) ?? $attributes['region'],
            status: WebsocketStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            maxConnections: WebsocketMaxConnections::tryFrom($attributes['max_connections']) ?? $attributes['max_connections'],
            connectionDistributionStrategy: WebsocketConnectionDistributionStrategy::tryFrom($attributes['connection_distribution_strategy']) ?? $attributes['connection_distribution_strategy'],
            hostname: $attributes['hostname'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
