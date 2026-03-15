<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\DatabaseType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateDatabaseClusterData extends Data
{
    public function __construct(
        public string $name,
        public DatabaseType $type,
        public CloudRegion $region,
        public NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
        public int|Optional $clusterId = new Optional,
    ) {}
}
