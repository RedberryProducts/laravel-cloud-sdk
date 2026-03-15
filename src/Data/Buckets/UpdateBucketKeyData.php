<?php

namespace App\Data\LaravelCloud\Buckets;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateBucketKeyData extends Data
{
    public function __construct(
        public string|Optional $name = new Optional,
    ) {}
}
