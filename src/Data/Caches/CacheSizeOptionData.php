<?php

namespace App\Data\LaravelCloud\Caches;

use Spatie\LaravelData\Data;

class CacheSizeOptionData extends Data
{
    public function __construct(
        public string $value,
        public string $label,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            value: $attributes['value'],
            label: $attributes['label'],
        );
    }
}
