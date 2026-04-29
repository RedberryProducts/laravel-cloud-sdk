<?php

namespace Redberry\LaravelCloudSdk\Data\Caches;

use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateCacheData extends Data
{
    public function __construct(
        public string|Optional $name = new Optional,
        public string|CacheSize|Optional $size = new Optional,
        public bool|Optional $autoUpgradeEnabled = new Optional,
        public bool|Optional $isPublic = new Optional,
        public string|EvictionPolicy|null|Optional $evictionPolicy = new Optional,
    ) {}
}
