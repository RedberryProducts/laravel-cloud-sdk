<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseStatus;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class DatabaseClusterData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string|DatabaseType $type,
        public string|DatabaseStatus $status,
        public string|CloudRegion $region,
        public NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
        public DatabaseConnectionData $connection,
        public ?CarbonImmutable $createdAt,
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

    private static function resolveConfig(string $type, array $config): NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData
    {
        return match ($type) {
            'neon_serverless_postgres_18', 'neon_serverless_postgres_17', 'neon_serverless_postgres_16' => NeonConfigData::fromResponse($config),
            'laravel_mysql_84', 'laravel_mysql_8' => LaravelMysqlConfigData::fromResponse($config),
            'aws_rds_mysql_8', 'aws_rds_postgres_18' => AwsRdsConfigData::fromResponse($config),
        };
    }
}
