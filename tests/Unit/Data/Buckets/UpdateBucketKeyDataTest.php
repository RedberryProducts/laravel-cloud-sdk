<?php

use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketKeyData;

it('requires a name', function () {
    $data = new UpdateBucketKeyData(name: 'my-key');

    expect($data->name)->toBe('my-key');
    expect($data->toArray())->toBe(['name' => 'my-key']);
});
