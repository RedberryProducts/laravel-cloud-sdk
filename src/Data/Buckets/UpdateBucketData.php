<?php

namespace App\Data\LaravelCloud\Buckets;

use App\Enums\LaravelCloud\BucketVisibility;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

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
