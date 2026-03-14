<?php

namespace App\Data\LaravelCloud\Buckets;

use App\Enums\LaravelCloud\BucketJurisdiction;
use App\Enums\LaravelCloud\BucketVisibility;
use App\Enums\LaravelCloud\KeyPermission;
use Spatie\LaravelData\Data;

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
