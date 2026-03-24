<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Spatie\LaravelData\Data;

class EnvironmentVariableData extends Data
{
    public function __construct(
        public string $key,
        public string $value,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            key: $attributes['key'],
            value: $attributes['value'],
        );
    }
}
