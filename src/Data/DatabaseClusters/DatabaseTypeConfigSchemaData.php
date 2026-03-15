<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use Spatie\LaravelData\Data;

class DatabaseTypeConfigSchemaData extends Data
{
    /**
     * @param  array<int, mixed>|null  $enum
     */
    public function __construct(
        public string $name,
        public string $type,
        public bool $required,
        public string $description,
        public ?array $enum = null,
        public ?int $min = null,
        public ?int $max = null,
        public ?bool $nullable = null,
        public ?string $example = null,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            name: $attributes['name'],
            type: $attributes['type'],
            required: $attributes['required'],
            description: $attributes['description'],
            enum: $attributes['enum'] ?? null,
            min: $attributes['min'] ?? null,
            max: $attributes['max'] ?? null,
            nullable: $attributes['nullable'] ?? null,
            example: $attributes['example'] ?? null,
        );
    }
}
