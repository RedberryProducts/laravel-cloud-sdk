<?php

use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketKeyData;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;

it('can be constructed with all parameters', function () {
    $data = new CreateBucketKeyData(
        name: 'my-key',
        permission: KeyPermission::ReadWrite,
    );

    expect($data->name)->toBe('my-key');
    expect($data->permission)->toBe(KeyPermission::ReadWrite);
});

it('can be constructed with read only permission', function () {
    $data = new CreateBucketKeyData(
        name: 'readonly-key',
        permission: KeyPermission::ReadOnly,
    );

    expect($data->name)->toBe('readonly-key');
    expect($data->permission)->toBe(KeyPermission::ReadOnly);
});
