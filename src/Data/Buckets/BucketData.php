<?php

namespace Redberry\LaravelCloudSdk\Data\Buckets;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketStatus;
use Redberry\LaravelCloudSdk\Enums\BucketType;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Spatie\LaravelData\Data;

class BucketData extends Data
{
    /**
     * @param  array<int, string>|null  $allowedOrigins
     */
    public function __construct(
        public string $id,
        public string $name,
        public string|BucketType $type,
        public string|BucketStatus $status,
        public string|BucketVisibility $visibility,
        public string|BucketJurisdiction $jurisdiction,
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
            type: BucketType::tryFrom($attributes['type']) ?? $attributes['type'],
            status: BucketStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            visibility: BucketVisibility::tryFrom($attributes['visibility']) ?? $attributes['visibility'],
            jurisdiction: BucketJurisdiction::tryFrom($attributes['jurisdiction']) ?? $attributes['jurisdiction'],
            endpoint: $attributes['endpoint'],
            url: $attributes['url'],
            allowedOrigins: $attributes['allowed_origins'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
