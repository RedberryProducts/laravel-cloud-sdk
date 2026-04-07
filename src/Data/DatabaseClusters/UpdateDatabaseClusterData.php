<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Spatie\LaravelData\Data;

class UpdateDatabaseClusterData extends Data
{
    public function __construct(
        public NeonServerlessPostgresConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
    ) {}
}
