<?php

namespace App\Data\LaravelCloud\Caches;

use App\Enums\LaravelCloud\CacheSize;
use App\Enums\LaravelCloud\EvictionPolicy;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateCacheData extends Data
{
    public function __construct(
        public string|Optional $name = new Optional,
        public CacheSize|Optional $size = new Optional,
        public bool|Optional $autoUpgradeEnabled = new Optional,
        public bool|Optional $isPublic = new Optional,
        public EvictionPolicy|null|Optional $evictionPolicy = new Optional,
    ) {}
}
