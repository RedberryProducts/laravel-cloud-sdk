<?php

namespace Redberry\LaravelCloudSdk\Data\Caches;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Spatie\LaravelData\Data;

class CacheTypeData extends Data
{
    /**
     * @param  array<int, CloudRegion>  $regions
     * @param  array<int, CacheSizeOptionData>  $sizes
     */
    public function __construct(
        public string $type,
        public string $label,
        public array $regions,
        public array $sizes,
        public bool $supportsAutoUpgrade,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            label: $attributes['label'],
            regions: array_map(
                fn (string $region) => CloudRegion::from($region),
                $attributes['regions'],
            ),
            sizes: array_map(
                fn (array $size) => CacheSizeOptionData::fromResponse($size),
                $attributes['sizes'],
            ),
            supportsAutoUpgrade: $attributes['supports_auto_upgrade'],
        );
    }
}
