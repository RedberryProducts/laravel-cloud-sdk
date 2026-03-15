<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use App\Enums\LaravelCloud\CloudRegion;
use Spatie\LaravelData\Data;

class DatabaseTypeData extends Data
{
    /**
     * @param  array<int, CloudRegion>  $regions
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
                fn (string $region) => CloudRegion::from($region),
                $attributes['regions'],
            ),
            configSchema: array_map(
                fn (array $schema) => DatabaseTypeConfigSchemaData::fromResponse($schema),
                $attributes['config_schema'],
            ),
        );
    }
}
