<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Spatie\LaravelData\Data;

class DatabaseTypeData extends Data
{
    /**
     * @param  array<int, string|CloudRegion>  $regions
     * @param  array<int, DatabaseTypeConfigSchemaData>  $configSchema
     */
    public function __construct(
        public string $type,
        public string $label,
        public array $regions,
        public array $configSchema,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            label: $attributes['label'],
            regions: array_map(
                fn (string $region) => CloudRegion::tryFrom($region) ?? $region,
                $attributes['regions'],
            ),
            configSchema: array_map(
                fn (array $schema) => DatabaseTypeConfigSchemaData::fromResponse($schema),
                $attributes['config_schema'],
            ),
        );
    }
}
