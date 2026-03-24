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
        public CacheType $type,
        public string $name,
        public CloudRegion $region,
        public CacheSize $size,
        public bool $autoUpgradeEnabled,
        public bool $isPublic,
        public ?EvictionPolicy $evictionPolicy = null,
    ) {}
}
