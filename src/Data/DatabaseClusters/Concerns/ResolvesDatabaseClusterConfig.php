<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters\Concerns;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\AwsRdsConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;

trait ResolvesDatabaseClusterConfig
{
    /**
     * @param  array<string, mixed>  $config
     * @return NeonServerlessPostgresConfigData|LaravelMysqlConfigData|AwsRdsConfigData|array<string, mixed>
     */
    private static function resolveConfig(string|DatabaseType $type, array $config): NeonServerlessPostgresConfigData|LaravelMysqlConfigData|AwsRdsConfigData|array
    {
        $typeValue = $type instanceof DatabaseType ? $type->value : $type;

        return match ($typeValue) {
            DatabaseType::NeonServerlessPostgres18->value,
            DatabaseType::NeonServerlessPostgres17->value,
            DatabaseType::NeonServerlessPostgres16->value => NeonServerlessPostgresConfigData::fromResponse($config),

            DatabaseType::LaravelMysql84->value,
            DatabaseType::LaravelMysql8->value => LaravelMysqlConfigData::fromResponse($config),

            DatabaseType::AwsRdsMysql8->value,
            DatabaseType::AwsRdsPostgres18->value => AwsRdsConfigData::fromResponse($config),

            default => $config,
        };
    }
}
