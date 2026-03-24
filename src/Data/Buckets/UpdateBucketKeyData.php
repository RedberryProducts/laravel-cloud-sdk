<?php

namespace Redberry\LaravelCloudSdk\Data\Buckets;

use Spatie\LaravelData\Data;

class UpdateBucketKeyData extends Data
{
    public function __construct(
        public string $name,
    ) {}
}
