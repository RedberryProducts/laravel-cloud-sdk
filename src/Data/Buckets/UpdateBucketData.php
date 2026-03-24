<?php

namespace Redberry\LaravelCloudSdk\Data\Buckets;

use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateBucketData extends Data
{
    /**
     * @param  array<int, string>|null|Optional  $allowedOrigins
     */
    public function __construct(
        public string|Optional $name = new Optional,
        public BucketVisibility|Optional $visibility = new Optional,
        public array|null|Optional $allowedOrigins = new Optional,
    ) {}
}
