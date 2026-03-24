<?php

namespace Redberry\LaravelCloudSdk\Data\Buckets;

use Redberry\LaravelCloudSdk\Enums\BucketStatus;
use Redberry\LaravelCloudSdk\Enums\BucketType;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class BucketData extends Data
{
    /**
     * @param  array<int, string>|null  $allowedOrigins
     */
    public function __construct(
        public string $id,
        public string $name,
        public BucketType $type,
        public BucketStatus $status,
        public BucketVisibility $visibility,
        public BucketJurisdiction $jurisdiction,
        public ?string $endpoint,
        public ?string $url,
        public ?array $allowedOrigins,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            type: BucketType::from($attributes['type']),
            status: BucketStatus::from($attributes['status']),
            visibility: BucketVisibility::from($attributes['visibility']),
            jurisdiction: BucketJurisdiction::from($attributes['jurisdiction']),
            endpoint: $attributes['endpoint'],
            url: $attributes['url'],
            allowedOrigins: $attributes['allowed_origins'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
