<?php

use App\Data\LaravelCloud\Environments\HstsData;

it('can be created from API response data', function () {
    $data = HstsData::fromResponse([
        'max_age' => 31536000,
        'include_subdomains' => true,
        'preload' => false,
    ]);

    expect($data)->toBeInstanceOf(HstsData::class);
    expect($data->maxAge)->toBe(31536000);
    expect($data->includeSubdomains)->toBeTrue();
    expect($data->preload)->toBeFalse();
});

it('handles null max_age from response', function () {
    $data = HstsData::fromResponse([
        'max_age' => null,
        'include_subdomains' => false,
        'preload' => false,
    ]);

    expect($data->maxAge)->toBeNull();
});

it('serializes to snake_case keys for API requests', function () {
    $data = new HstsData(maxAge: 31536000, includeSubdomains: true, preload: false);

    $array = $data->toArray();

    expect($array)->toHaveKey('max_age');
    expect($array)->toHaveKey('include_subdomains');
    expect($array)->toHaveKey('preload');
    expect($array)->not->toHaveKey('maxAge');
    expect($array)->not->toHaveKey('includeSubdomains');
    expect($array['max_age'])->toBe(31536000);
    expect($array['include_subdomains'])->toBeTrue();
    expect($array['preload'])->toBeFalse();
});

it('serializes null max_age', function () {
    $data = new HstsData(maxAge: null, includeSubdomains: false, preload: false);

    expect($data->toArray()['max_age'])->toBeNull();
});
