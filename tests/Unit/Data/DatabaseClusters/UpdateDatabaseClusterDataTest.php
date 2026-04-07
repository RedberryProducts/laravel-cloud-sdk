<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\UpdateDatabaseClusterData;

it('can be constructed with config', function () {
    $config = new NeonServerlessPostgresConfigData(
        cuMin: 0.5,
        cuMax: 4.0,
        suspendSeconds: 600,
        retentionDays: 14,
    );

    $data = new UpdateDatabaseClusterData(
        config: $config,
    );

    expect($data->config)->toBeInstanceOf(NeonServerlessPostgresConfigData::class);
    expect($data->config->cuMin)->toBe(0.5);
    expect($data->config->cuMax)->toBe(4.0);
});
