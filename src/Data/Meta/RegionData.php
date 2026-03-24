<?php

namespace Redberry\LaravelCloudSdk\Data\Meta;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Spatie\LaravelData\Data;

class RegionData extends Data
{
    public function __construct(
        public CloudRegion $region,
        public string $label,
        public string $flag,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            region: CloudRegion::from($attributes['region']),
            label: $attributes['label'],
            flag: $attributes['flag'],
        );
    }
}
