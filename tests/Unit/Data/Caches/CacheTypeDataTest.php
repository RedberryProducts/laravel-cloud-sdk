<?php

use App\Data\LaravelCloud\Caches\CacheSizeOptionData;
use App\Data\LaravelCloud\Caches\CacheTypeData;
use App\Enums\LaravelCloud\CloudRegion;

it('can be created from API response data', function () {
    $responseData = [
        'type' => 'laravel_valkey',
        'label' => 'Laravel Valkey',
        'regions' => ['us-east-1', 'eu-central-1'],
        'sizes' => [
            ['value' => '250mb', 'label' => '250 MB'],
            ['value' => '1gb', 'label' => '1 GB'],
        ],
        'supports_auto_upgrade' => true,
    ];

    $data = CacheTypeData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(CacheTypeData::class);
    expect($data->type)->toBe('laravel_valkey');
    expect($data->label)->toBe('Laravel Valkey');
    expect($data->regions)->toHaveCount(2);
    expect($data->regions[0])->toBe(CloudRegion::US_EAST_1);
    expect($data->regions[1])->toBe(CloudRegion::EU_CENTRAL_1);
    expect($data->sizes)->toHaveCount(2);
    expect($data->sizes[0])->toBeInstanceOf(CacheSizeOptionData::class);
    expect($data->sizes[0]->value)->toBe('250mb');
    expect($data->sizes[0]->label)->toBe('250 MB');
    expect($data->supportsAutoUpgrade)->toBeTrue();
});

it('handles empty sizes and regions', function () {
    $responseData = [
        'type' => 'test_cache',
        'label' => 'Test Cache',
        'regions' => [],
        'sizes' => [],
        'supports_auto_upgrade' => false,
    ];

    $data = CacheTypeData::fromResponse($responseData);

    expect($data->regions)->toBeEmpty();
    expect($data->sizes)->toBeEmpty();
    expect($data->supportsAutoUpgrade)->toBeFalse();
});
