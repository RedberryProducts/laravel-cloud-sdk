<?php

use App\Data\LaravelCloud\Environments\FilesystemKeyData;

it('can be constructed with required fields', function () {
    $data = new FilesystemKeyData(id: 'key-123', disk: 'media', isDefaultDisk: true);

    expect($data->id)->toBe('key-123');
    expect($data->disk)->toBe('media');
    expect($data->isDefaultDisk)->toBeTrue();
});

it('serializes to snake_case keys for API requests', function () {
    $data = new FilesystemKeyData(id: 'key-456', disk: 'uploads', isDefaultDisk: false);

    $array = $data->toArray();

    expect($array)->toHaveKey('id');
    expect($array)->toHaveKey('disk');
    expect($array)->toHaveKey('is_default_disk');
    expect($array)->not->toHaveKey('isDefaultDisk');
    expect($array['id'])->toBe('key-456');
    expect($array['disk'])->toBe('uploads');
    expect($array['is_default_disk'])->toBeFalse();
});
