<?php

use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketData;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Spatie\LaravelData\Optional;

it('can be constructed with partial parameters', function () {
    $data = new UpdateBucketData(
        name: 'new-name',
    );

    expect($data->name)->toBe('new-name');
    expect($data->visibility)->toBeInstanceOf(Optional::class);
    expect($data->allowedOrigins)->toBeInstanceOf(Optional::class);
});

it('can be constructed with all parameters', function () {
    $data = new UpdateBucketData(
        name: 'updated-bucket',
        visibility: BucketVisibility::Public,
        allowedOrigins: ['https://example.com'],
    );

    expect($data->name)->toBe('updated-bucket');
    expect($data->visibility)->toBe(BucketVisibility::Public);
    expect($data->allowedOrigins)->toBe(['https://example.com']);
});

it('defaults all parameters to Optional', function () {
    $data = new UpdateBucketData;

    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->visibility)->toBeInstanceOf(Optional::class);
    expect($data->allowedOrigins)->toBeInstanceOf(Optional::class);
});
