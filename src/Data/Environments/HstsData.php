<?php

namespace App\Data\LaravelCloud\Environments;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class HstsData extends Data
{
    public function __construct(
        public ?int $maxAge,
        public bool $includeSubdomains,
        public bool $preload,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            maxAge: $attributes['max_age'] ?? null,
            includeSubdomains: $attributes['include_subdomains'] ?? false,
            preload: $attributes['preload'] ?? false,
        );
    }
}
