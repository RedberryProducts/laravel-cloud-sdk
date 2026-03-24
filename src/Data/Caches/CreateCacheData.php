<?php

namespace App\Data\LaravelCloud\Caches;

use App\Enums\LaravelCloud\CacheSize;
use App\Enums\LaravelCloud\CacheType;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\EvictionPolicy;
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
