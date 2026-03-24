<?php

namespace Redberry\LaravelCloudSdk\Data\Buckets;

use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class BucketKeyData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public KeyPermission $permission,
        public ?string $accessKeyId,
        public ?string $accessKeySecret,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            permission: KeyPermission::from($attributes['permission']),
            accessKeyId: $attributes['access_key_id'],
            accessKeySecret: $attributes['access_key_secret'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
