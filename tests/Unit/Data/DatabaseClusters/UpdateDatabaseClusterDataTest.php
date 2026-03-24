<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\UpdateDatabaseClusterData;

it('can be constructed with config', function () {
    $config = new NeonConfigData(
        cuMin: 0.5,
        cuMax: 4.0,
        suspendSeconds: 600,
        retentionDays: 14,
    );

    $data = new UpdateDatabaseClusterData(
        config: $config,
    );

    expect($data->config)->toBeInstanceOf(NeonConfigData::class);
    expect($data->config->cuMin)->toBe(0.5);
    expect($data->config->cuMax)->toBe(4.0);
});
