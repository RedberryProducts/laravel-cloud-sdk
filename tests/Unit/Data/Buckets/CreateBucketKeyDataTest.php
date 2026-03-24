<?php

use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketKeyData;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;

it('can be constructed with all parameters', function () {
    $data = new CreateBucketKeyData(
        name: 'my-key',
        permission: KeyPermission::READ_WRITE,
    );

    expect($data->name)->toBe('my-key');
    expect($data->permission)->toBe(KeyPermission::READ_WRITE);
});

it('can be constructed with read only permission', function () {
    $data = new CreateBucketKeyData(
        name: 'readonly-key',
        permission: KeyPermission::READ_ONLY,
    );

    expect($data->name)->toBe('readonly-key');
    expect($data->permission)->toBe(KeyPermission::READ_ONLY);
});
