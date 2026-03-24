<?php

namespace Redberry\LaravelCloudSdk\Data\Caches;

use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateCacheData extends Data
{
    public function __construct(
        public string|CacheType $type,
        public string $name,
        public string|CloudRegion $region,
        public string|CacheSize $size,
        public bool $autoUpgradeEnabled,
        public bool $isPublic,
        public string|EvictionPolicy|null $evictionPolicy = null,
    ) {}
}
