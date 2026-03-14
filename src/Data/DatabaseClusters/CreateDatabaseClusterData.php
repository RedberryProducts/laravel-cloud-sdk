<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\DatabaseType;
use Spatie\LaravelData\Data;

class CreateDatabaseClusterData extends Data
{
    public function __construct(
        public string $name,
        public DatabaseType $type,
        public CloudRegion $region,
        public array $config,
        public ?int $clusterId = null,
    ) {}
}
