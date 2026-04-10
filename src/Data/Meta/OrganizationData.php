<?php

namespace Redberry\LaravelCloudSdk\Data\Meta;

use Spatie\LaravelData\Data;

class OrganizationData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            slug: $attributes['slug'],
        );
    }
}
