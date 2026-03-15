<?php

use App\Data\LaravelCloud\Buckets\UpdateBucketKeyData;

it('serializes only provided fields', function () {
    $data = new UpdateBucketKeyData(name: 'my-key');

    expect($data->toArray())->toBe(['name' => 'my-key']);
});

it('omits optional fields when not provided', function () {
    $data = new UpdateBucketKeyData;

    expect($data->toArray())->not->toHaveKey('name');
});
