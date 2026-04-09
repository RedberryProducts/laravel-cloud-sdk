<?php

use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;

it('can be constructed with required parameters', function () {
    $data = new CreateBucketData(
        name: 'my-bucket',
        visibility: BucketVisibility::Private,
        jurisdiction: BucketJurisdiction::Default,
        keyName: 'default-key',
        keyPermission: KeyPermission::ReadWrite,
    );

    expect($data->name)->toBe('my-bucket');
    expect($data->visibility)->toBe(BucketVisibility::Private);
    expect($data->jurisdiction)->toBe(BucketJurisdiction::Default);
    expect($data->keyName)->toBe('default-key');
    expect($data->keyPermission)->toBe(KeyPermission::ReadWrite);
    expect($data->allowedOrigins)->toBeNull();
});

it('can be constructed with optional allowed origins', function () {
    $data = new CreateBucketData(
        name: 'public-bucket',
        visibility: BucketVisibility::Public,
        jurisdiction: BucketJurisdiction::Eu,
        keyName: 'eu-key',
        keyPermission: KeyPermission::ReadOnly,
        allowedOrigins: ['https://example.com', 'https://app.example.com'],
    );

    expect($data->allowedOrigins)->toBe(['https://example.com', 'https://app.example.com']);
    expect($data->jurisdiction)->toBe(BucketJurisdiction::Eu);
});
