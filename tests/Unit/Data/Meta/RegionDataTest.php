<?php

use Redberry\LaravelCloudSdk\Data\Meta\RegionData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;

it('can be constructed with all parameters', function () {
    $data = new RegionData(
        region: CloudRegion::UsEast1,
        label: 'N. Virginia',
        flag: 'us',
    );

    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->label)->toBe('N. Virginia');
    expect($data->flag)->toBe('us');
});

it('can be created from API response data', function () {
    $responseData = [
        'region' => 'us-east-1',
        'label' => 'N. Virginia',
        'flag' => 'us',
    ];

    $data = RegionData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(RegionData::class);
    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->label)->toBe('N. Virginia');
    expect($data->flag)->toBe('us');
});

it('casts region string to CloudRegion enum', function () {
    $data = RegionData::fromResponse([
        'region' => 'eu-central-1',
        'label' => 'Frankfurt',
        'flag' => 'germany',
    ]);

    expect($data->region)->toBeInstanceOf(CloudRegion::class);
    expect($data->region)->toBe(CloudRegion::EuCentral1);
});
