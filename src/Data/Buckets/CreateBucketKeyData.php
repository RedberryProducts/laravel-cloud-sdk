<?php

namespace Redberry\LaravelCloudSdk\Data\Buckets;

use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Spatie\LaravelData\Data;

class CreateBucketKeyData extends Data
{
    public function __construct(
        public string $name,
        public KeyPermission $permission,
    ) {}
}
