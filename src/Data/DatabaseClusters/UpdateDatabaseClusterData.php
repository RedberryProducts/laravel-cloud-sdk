<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use Spatie\LaravelData\Data;

class UpdateDatabaseClusterData extends Data
{
    public function __construct(
        public NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
    ) {}
}
