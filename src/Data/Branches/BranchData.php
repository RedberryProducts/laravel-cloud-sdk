<?php

namespace Redberry\LaravelCloudSdk\Data\Branches;

use Spatie\LaravelData\Data;

class BranchData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
        );
    }
}
