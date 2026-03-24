<?php

namespace Redberry\LaravelCloudSdk\Data\Buckets;

use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateBucketData extends Data
{
    /**
     * @param  array<int, string>|null  $allowedOrigins
     */
    public function __construct(
        public string $name,
        public BucketVisibility $visibility,
        public BucketJurisdiction $jurisdiction,
        public string $keyName,
        public KeyPermission $keyPermission,
        public ?array $allowedOrigins = null,
    ) {}
}
