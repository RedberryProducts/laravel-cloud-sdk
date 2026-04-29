<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\Concerns\ResolvesDatabaseClusterConfig;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class CreateDatabaseClusterData extends Data
{
    use ResolvesDatabaseClusterConfig;

    public function __construct(
        public string $name,
        public string|DatabaseType $type,
        public string|CloudRegion $region,
        public NeonServerlessPostgresConfigData|LaravelMysqlConfigData|AwsRdsConfigData|array $config,
        public int|Optional $clusterId = new Optional,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function fromArray(array $attributes): self
    {
        $config = $attributes['config'] ?? null;

        if (is_array($config)) {
            $config = self::resolveConfig($attributes['type'], $config);
        }

        return new self(
            name: $attributes['name'],
            type: DatabaseType::tryFrom((string) $attributes['type']) ?? $attributes['type'],
            region: CloudRegion::tryFrom((string) $attributes['region']) ?? $attributes['region'],
            config: $config,
            clusterId: $attributes['cluster_id'] ?? new Optional,
        );
    }
}
