<?php

namespace App\Data\LaravelCloud\WebsocketClusters;

use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\WebsocketConnectionDistributionStrategy;
use App\Enums\LaravelCloud\WebsocketMaxConnections;
use App\Enums\LaravelCloud\WebsocketServerType;
use App\Enums\LaravelCloud\WebsocketStatus;
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
