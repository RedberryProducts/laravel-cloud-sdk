<?php

use Redberry\LaravelCloudSdk\Data\Caches\CacheSizeOptionData;

it('can be constructed with all parameters', function () {
    $data = new CacheSizeOptionData(
        value: '250mb',
        label: '250 MB',
    );

    expect($data->value)->toBe('250mb');
    expect($data->label)->toBe('250 MB');
});

it('can be created from API response data', function () {
    $responseData = [
        'value' => 'valkey-pro.50gb',
        'label' => 'Valkey Pro 50 GB',
    ];

    $data = CacheSizeOptionData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(CacheSizeOptionData::class);
    expect($data->value)->toBe('valkey-pro.50gb');
    expect($data->label)->toBe('Valkey Pro 50 GB');
});
