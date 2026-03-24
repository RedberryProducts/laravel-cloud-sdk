<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateDatabaseClusterData extends Data
{
    public function __construct(
        public string $name,
        public string|DatabaseType $type,
        public string|CloudRegion $region,
        public NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
        public int|Optional $clusterId = new Optional,
    ) {}
}
