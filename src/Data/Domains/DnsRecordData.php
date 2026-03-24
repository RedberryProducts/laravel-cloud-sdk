<?php

namespace Redberry\LaravelCloudSdk\Data\Domains;

use Spatie\LaravelData\Data;

class DnsRecordData extends Data
{
    public function __construct(
        public string $type,
        public ?string $name,
        public ?string $value,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            name: $attributes['name'],
            value: $attributes['value'],
        );
    }
}
