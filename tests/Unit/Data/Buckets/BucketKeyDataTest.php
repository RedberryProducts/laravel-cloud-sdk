<?php

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;

it('can be created from API response data', function () {
    $responseData = [
        'name' => 'my-key',
        'permission' => 'read_write',
        'access_key_id' => 'AKIAIOSFODNN7EXAMPLE',
        'access_key_secret' => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = BucketKeyData::fromResponse($responseData, 'key-123');

    expect($data)->toBeInstanceOf(BucketKeyData::class);
    expect($data->id)->toBe('key-123');
    expect($data->name)->toBe('my-key');
    expect($data->permission)->toBe(KeyPermission::READ_WRITE);
    expect($data->accessKeyId)->toBe('AKIAIOSFODNN7EXAMPLE');
    expect($data->accessKeySecret)->toBe('wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY');
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null optional fields', function () {
    $responseData = [
        'name' => 'my-key',
        'permission' => 'read_only',
        'access_key_id' => null,
        'access_key_secret' => null,
    ];

    $data = BucketKeyData::fromResponse($responseData, 'key-456');

    expect($data->permission)->toBe(KeyPermission::READ_ONLY);
    expect($data->accessKeyId)->toBeNull();
    expect($data->accessKeySecret)->toBeNull();
    expect($data->createdAt)->toBeNull();
});
