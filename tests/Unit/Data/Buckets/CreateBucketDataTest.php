<?php

use App\Data\LaravelCloud\Buckets\CreateBucketData;
use App\Enums\LaravelCloud\BucketJurisdiction;
use App\Enums\LaravelCloud\BucketVisibility;
use App\Enums\LaravelCloud\KeyPermission;

it('can be constructed with required parameters', function () {
    $data = new CreateBucketData(
        name: 'my-bucket',
        visibility: BucketVisibility::PRIVATE,
        jurisdiction: BucketJurisdiction::DEFAULT,
        keyName: 'default-key',
        keyPermission: KeyPermission::READ_WRITE,
    );

    expect($data->name)->toBe('my-bucket');
    expect($data->visibility)->toBe(BucketVisibility::PRIVATE);
    expect($data->jurisdiction)->toBe(BucketJurisdiction::DEFAULT);
    expect($data->keyName)->toBe('default-key');
    expect($data->keyPermission)->toBe(KeyPermission::READ_WRITE);
    expect($data->allowedOrigins)->toBeNull();
});

it('can be constructed with optional allowed origins', function () {
    $data = new CreateBucketData(
        name: 'public-bucket',
        visibility: BucketVisibility::PUBLIC,
        jurisdiction: BucketJurisdiction::EU,
        keyName: 'eu-key',
        keyPermission: KeyPermission::READ_ONLY,
        allowedOrigins: ['https://example.com', 'https://app.example.com'],
    );

    expect($data->allowedOrigins)->toBe(['https://example.com', 'https://app.example.com']);
    expect($data->jurisdiction)->toBe(BucketJurisdiction::EU);
});
