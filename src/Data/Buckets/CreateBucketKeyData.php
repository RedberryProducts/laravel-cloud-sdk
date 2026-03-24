<?php

namespace App\Data\LaravelCloud\Buckets;

use App\Enums\LaravelCloud\KeyPermission;
use Spatie\LaravelData\Data;

class CreateBucketKeyData extends Data
{
    public function __construct(
        public string $name,
        public KeyPermission $permission,
    ) {}
}
