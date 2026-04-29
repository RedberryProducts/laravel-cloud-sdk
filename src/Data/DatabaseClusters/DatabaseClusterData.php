<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\Concerns\ResolvesDatabaseClusterConfig;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseStatus;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Spatie\LaravelData\Data;

class DatabaseClusterData extends Data
{
    use ResolvesDatabaseClusterConfig;

    public function __construct(
        public string $id,
        public string $name,
        public string|DatabaseType $type,
        public string|DatabaseStatus $status,
        public string|CloudRegion $region,
        public NeonServerlessPostgresConfigData|LaravelMysqlConfigData|AwsRdsConfigData|array $config,
        public DatabaseConnectionData $connection,
        public ?CarbonImmutable $createdAt,
        /** @var DatabaseData[] */
        public array $databases = [],
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            type: DatabaseType::tryFrom($attributes['type']) ?? $attributes['type'],
            status: DatabaseStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            region: CloudRegion::tryFrom($attributes['region']) ?? $attributes['region'],
            config: self::resolveConfig($attributes['type'], $attributes['config']),
            connection: DatabaseConnectionData::fromResponse($attributes['connection']),
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
