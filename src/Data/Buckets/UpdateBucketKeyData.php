<?php

namespace App\Data\LaravelCloud\Buckets;

use Spatie\LaravelData\Data;

class UpdateBucketKeyData extends Data
{
    public function __construct(
        public string $name,
    ) {}
}
