<?php

namespace Redberry\LaravelCloudSdk\Data\Buckets;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Spatie\LaravelData\Data;

class BucketKeyData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string|KeyPermission $permission,
        public ?string $accessKeyId,
        public ?string $accessKeySecret,
        public ?CarbonImmutable $createdAt,
        public ?BucketData $bucket = null,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            permission: KeyPermission::tryFrom($attributes['permission']) ?? $attributes['permission'],
            accessKeyId: $attributes['access_key_id'],
            accessKeySecret: $attributes['access_key_secret'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
