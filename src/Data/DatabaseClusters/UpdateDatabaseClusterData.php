<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Spatie\LaravelData\Data;

class UpdateDatabaseClusterData extends Data
{
    public function __construct(
        public NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
    ) {}
}
